<?php


namespace App\DataFixtures;


use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_JPEC_REFERENCE = 'user-jpec';
    public const USER_SNOUF_REFERENCE = 'user-snouf';

    const JPEC_TEST_TOKEN = "JeSuisUnToken";
    const SNOUF_TEST_TOKEN = "JeSuisUneFille";

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
        $token = new ApiToken($user, "JeSuisUnToken");
        $manager->persist($token);
        $this->addReference(self::USER_JPEC_REFERENCE, $user);


        $user = new User();
        $user->setEmail("snouf@benkyou.fr")
            ->setUsername("Snouf");
        $manager->persist($user);
        $user
            ->setPassword($this->userPasswordHasher->hashPassword($user, "test"));
        $manager->flush();
        $token = new ApiToken($user, self::SNOUF_TEST_TOKEN);
        $manager->persist($token);
        $this->addReference(self::USER_SNOUF_REFERENCE, $user);
        $manager->flush();
    }
}