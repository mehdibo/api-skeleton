<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public const TOTAL_COUNT = 40;

    public const NORMAL_USER_REFERENCE = 'normal-user';

    public const DEFINED_USERS = [
        [
            'email' => 'mehdi@spoody.me',
            'user-ref' => self::NORMAL_USER_REFERENCE,
        ],
    ];

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager):void
    {
        foreach (self::DEFINED_USERS as $definedUser)
        {
            $user = new User();
            $user->setEmail($definedUser['email'])
                ->setPassword($this->passwordEncoder->encodePassword($user, $definedUser['email']));
            $manager->persist($user);
            $this->addReference($definedUser['user-ref'], $user);
        }
        $faker = Factory::create();
        for ($i = count(self::DEFINED_USERS); $i < self::TOTAL_COUNT; $i++)
        {
            $email = $faker->email;
            $user = new User();
            $user->setEmail($email)
                ->setPassword($this->passwordEncoder->encodePassword($user, $email));
            $manager->persist($user);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['users'];
    }
}
