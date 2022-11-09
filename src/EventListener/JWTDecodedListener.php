<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;

class JWTDecodedListener
{
    public function __construct(private ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param JWTDecodedEvent $event
     *
     * @return void
     */
    public function onJWTDecoded(JWTDecodedEvent $event)
    {
        $payload = $event->getPayload();

        /** @var UserRepository $userRepository */
        $userRepository = $this->manager->getRepository(User::class);
        $user = $userRepository->findOneBy([
            'email' => $payload['username']
        ]);

        if (!$user) {
            $event->markAsInvalid();
        }

        if ($user) {
            if (!$user->isActive()) {
                $event->markAsInvalid();
            }
        }
    }
}
