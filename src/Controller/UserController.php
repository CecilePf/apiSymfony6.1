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
        if ($this->security->isGranted(User::ADMIN)) {
            /** @var UserRepository $userRepository */
            $userRepository = $this->em->getRepository(User::class);
            $users = $userRepository->findBy([
                'active' => true
            ]);
            $this->getJson($users);

            return $this->json([
                'users' => $users
            ]);
        }

        return $this->json([
            'status' => JsonResponse::HTTP_UNAUTHORIZED
        ]);
    }

    #[Route('/citation', name: 'citation')]
    public function getCitation(): JsonResponse
    {
        $url = 'https://kaamelott.chaudie.re/api/random';
        $response = $this->setRequest($url, 'GET');
        $res = json_decode($response->getContent())->citation;

        return $this->json([
            'citation' => $res->citation,
            'personnage' => $res->infos->personnage
        ]);
    }
}
