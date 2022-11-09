<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@test.fr');

        $plaintextPassword = 'test1234';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword)
            ->setActive(true);

        $admin = new User();
        $admin->setEmail('admin@test.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setActive(true);

        $plaintextPassword = 'test1234';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            $plaintextPassword
        );
        $admin->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->persist($admin);
        $manager->flush();
    }
}
