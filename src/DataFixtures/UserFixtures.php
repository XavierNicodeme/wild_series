<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
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
        $user = new User();
        $user->setEmail('user@mail.com');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'password'
        );
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $contributor = new User();
        $contributor->setEmail('contributor@mail.com');
        $contributor->setRoles(user::CONTRIBUTOR);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'password'
        );
        $contributor->setPassword($hashedPassword);
        $manager->persist($contributor);

        $admin = new User();
        $admin->setEmail('admin@mail.com');
        $admin->setRoles(user::ADMIN);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'adminpass',
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);


        $manager->flush();
    }
}
