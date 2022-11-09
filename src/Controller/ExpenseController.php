<?php

namespace App\Controller;

use App\Entity\Expense;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ExpenseController extends BaseController
{
    #[Route('/expenses', name: 'app_expenses', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $user = $this->getCurrentUser();

        $expenses = null;
        if ($user) {
            /** @var ExpenseRepository $expenseRepository */
            $expenseRepository = $this->em->getRepository(Expense::class);
            $expenses = $expenseRepository->findBy([
                'user' => $user
            ]);
        }

        return $this->json([
            'expenses' => $expenses
        ]);
    }

    #[Route('/expenses', name: 'app_expenses_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        if ((!isset($body['cost']) || !isset($body['label'])) || $body['cost'] < 0) {
            return $this->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST
            ]);
        }

        $expense = new Expense;
        $expense->setCost($body['cost'])
            ->setLabel($body['label'])
            ->setUser($this->getCurrentUser())
        ;

        $this->em->persist($expense);
        $this->em->flush();

        return $this->json([
            'status' => JsonResponse::HTTP_CREATED,
            'expense' => $expense
        ]);
    }

    #[Route('/total', name: 'app_expenses_total', methods: ['GET'])]
    public function total(): JsonResponse
    {
        $user = $this->getCurrentUser();
        $total = 0;
        if ($user) {
            /** @var ExpenseRepository $expenseRepository */
            $expenseRepository = $this->em->getRepository(Expense::class);
            $expenses = $expenseRepository->findBy([
                'user' => $user
            ]);

            foreach ($expenses as $expense) {
                $total += $expense->getCost();
            }
        }

        return $this->json([
            'total' => round($total, 2)
        ]);
    }
}
