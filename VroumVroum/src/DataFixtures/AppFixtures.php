<?php

namespace App\DataFixtures;

use App\Entity\Advert;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
  private $passwordEncoder;

  public function __construct(UserPasswordEncoderInterface $userPasswordEncoderInterface)
  {
    $this->passwordEncoder = $userPasswordEncoderInterface;
  }

  public function load(ObjectManager $manager)
  {
    $faker = Factory::create();

    for ($i = 0; $i < 100; $i++) {
      $car = new Advert();

      $car->setTitle($faker->sentence(3, true))
        ->setDescription($faker->paragraph(5))
        ->setCity($faker->state)
        ->setPrice($faker->randomFloat(2, 5, 30000))
        ->setNbDays($faker->randomFloat(2, 5, 400))
        ->setNbKm($faker->randomFloat(2, 5, 5000))
        ->setCarYear($faker->randomFloat(2, 5, 10));

      $manager->persist($car);
    }

    $admin = new User();
    $admin->setEmail('b@b.b')
      ->setRoles(['ROLE_ADMIN'])
      ->setPassword($this->passwordEncoder->encodePassword(
        $admin,
        'b'
      ));

    $user = new User();
    $user->setEmail('a@a.a')
      ->setRoles(['ROLE_USER'])
      ->setPassword($this->passwordEncoder->encodePassword(
        $user,
        'a'
      ));

    $manager->persist($user);
    $manager->persist($admin);

    $manager->flush();
  }
}
