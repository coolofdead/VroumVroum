<?php

namespace App\Controller\Restaurateur;

use App\Entity\Restaurant;
use App\Entity\Commande;
use App\Entity\Plat;
use App\Entity\User;
use App\Form\PlatNewType;
use App\Form\PlatType;
use App\Form\RestaurantType;
use App\Repository\CategoriePlatRepository;
use App\Repository\CategorieRestaurantRepository;
use App\Repository\CommandeRepository;
use App\Repository\PlatRepository;
use App\Repository\RestaurantRepository;
use App\Repository\StatusRepository;
use App\Repository\TypePlatRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

/**
 * @Route("/restaurant")
 */
class RestaurantController extends AbstractController
{
    /**
     * @Route("/all", name="restaurants")
     */
    public function index(RestaurantRepository $restaurantRepository, CategorieRestaurantRepository $cr, UserRepository $ur): Response
    {
        $form = $this->createForm(RestaurantType::class);

        $userSecu = $this->getUser();
        $userEmail= $userSecu->getUsername();
        $user =  $ur->findOneByEmail($userEmail);

        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurantRepository->findBy(['restaurateur'=>$user]),
            'categories' => $cr->findAll(),
            'form' => $form->createView(),
            'formUpdate' => $form->createView(),
        ]);
    }

    /**
     * @Route("/historique/{id}", name="historique")
     */
    public function historique(Restaurant $r, CommandeRepository $cr, StatusRepository $sr): Response
    {
        $status = $sr->findOneBy(['state'=>'Livré']);

        return $this->render('membre/historique-commandes.html.twig', [
            'commandes' => $cr->findBy(['restaurant'=>$r, 'status'=>$status]),
        ]);
    }

    /**
     * @Route("/commandes/{id}", name="commandes")
     */
    public function commandes(Restaurant $r, CommandeRepository $cr, StatusRepository $sr): Response
    {
        $status = $sr->findAll();
        $statusDelivered = $status[count($status) - 1];
        array_shift($status);

        return $this->render('restaurant/orders-tracker.html.twig', [
            'commandes' => $cr->findCommandesWithoutStatusFromRestaurant($statusDelivered, $r),
            'restaurant' => $r,
            'status' => $status,
        ]);
    }

    /**
     * @Route("/commande_status/{id}", name="commandes_update_status")
     */
    public function updateCommandeStatus(Commande $c, Request $r, EntityManagerInterface $em, StatusRepository $sr) {
        $status = $sr->findOneBy(['id'=>$r->request->get('status')]);
        $c->setStatus($status);

        $em->persist($c);
        $em->flush();

        return $this->redirectToRoute('restaurateur_commandes', ['id'=>$c->getRestaurant()->getId()]);
    }

    /**
     * @Route("/new", name="create")
     */
    public function new(Request $request, UserRepository $ur):Response
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //ajout le restaurateur connecté directement au restaurant ajouté
            $userSecu = $this->getUser();
            $userEmail= $userSecu->getUsername();
            $user =  $ur->findOneByEmail($userEmail);


            $restaurant->setRestaurateur($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($restaurant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('restaurateur_restaurants');
    }

    /**
     * @Route("/{id}", name="restaurant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Restaurant $restaurant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($restaurant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('restaurateur_restaurants');
    }

    /**
     * @Route("/edit", name="restaurant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RestaurantRepository $rr, CategorieRestaurantRepository $crr, EntityManagerInterface $entityManager): Response
    {

        $categoryId = $request->request->get("category");
        $restaurantId = $request->request->get("id");
        $longitude = $request->request->get("longitude");
        $latitude = $request->request->get("latitude");
        $nom = $request->request->get("nom");
        $adresse = $request->request->get("adresse");
        $url = $request->request->get("url");
        $email = $request->request->get("email");
        $category = $crr->findOneBy(['id' => $categoryId]);
        $restaurant = $rr->findOneBy(['id' => $restaurantId]);

        if(is_string($nom)){
            $restaurant->setNom($nom);
        }
        if(is_string($adresse)){
            $restaurant->setAdresse($adresse);
        }
        if(is_string($email)){
            $restaurant->setEmail($email);
        }
        if(is_string($url)){
            $restaurant->setUrl($url);
        }


        if(is_numeric($latitude)){
            $restaurant->setLatitude($latitude);
        }
        if(is_numeric($longitude)){
            $restaurant->setLongitude($longitude);
        }

        $restaurant->setCategorie($category);
        $entityManager->persist($restaurant);
        $entityManager->flush();

        return $this->redirectToRoute('restaurateur_restaurants');
    }

    /**
     * @Route("/plats/{id}", name="restaurant_plats", methods={"GET","POST"})
     */
    public function restaurantPlats(Restaurant $restaurant, Request $request, PlatRepository $pr, CategoriePlatRepository $cpr, TypePlatRepository $tpr): Response
    {

        $form = $this->createForm(PlatType::class);
        $form->handleRequest($request);

        $formNew = $this->createForm(PlatNewType::class);
        $formNew->handleRequest($request);

        return $this->render('restaurant/plats-list.html.twig', [
            'plats' => $pr->findBy(['restaurant' => $restaurant]),
            'categories' => $cpr->findAll(),
            'types' => $tpr->findAll(),
            'restaurant' => $restaurant,
            'form' => $form->createView(),
            'formNew' => $formNew->createView()
        ]);
    }

    /**
     * @Route("/create_plat/{id}", name="create_plat", methods={"GET","POST"})
     */

    public function createPlat(Restaurant $restaurant, Request $request): Response
    {
        $plat = new Plat();
        $form = $this->createForm(PlatNewType::class , $plat);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $plat->setRestaurant($restaurant);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($plat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('restaurateur_restaurant_plats', ["id"=>$restaurant->getId()]);
    }

    /**
     * @Route("/delete_plat/{id}", name="delete_plat", methods={"DELETE"})
     */
    public function deletePlat(Plat $plat, Request $request): Response
    {
        // TODO : ca marche deja tu m'as feinté
        $restaurantId = $plat->getRestaurant()->getId();

        if ($this->isCsrfTokenValid('delete'.$plat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($plat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('restaurateur_restaurant_plats', ["id"=>$restaurantId]);
    }

    /**
     * @Route("/update_plat/{id}", name="update_plat", methods={"POST"})
     */
    public function updatePlat(Plat $plat, Request $request, CategoriePlatRepository $cpr, TypePlatRepository $tpr, EntityManagerInterface $em): Response
    {
        $restaurantId = $plat->getRestaurant()->getId();

        $categorieName = $request->request->get("categorie");
        $typeName = $request->request->get("type");
        $categorie = $cpr->findOneBy(['categorie' => $categorieName]);
        $type = $tpr->findOneBy(['type' => $typeName]);

        $nom = $request->request->get("nom");
        $prix = $request->request->get("prix");
        $url = $request->request->get("urlImg");

        if(is_float($prix)){
            $plat->setPrix($prix);
        }
        if(is_string($nom)){
            $plat->setNom($nom);
        }
        if(is_string($url)){
            $plat->setUrlImg($url);
        }


        $plat->setCategorie($categorie);
        $plat->setType($type);

        $em->persist($plat);
        $em->flush();

        return $this->redirectToRoute('restaurateur_restaurant_plats', ["id"=>$restaurantId]);
    }
}
