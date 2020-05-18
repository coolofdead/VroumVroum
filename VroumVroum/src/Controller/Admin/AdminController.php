<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Entity\Restaurant;
use App\Entity\User;
use App\Repository\CategorieRestaurantRepository;
use App\Repository\CommandeRepository;
use App\Repository\NoteRepository;
use App\Repository\RestaurantRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    const COMMANDE_STATE_DELIVERED = '🟢';

    /**
     * @Route("/index", name="index")
     */
    public function index(CommandeRepository $cr, RestaurantRepository $rr, UserRepository $ur)
    {
        $commandes = $cr->findAll();
        $users = $ur->findAll();

        $commandes_sorted = array_count_values(array_map(function($c){return $c->getStatus()->getIcon() == self::COMMANDE_STATE_DELIVERED ? "Livre" : "En cours";}, $commandes));
        $users_sorted = array_count_values(array_map(function($u){return in_array('ROLE_RESTAURATEUR', $u->getRoles()) ? "Membre" : "Restaurateur";}, $users));

        return $this->render('admin/index.html.twig', [
            'admin' => 'AdminController',
            'total_delivery_fee' => getenv('DELIVERY_PRICE') * count($commandes),
            'total_commande' => count($commandes),
            'total_restaurant' => count($rr->findAll()),
            'total_commandes_delivering' => isset($commandes_sorted["En cours"]) ? $commandes_sorted["En cours"] : 0,
            'total_commandes_delivered' => isset($commandes_sorted["Livre"]) ? $commandes_sorted["Livre"] : 0,
            'total_membre' => isset($users_sorted["Membre"]) ? $users_sorted["Membre"] : 0,
            'total_restaurateur' => isset($users_sorted["Restaurateur"]) ? $users_sorted["Restaurateur"] : 0,
        ]);
    }

    /**
     * @Route("/restaurants", name="restaurants")
     */
    public function restaurants(RestaurantRepository $rr, NoteRepository $nr): Response
    {
        $restaurants = $rr->findAll();
        usort($restaurants, 
            function($r1, $r2) {
                return strcmp($r1->getRestaurateur()->getNom() . $r1->getRestaurateur()->getPrenom(), $r2->getRestaurateur()->getNom() . $r2->getRestaurateur()->getPrenom());
            }
        );

        return $this->render('admin/restaurants.html.twig', [
            'restaurants' => $restaurants,
            'notes' => $nr->findAll(),
        ]);
    }

    /**
     * @Route("/commandes-delivering", name="commandes_delivering")
     */
    public function commandesEnCours(CommandeRepository $cr, StatusRepository $sr): Response
    {
        $statusDelivered = $sr->findOneBy(['state'=>'Livré']);

        $commandes = $cr->findCommandesWithoutStatus($statusDelivered);
        usort($commandes, 
            function($c1, $c2) {
                if ($c1->getDate() == $c2->getDate()) {
                    return 0;
                }
                return ($c1->getDate() < $c2->getDate()) ? 1 : -1;
            }
        );

        return $this->render('admin/commandes-delivering.html.twig', [
            's' => $statusDelivered,
            'commandes' => $commandes,
            'title' => 'Commandes en cours',
            'h2' => 'Suivie des commandes en cours',
        ]);
    }

    /**
     * @Route("/restaurant-commandes-delivering/{id}", name="restaurant_commandes_delivering")
     */
    public function restaurantCommandesEnCours(Restaurant $r, CommandeRepository $cr, StatusRepository $sr): Response
    {
        $statusDelivered = $sr->findOneBy(['state'=>'Livré']);
        $commandes = $cr->findCommandesWithoutStatusFromRestaurant($statusDelivered, $r);

        return $this->render('admin/commandes-delivering.html.twig', [
            'restaurant' => $r,
            'commandes' => $commandes,
            'title' => 'Commandes en cours',
            'h2' => 'Suivie des commandes en cours',
        ]);
    }

    /**
     * @Route("/commandes-delivered", name="commandes_delivered")
     */
    public function commandesLivre(CommandeRepository $cr, StatusRepository $sr): Response
    {
        $statusDelivered = $sr->findOneBy(['state'=>'Livré']);

        $commandes = $cr->findBy(['status'=>$statusDelivered]);
        usort($commandes, 
            function($c1, $c2) {
                if ($c1->getDate() == $c2->getDate()) {
                    return 0;
                }
                return ($c1->getDate() < $c2->getDate()) ? 1 : -1;
            }
        );

        return $this->render('admin/commandes-delivering.html.twig', [
            's' => $statusDelivered,
            'commandes' => $commandes,
            'title' => 'Commandes passé',
            'h2' => 'Suivie des commandes livré',
        ]);
    }

    /**
     * @Route("/restaurant-commandes-delivered/{id}", name="restaurant_commandes_delivered")
     */
    public function restaurantCommandesLivre(Restaurant $r, CommandeRepository $cr, StatusRepository $sr): Response
    {
        $statusDelivered = $sr->findOneBy(['state'=>'Livré']);
        $commandes = $cr->findCommandesWithoutStatusFromRestaurant($statusDelivered, $r);

        return $this->render('admin/commandes-delivering.html.twig', [
            'restaurant' => $r,
            'commandes' => $commandes,
            'title' => 'Commandes passé',
            'h2' => 'Suivie des commandes livré',
        ]);
    }

}
