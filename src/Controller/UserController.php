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
        /** @var UserRepository $userRepository */
        $userRepository = $this->em->getRepository(User::class);
        $users = $userRepository->findAll();
        $this->getJson($users);

        return $this->json([
            'users' => $users
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
