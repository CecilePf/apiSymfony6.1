<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BaseController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected SerializerInterface $serializer,
        protected HttpClientInterface $client,
        protected TokenStorageInterface $tokenStorageInterface,
        protected JWTTokenManagerInterface $jwtManager,
        protected Security $security
    )
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->client = $client;
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->security = $security;
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
}