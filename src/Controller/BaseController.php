<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BaseController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected SerializerInterface $serializer,
        protected HttpClientInterface $client,
        protected TokenStorageInterface $tokenStorageInterface,
        protected JWTTokenManagerInterface $jwtManager
    )
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->client = $client;
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    /**
     * @param array $data
     * 
     * @return string
     */
    public function getJson(array $data)
    {
        $this->serializer->serialize($data, 'json');
    }

    /**
     * @param string $url
     * @param string $method
     * 
     * @return mixed $response
     */
    public function setRequest(string $url, string $method)
    {
        $response = $this->client->request($method, $url);

        return $response;
    }

    /**
     * Get user from decoded token
     * @return User $user
     */
    public function getCurrentUser()
    {
        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
        $email = $decodedJwtToken['username'];
        /** @var UserRepository $userRepository */
        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->findOneBy([
            'email' => $email
        ]);

        return $user;
    }
}