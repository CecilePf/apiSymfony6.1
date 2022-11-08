<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class UserController extends BaseController
{
    #[Route('/users', name: 'app_users')]
    public function index(): JsonResponse
    {
        $userRepository = $this->em->getRepository(User::class);
        $users = $userRepository->findAll();
        $this->getJson($users);

        return $this->json([
            'users' => $users
        ]);
    }
}
