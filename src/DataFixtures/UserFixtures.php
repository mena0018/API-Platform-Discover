<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['login' => "user1"]);
        UserFactory::createOne(['login' => "user2"]);
        UserFactory::createOne(['login' => "user3"]);
        UserFactory::createMany(20);
    }
}
