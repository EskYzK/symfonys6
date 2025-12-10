<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $userAdmin = new User();
        $userAdmin->setEmail('admin@taskapp.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setPassword(
            $this->passwordHasher->hashPassword($userAdmin, 'password')
        );
        $manager->persist($userAdmin);

        $this->addReference('user-admin', $userAdmin);

        $userRegular = new User();
        $userRegular->setEmail('user@taskapp.com');
        $userRegular->setRoles(['ROLE_USER']);
        $userRegular->setPassword(
            $this->passwordHasher->hashPassword($userRegular, 'password')
        );
        $manager->persist($userRegular);

        $this->addReference('user-regular', $userRegular);

        $manager->flush();
    }
}