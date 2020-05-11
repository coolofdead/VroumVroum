<?php

namespace App\Controller;

use App\Entity\CategorieRestaurant;
use App\Entity\Plat;
use App\Entity\Restaurant;
use App\Repository\PlatRepository;
use App\Repository\CategorieRestaurantRepository;
use App\Repository\CommandeRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
   /**
    * @Route("/", name="accueil")
    */
   public function accueil(RestaurantRepository $restaurantRepository, CategorieRestaurantRepository $categorieRestaurantRepository)
   {

      return $this->render('membre/accueil.html.twig', [
         'accueil' => 'IndexController',
         'restaurants' => $restaurantRepository->findAll(),
         'categorieRestaurant' => $categorieRestaurantRepository->findAll()

      ]);
   }

   /**
    * @Route("/restaurant-detail/{id}", name="restaurant-detail")
    */
   public function restaurantDetail(Restaurant $r)
   {
      return $this->render('membre/restaurant-detail.html.twig', [
         'accueil' => 'IndexController',
         'restaurant' => $r,
      ]);
   }

   /**
    * @Route("/payement", name="")
    */
   public function payement(Request $request, /* A REMOVE => */ PlatRepository $pr)
   {
      /* TODO Prendre le json dans la request avec la structure suivante :
       [
          {
           id_resto : value,
           id_plat : value
          },
          ...
       ]

      - faire ta tambouille pour calculer le total
      - check si l'utilisateur à la somme
      - rediriger vers soit :
        si il peut pas payer -> page addBalance 
        sinon -> render la page payement 
    */

      return $this->render('membre/payement.html.twig', [
         'accueil' => 'IndexController',
         'items' =>  $pr->findAll(), // Array avec les plats
         'delivery_fee' => getenv('DELIVERY_PRICE'),
         'message_pas_les_sous' => 'tas pas les sous gros', // TODO : à intégrer sur la page payement en facultatif
      ]);
   }

   /**
    * @Route("/compte", name="compte")
    */
   public function compte(Request $request)
   {
      return $this->render('membre/profil.html.twig', [
         'accueil' => 'IndexController',
      ]);
   }

   /**
    * @Route("/historique", name="historique")
    */
   public function historique(CommandeRepository $cr)
   {
      return $this->render('membre/historique-commandes.html.twig', [
         'accueil' => 'IndexController',
         'commandes' => $cr->findAll(),
      ]);
   }

   /**
    * @Route("/deleteUser", name="deleteUser")
    */
   public function deleteUser(Request $request)
   {
      // TODO Générer le formtype adéquat

      return $this->render('membre/profil.html.twig', [
         'accueil' => 'IndexController',
      ]);
   }

   /**
    * @Route("/updatePassword", name="updatePassword")
    */
   public function updatePassword(Request $request)
   {
      // TODO : Générer le formtype adéquat


      return $this->render('membre/profil.html.twig', [
         'accueil' => 'IndexController',
      ]);
   }

   /**
    * @Route("/updateUser", name="updateUser")
    */
   public function updateUser(Request $request)
   {
      // TODO Générer le formtype adéquat

      return $this->render('membre/profil.html.twig', [
         'accueil' => 'IndexController',
      ]);
   }

   /**
    * @Route("/leaveReview", name="leaveReview")
    */
   public function leaveReview(CommandeRepository $cr)
   {
      // TODO

      return $this->render('membre/leave-review.html.twig', [
         'accueil' => 'IndexController',
         'commande' => $cr->findOneBy(["id" => 1])
      ]);
   }

   /**
    * @Route("/followOrder", name="followOrder")
    */
   public function followOrder(CommandeRepository $cr)
   {
      // TODO : passer un id dans l'url pour voir la commande

      return $this->render('membre/command-tracker.html.twig', [
         'accueil' => 'IndexController',
         'commande_en_cours' => $cr->findOneBy(["id" => 1]),
         'heure_de_commande' => '11:05', // si tu peux m'envoyer une heure sous le format heure:minute
         'heure_de_livraison_estimation' => '12:05', // ajouter 1 heure à l'heure de la commande
      ]);
   }

   /**
    * @Route("/addBalance", name="addBalance")
    */
   public function addBalance()
   {
      // TODO

      return $this->render('membre/add-balance.html.twig', [
         'accueil' => 'IndexController',
      ]);
   }
}
