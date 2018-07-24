<?php

namespace App\DataFixtures;

use App\Entity\User\Role;
use App\Manager\User\UserManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRoleFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $roleAdmin = new Role();
        $roleAdmin->setName(UserManagerInterface::TYPE_ADMIN)
            ->setRole('ROLE_ADMIN');

        $roleUser = new Role();
        $roleUser->setName(UserManagerInterface::TYPE_USER)
            ->setRole('ROLE_USER');

        $manager->persist($roleUser);
        $manager->persist($roleAdmin);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
