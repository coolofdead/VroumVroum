<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Entity\Plat;
use App\Entity\Restaurant;
use App\Entity\User;
use App\Form\BalanceType;
use App\Form\UserType;
use App\Repository\PlatRepository;
use App\Repository\CategorieRestaurantRepository;
use App\Repository\CommandeRepository;
use App\Repository\RestaurantRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Encoder\JsonDecode;


class IndexController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


   /**
    * @Route("/accueil", name="accueil")
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
   public function payement(Request $request, PlatRepository $pr,EntityManagerInterface $em,StatusRepository $statusRepository, UserRepository $ur)
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


      $data = json_decode($request->get("items"));
      $plats = [];
      $commandeDeatil = new CommandeDetail();
      $commande = new Commande();
      $totalCommande = 0;
      $idPlatList = [];
      //boucle sur le json de session qui contient les plats
      foreach ($data->items as $item) {
       $plat = $pr->findOneBy(["id"=>$item->id_plat]);
       $plats[] = $plat;
       $idPlatList[] = ["id"=>$item->id_plat];
   }
        $userSecu = $this->getUser();
        $userEmail= $userSecu->getUsername();
        $user =  $ur->findOneByEmail($userEmail);
        $this->session->set('plats-'.$user->getId(), $idPlatList);




      return $this->render('membre/payement.html.twig', [
         'accueil' => 'IndexController',
         'items' => $plats,  // Array avec les plats
         'delivery_fee' => getenv('DELIVERY_PRICE'),
         'hasMoney' => [], // TODO : à intégrer sur la page payement en facultatif
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
    * @Route("/leaveReview/{id}", name="leaveReview")
    */
   public function leaveReview(Commande $commande)
   {
      return $this->render('membre/leave-review.html.twig', [
         'accueil' => 'IndexController',
         'commande' => $commande
      ]);
   }

   /**
    * @Route("/createOrder", name="createOrder")
    */
   public function createOrder(EntityManagerInterface $em,UserRepository $ur, PlatRepository $pr, StatusRepository $statusRepository,MailerInterface $mailer)
   {
       $commandeDetail = new CommandeDetail();
       $commande = new Commande();
       $plats = [];
       $totalCommande = 0;

       //recuperation l'entity user grace au systeme de login
       $userSecu = $this->getUser();
       $userEmail= $userSecu->getUsername();
       $user =  $ur->findOneByEmail($userEmail);


        //recuperation de la liste des id des plats commandés et validé
       $listIdPlat  = $this->session->get("plats-".$user->getId());

       foreach ($listIdPlat as $id){
            $plat = $pr->findOneBy($id);
            $plats[] = $plat;
            $commandeDetail->addPlat($plat);
            $totalCommande += $plat->getPrix();
            $restaurant = $plat->getRestaurant();
            $commande->setRestaurant($restaurant);
       }

        //creation en base des nouveau element relatif a la comande
       $status = $statusRepository->findOneByState("En attente");
       $commande->setStatus($status);
       $commande->setMembre($user);
       $commande->setDetail($commandeDetail);

        $commande->setDate(new \DateTime());
        $commandeDetail->setCommande($commande);
        $commandeDetail->setPrix($totalCommande + getenv('DELIVERY_PRICE'));

        $em->persist($commandeDetail);
        $em->persist($commande);
        $em->flush();
        $idcommande = $commande->getId();
//        return $this->render('debug.html.twig',['debug' => $idcommande]);
        $restaurateur = $restaurant->getRestaurateur();
       $restaurateurEmail =  $restaurateur->getEmail();

       $email = (new TemplatedEmail())
           ->from('delivroomvroom@gmail.com')
           ->to($restaurateurEmail)
           ->cc('guillaume.faugeron@ynov.com')
           //->bcc('bcc@example.com')
           //->replyTo('fabien@example.com')
           ->priority(Email::PRIORITY_HIGH)
           ->subject('Votre restaurant'.$restaurant->getNom().'à recu une commande')
           ->htmlTemplate('email/restaurateur-email.html.twig')
           ->context([
               'commande' => $commande,
               'commandeDetail' => $commandeDetail,
               'plats' => $plats,
               'membre' => $user,
               'heure_de_commande' => $commande->getDate()->format('H:i'),
               'heure_de_livraison_estimation' => $commande->getDate()->add(new DateInterval('PT1H'))->format('H:i'),
               'delivery_fee' => getenv('DELIVERY_PRICE'),
           ]);
       $mailer->send($email);



        return $this->redirectToRoute('followOrder',["id" => $idcommande]);
   }

   /**
    * @Route("/followOrder/{id}", name="followOrder")
    */
   public function followOrder(Commande $commande)
   {
      return $this->render('membre/command-tracker.html.twig', [
         'accueil' => 'IndexController',
         'commande_en_cours' => $commande,
         'heure_de_commande' => $commande->getDate()->format('H:i'), // si tu peux m'envoyer une heure sous le format heure:minute
         'heure_de_livraison_estimation' => $commande->getDate()->add(new DateInterval('PT1H'))->format('H:i'), // ajouter 1 heure à l'heure de la commande
      ]);
   }

   /**
    * @Route("/addBalance", name="addBalance")
    */
   public function addBalance(Request $request, EntityManagerInterface $em)
   {
       $user = $this->getUser();
       $form = $this->createForm(BalanceType::class);
       $form->handleRequest($request);

       if ($request->isMethod('POST') && $form->isSubmitted()) {
           $data = $form->getData();
           $preUpdated = $user->getSolde();
           $money2Add = $data["somme"];
           $newBalance = $money2Add + $preUpdated;
           $user->setSolde($newBalance);
           $em->persist($user);
           $em->flush();
           return $this->redirectToRoute('accueil');

       }
       elseif ($request->isMethod('GET')){
           return $this->render('membre/add-balance.html.twig', [
               'accueil' => 'IndexController',
               'form' => $form->createView()
           ]);
       }
   }
}
