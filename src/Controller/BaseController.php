<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BaseController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected SerializerInterface $serializer,
        protected HttpClientInterface $client
    )
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->client = $client;
    }

    public function getJson(mixed $data)
    {
        $this->serializer->serialize($data, 'json');
    }
}