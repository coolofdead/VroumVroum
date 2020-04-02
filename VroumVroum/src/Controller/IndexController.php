<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
  /**
   * @Route("/", name="home")
   */
  public function index()
  {
    return $this->render('membre/accueil.html.twig', [
      'index' => 'IndexController',
    ]);
  }

  /**
   * @Route("/adverts", name="adverts")
   */
  public function adverts(AdvertRepository $advertRepository)
  {
    return $this->render('advert/adverts.html.twig', [
      'adverts' => 'IndexController',
       'cars' => $advertRepository->findAll()
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
