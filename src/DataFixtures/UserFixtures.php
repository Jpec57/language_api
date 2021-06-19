<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_JPEC_REFERENCE = 'user-jpec';

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail("jpec@benkyou.fr")
            ->setUsername("Jpec");
        $manager->persist($user);
        $user
            ->setPassword($this->userPasswordHasher->hashPassword($user, "test"));
        $manager->flush();
        $this->addReference(self::USER_JPEC_REFERENCE, $user);
    }
}