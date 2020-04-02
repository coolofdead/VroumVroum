<?php

namespace App\DataFixtures;

use App\Entity\CategoriePlat;
use App\Entity\CategorieRestaurant;
use App\Entity\Plat;
use App\Entity\Restaurant;
use App\Entity\TypePlat;
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

        $categorieRestaurant  = new CategorieRestaurant();
        $categorieRestaurant->setCategorie("cat1");
        $manager->persist($categorieRestaurant);


      $restaurateur = new User();
      $restaurateur->setEmail('restaurateur@restaurateur.com')
          ->setRoles(['ROLE_RESTAURATEUR'])
          ->setSolde(0)
          ->setPassword($this->passwordEncoder->encodePassword(
              $restaurateur,
              'r'
          ));
      $manager->persist($restaurateur);



      $restaurant = new Restaurant();

      $restaurant->setCategorie($categorieRestaurant)
        ->setLatitude($faker->randomFloat(2, 5, 30000))
        ->setLongitude($faker->randomFloat(2, 5, 30000))
        ->setRestaurateur($restaurateur)
        ->setNom($faker->realText(20,1))
        ->setAdresse($faker->realText(30,3))
        ->setUrl($faker->imageUrl(640,480));

      $manager->persist($restaurant);



      $categoriePlat = new CategoriePlat();
      $categoriePlat->setCategorie($faker->realText(20,2));
      $manager->persist($categoriePlat);

      $typePlat = new TypePlat();
      $typePlat->setType($faker->realText(20,2));
      $manager->persist($typePlat);



      
      $plats = new Plat();

      $plats->setNom($faker->realText(150,3))
          ->setPrix($faker->randomNumber(1))
          ->setCategorie($categoriePlat)
          ->setRestaurant($restaurant)
          ->setType($typePlat);
      $manager->persist($plats);








        $admin = new User();
        $admin->setEmail('admin@admin.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setSolde(0)
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'm'));



    $manager->persist($admin);
    $manager->flush();
  }
}
