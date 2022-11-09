<?php

namespace App\DataFixtures;

use App\Entity\Expense;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ExpenseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $userRepository = $manager->getRepository(User::class);
        $user = $userRepository->findOneBy([
            'email' => 'user@test.fr'
        ]);

        for ($i = 0; $i < 10; $i++) {
            $expense = new Expense();
            $expense->setCost($faker->randomFloat(2, 0, 1000))
                ->setLabel($faker->sentence(6, true))
                ->setUser($user)
                ->setActive(true)
            ;

            $manager->persist($expense);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}