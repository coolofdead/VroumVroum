<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Entity\Restaurant;
use App\Repository\AdvertRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
  /**
   * @Route("/", name="liste_restaurant")
   */
  public function index(RestaurantRepository $restaurantRepository)
  {
    return $this->render('membre/accueil.html.twig', [
      'index' => 'IndexController',
        'restaurants' => $restaurantRepository->findAll()
    ]);
  }



  /**
   * @Route("/restaurant/{id}", name="unique_restaurant")
   */
    public function restaurant(Restaurant $restaurant)
    {
        return $this->render('membre/restaurant.html.twig', [
            'restaurant' => $restaurant,
            'restaurateur' => $restaurant->getRestaurateur(),
            'plats' => $restaurant->getPlats(),
            ]);
    }







    /**
   * @Route("/estimate-your-car", name="estimate_your_car")
   */
  // public function estimateYourCar(Request $request, EntityManagerInterface $em, IPriceEstimation $priceEstimater)
  // {
  //   $advert = new Advert();

  //   $form = $this->createForm(CarType::class, $advert);

  //   $form->handleRequest($request);
  //   if ($form->isSubmitted() && $form->isValid()) {
  //     $advert->setPrice($priceEstimater->EstimateCar($advert));
  //     $em->persist($advert);
  //     $em->flush();
  //     return $this->redirectToRoute('homepage');
  //   }

  //   return $this->render('estimate/estimate.html.twig', [
  //     'estimateYourCar' => 'IndexController',
  //     'form' => $form->createView()
  //   ]);
  // }

  /**
   * @Route("/test", name="test")
   */
  public function test() {
    return $this->render('membre/accueil.html.twig', [
      'test' => 'IndexController',
      'restaurants' => [],
    ]);
  }
}
