<?php

namespace App\Controller\Restaurateur;

use App\Entity\Restaurant;
use App\Entity\Commande;
use App\Entity\Plat;
use App\Entity\User;
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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $userSecu = $this->getUser();
        $userEmail= $userSecu->getUsername();
        $user =  $ur->findOneByEmail($userEmail);

        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurantRepository->findBy(['restaurateur'=>$user]),
            'categories' => $cr->findAll(),
        ]);
    }

    /**
     * @Route("/historique/{id}", name="historique")
     */
    public function historique(Restaurant $r, CommandeRepository $cr, StatusRepository $sr): Response
    {
        $status = $sr->findAll();

        return $this->render('membre/historique-commandes.html.twig', [
            'commandes' => $cr->findBy(['restaurant'=>$r, 'status'=>$status[count($status)-1]]),
        ]);
    }

    /**
     * @Route("/commandes/{id}", name="commandes")
     */
    public function commandes(Restaurant $r, Request $request, CommandeRepository $cr, StatusRepository $sr): Response
    {
        // TODO : 
        // GET => afficher les commandes avec un status autre que livré
        // charger les commandes pour le restaurant $r

        $status = $sr->findAll();
        array_pop($status);

        return $this->render('restaurant/orders-tracker.html.twig', [
            'commandes' => $cr->findAll(),
            'restaurant' => $r,
            'status' => $status,
        ]);
    }

    /**
     * @Route("/commande_status/{id}", name="commandes_update_status")
     */
    public function updateCommandeStatus(Commande $c, Request $r, EntityManagerInterface $em) {
        // TODO modifie le status de la commande avec les données du POST

        return $this->redirectToRoute('restaurateur_commandes', ['id'=>$c->getRestaurant()->getId()]);
    }

    /**
     * @Route("/new", name="create")
     */
    public function new(Request $request):Response
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //ajout le restaurateur connecté directement au restaurant ajouté
//            $curentRestaurateur = $this->getUser();
//            $restaurant->setRestaurateur($curentRestaurateur);

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

        return $this->redirectToRoute('restaurateur_restaurant_index');
    }

    /**
     * @Route("/edit", name="restaurant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserRepository $ur): Response
    {
        $userSecu = $this->getUser();
        $userEmail= $userSecu->getUsername();
        $user =  $ur->findOneByEmail($userEmail);

        $form = $this->createForm(RestaurantType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('restaurateur_restaurant_index');
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('admin_index');
        }
        else {
            return $this->redirectToRoute('restaurateur_restaurants');
        }
    }

    /**
     * @Route("/plats/{id}", name="restaurant_plats", methods={"GET","POST"})
     */
    public function restaurantPlats(Restaurant $restaurant, Request $request, PlatRepository $pr, CategoriePlatRepository $cpr, TypePlatRepository $tpr): Response
    {
        return $this->render('restaurant/plats-list.html.twig', [
            'plats' => $pr->findBy(['restaurant' => $restaurant]),
            'categories' => $cpr->findAll(),
            'types' => $tpr->findAll(),
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * @Route("/create_plat/{id}", name="create_plat", methods={"GET","POST"})
     */
    public function createPlat(Restaurant $restaurant, Request $request): Response
    {
        // TODO : créer un nouveau plat

        return $this->redirectToRoute('restaurateur_restaurant_plats', ["id"=>$restaurant->getId()]);
    }

    /**
     * @Route("/delete_plat/{id}", name="delete_plat", methods={"DELETE"})
     */
    public function deletePlat(Plat $plat, Request $request): Response
    {
        // TODO : delete le plat
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
    public function updatePlat(Plat $plat, Request $request): Response
    {
        // TODO : update le plat
        $restaurantId = $plat->getRestaurant()->getId();

        return $this->redirectToRoute('restaurateur_restaurant_plats', ["id"=>$restaurantId]);
    }
}
