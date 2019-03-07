<?php

namespace Fixtures;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\User\Entity\User;
use Exception;

/**
 * Class UserFixtures
 * @package Fixtures
 */
class UserFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     *
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $admin = User::create()
            ->setCreatedAt()
            ->setPassword(123)
            ->setName('admin')
            ->setUpdatedAt()
            ->setIsActivated(true)
            ->setIsBlocked(false)
            ->setSex(User::SEX_MALE)
            ->setRole(User::ROLE_ADMIN)
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', time()))
            ->setEmail('admin@admin.ru')
            ->setPhone('123');
        $manager->persist($admin);
        $manager->flush();

        $user = User::create()
            ->setCreatedAt()
            ->setPassword(123)
            ->setName('user')
            ->setUpdatedAt()
            ->setIsActivated(true)
            ->setIsBlocked(false)
            ->setSex(User::SEX_MALE)
            ->setRole(User::ROLE_USER)
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', time()))
            ->setEmail('user@user.ru')
            ->setPhone('123');
        $manager->persist($user);
        $manager->flush();
    }
}
