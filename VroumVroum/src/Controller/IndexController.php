<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Entity\Restaurant;
use App\Repository\PlatRepository;
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
  public function accueil(RestaurantRepository $restaurantRepository) {
    return $this->render('membre/accueil.html.twig', [
      'accueil' => 'IndexController',
      'restaurants' => $restaurantRepository->findAll(),
    ]);
  }

  /**
   * @Route("/restaurant-detail", name="restaurant-detail")
   */
  public function restaurantDetail(RestaurantRepository $restaurantRepository) {
    // TODO : modifier la route pour accepter l'id d'un restau et retourner uniquement ce restau afin afficher dans la page

    return $this->render('membre/restaurant-detail.html.twig', [
      'accueil' => 'IndexController',
      'restaurant' => $restaurantRepository->find(2),
    ]);
  }

  /**
   * @Route("/payement", name="", methods={"POST, GET"})
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
        -> page payement pour ajouter le solde => ajouter un params dans le render avec la somme manquante ainsi que la liste des plats dans la commande
        -> page restaurant_detail (la d'ou tu viens normalement) avec un params en plus dans le render : 'error' => 'text d'erreur', que je print sur la page 
    */

    return $this->render('membre/payement.html.twig', [
      'accueil' => 'IndexController',
      // 'items' =>  $pr->findAll(), // Array avec les plats
      // 'delivery_fee' => getenv('DELIVERY_PRICE'),
      // 'solde' => 30,
    ]);
  }

  /**
   * @Route("/compte", name="compte")
   */
  public function compte(Request $request)
  {
    return $this->render('membre/compte.html.twig', [
      'accueil' => 'IndexController',
      'solde' => 30,
    ]);
  }
}
