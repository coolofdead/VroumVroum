<?php

namespace App\DataFixtures;

use App\Entity\CategoriePlat;
use App\Entity\CategorieRestaurant;
use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Entity\Plat;
use App\Entity\Restaurant;
use App\Entity\Status;
use App\Entity\TypePlat;
use App\Entity\User;
use DateTime;
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

    $categorieRestaurant  = new CategorieRestaurant();
    $categorieRestaurant->setCategorie("cat2");
    $manager->persist($categorieRestaurant);

    $membre = new User();
    $membre->setEmail('toto@gmail.com')
      ->setRoles(['ROLE_MEMBRE'])
      ->setSolde(50)
      ->setPassword($this->passwordEncoder->encodePassword(
        $membre,
        'mdp'
      ));
    $manager->persist($membre);

    $restaurateur = new User();
    $restaurateur->setEmail('restaurateur@restaurateur.com')
      ->setRoles(['ROLE_RESTAURATEUR'])
      ->setSolde(0)
      ->setPassword($this->passwordEncoder->encodePassword(
        $restaurateur,
        'r'
      ));
    $manager->persist($restaurateur);

    $status = [
      (new Status())
        ->setIcon("🔴")
        ->setState("En attente"),
      (new Status())
        ->setIcon("🟠")
        ->setState("En préparation"),
      (new Status())
        ->setIcon("🟡")
        ->setState("En livraison"),
      (new Status())
        ->setIcon("🟢")
        ->setState("Livré"),
    ];

    foreach ($status as $s) {
      $manager->persist($s);
    }

    $restaurants = [];
    for ($i = 1; $i <= 3; $i++) {

      $restaurant = new Restaurant();
      $restaurant->setCategorie($categorieRestaurant)
        ->setLatitude($faker->randomFloat(2, 5, 30000))
        ->setLongitude($faker->randomFloat(2, 5, 30000))
        ->setRestaurateur($restaurateur)
        ->setNom($faker->realText(20, 1))
        ->setAdresse($faker->streetAddress)
        ->setUrl($faker->imageUrl(640, 480));
      $manager->persist($restaurant);
      $restaurants[] = $restaurant;
    }

    $categoriesPlat = [];
    for ($i = 1; $i <= 3; $i++) {

      $categoriePlat = new CategoriePlat();
      $categoriePlat->setCategorie($faker->realText(20, 2));
      $manager->persist($categoriePlat);
      $categoriesPlat[] = $categoriePlat;
    }


    $typesPlat = [];
    for ($i = 1; $i <= 3; $i++) {
      $typePlat = new TypePlat();
      $typePlat->setType($faker->realText(20, 2));
      $manager->persist($typePlat);
      $typesPlat[] = $typePlat;
    }


    $platList = [];
    for ($i = 1; $i <= 12; $i++) {

      $plats = new Plat();
      $plats->setNom($faker->realText(20, 3))
        ->setPrix($faker->randomNumber(1))
        ->setCategorie($categoriesPlat[$faker->numberBetween(0, count($categoriesPlat) - 1)])
        ->setRestaurant($restaurants[$faker->numberBetween(0, count($restaurants) - 1)])
        ->setType($typesPlat[$faker->numberBetween(0, count($typesPlat) - 1)])
        ->setUrlImg("https://picsum.photos/200");
      $manager->persist($plats);
      $platList[] = $plats;
    }

    for ($i = 1; $i <= 12; $i++) {

      $plats = new Plat();
      $plats->setNom($faker->realText(150, 3))
        ->setPrix($faker->randomNumber(1))
        ->setCategorie($categoriesPlat[$faker->numberBetween(0, count($categoriesPlat) - 1)])
        ->setRestaurant($restaurants[$faker->numberBetween(0, count($restaurants) - 1)])
        ->setType($typesPlat[$faker->numberBetween(0, count($typesPlat) - 1)])
        ->setUrlImg("https://picsum.photos/200");
      $manager->persist($plats);
    }

    $command = new Commande();
    $command
    ->setMembre($membre)
    ->setDate(new DateTime())
    ->setStatus($status[0])
    ->setRestaurant($restaurants[0]);
    ;
    $commandDetail = new CommandeDetail();
    $commandDetail->setPrix(11)
      ->setCommande($command)
      ->addPlat($platList[rand(0, count($platList) - 1)])
      ->addPlat($platList[rand(0, count($platList) - 1)])
      ->addPlat($platList[rand(0, count($platList) - 1)]);
    $manager->persist($command);
    $manager->persist($commandDetail);

    $command = new Commande();
    $command
    ->setMembre($membre)
    ->setDate(new DateTime())
    ->setStatus($status[2])
    ->setRestaurant($restaurants[1]);
    ;
    $commandDetail = new CommandeDetail();
    $commandDetail->setPrix(11)
      ->setCommande($command)
      ->addPlat($platList[rand(0, count($platList) - 1)])
      ->addPlat($platList[rand(0, count($platList) - 1)])
      ->addPlat($platList[rand(0, count($platList) - 1)]);
    $manager->persist($command);
    $manager->persist($commandDetail);

    $admin = new User();
    $admin->setEmail('admin@admin.com')
      ->setRoles(['ROLE_ADMIN'])
      ->setSolde(0)
      ->setPassword($this->passwordEncoder->encodePassword($admin, 'a'));
    $manager->persist($admin);

    $user = new User();
    $user->setEmail('user@user.com')
      ->setRoles(['ROLE_MEMBRE'])
      ->setSolde(150)
      ->setPassword($this->passwordEncoder->encodePassword($user, 'user'))
      ->setAdresse("752 rue de la fausse adresse")
      ->setNom("alain")
      ->setPrenom("terrieur")
      ->setPays("Gabon")
      ->setCodePostal(69690)
      ->setVille("Ville-sur-villaine");
    $manager->persist($user);

    $manager->flush();
  }
}
