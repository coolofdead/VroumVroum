<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Note;
use App\Entity\CommandeDetail;
use App\Entity\Plat;
use App\Entity\Quantite;
use App\Entity\Restaurant;
use App\Entity\User;
use App\Form\BalanceType;
use App\Form\UpdateUserType;
use App\Form\UserType;
use App\Repository\PlatRepository;
use App\Repository\CategorieRestaurantRepository;
use App\Repository\CommandeRepository;
use App\Repository\NoteRepository;
use App\Repository\RestaurantRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

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
   public function accueil(RestaurantRepository $restaurantRepository, CategorieRestaurantRepository $categorieRestaurantRepository, NoteRepository $nr)
   {
      return $this->render('membre/accueil.html.twig', [
         'accueil' => 'IndexController',
         'restaurants' => $restaurantRepository->findAll(),
         'categorieRestaurant' => $categorieRestaurantRepository->findAll(),
         'notes' => $nr->findAll()
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
   public function payement(Request $request, PlatRepository $pr, EntityManagerInterface $em, StatusRepository $statusRepository, UserRepository $ur)
   {
      $data = json_decode($request->get("items"));
      if (!isset($data->items)) {
         return $this->redirectToRoute('accueil');
      }
      $plats = [];
      $idPlatList = [];
      //boucle sur le json de session qui contient les plats
      foreach ($data->items as $item) {
         $plat = $pr->findOneBy(["id" => $item->id_plat]);
         $plats[] = $plat;
         $idPlatList[] = ["id" => $item->id_plat];
      }

      $userSecu = $this->getUser();
      $userEmail = $userSecu->getUsername();
      $user =  $ur->findOneByEmail($userEmail);

      //ajout de la liste des plats de la commande en session
      $this->session->set('plats-' . $user->getId(), $idPlatList);

      return $this->render('membre/payement.html.twig', [
         'accueil' => 'IndexController',
         'items' => $plats,  // Array avec les plats
         'delivery_fee' => getenv('DELIVERY_PRICE'),
         'hasMoney' => [], // TODO : à intégrer sur la page payement en facultatif
      ]);
   }

   /**
    * @Route("/compte/{id}", name="compte")
    */
   public function compte(User $user)
   {
      $form = $this->createForm(UpdateUserType::class, $user);

      return $this->render('membre/profil.html.twig', [
         'accueil' => 'IndexController',
         'form' => $form->createView()
      ]);
   }

   /**
    * @Route("/historique", name="historique")
    */
   public function historique(CommandeRepository $cr, UserRepository $ur)
   {
      $userSecu = $this->getUser();
      $userEmail = $userSecu->getUsername();
      $user =  $ur->findOneByEmail($userEmail);

      return $this->render('membre/historique-commandes.html.twig', [
         'accueil' => 'IndexController',
         'commandes' => $cr->findBy(["membre" => $user]),
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
    *
    */
   public function updatePassword(Request $request)
   {
      // TODO : Générer le formtype adéquat

      return $this->render('membre/profil.html.twig', [
         'accueil' => 'IndexController',
      ]);
   }

   /**
    * @Route("compte/updateUser/{id}", name="updateUser", methods={"POST"})
    */
   public function updateUser(Request $request, User $user, UserRepository $ur, EntityManagerInterface $em)
   {


      $userSecu = $this->getUser();
      $userEmail = $userSecu->getUsername();
      $userCurrent =  $ur->findOneByEmail($userEmail);

      if (in_array('ROLE_ADMIN', $userCurrent->getRoles())) {
         $route = in_array('ROLE_RESTAURATEUR', $userCurrent->getRoles()) ? 'admin_restaurateurs' : 'admin_membres';



          $prenom = $request->request->get("prenom");
          $nom = $request->request->get("nom");
          $adresse = $request->request->get("adresse");
          $ville = $request->request->get("ville");
          $pays = $request->request->get("pays");
          $cp = $request->request->get("cp");
          $email = $request->request->get("email");

          if(is_string($prenom)){
              $user->setPrenom($prenom);
          }
          if(is_string($nom)){
              $user->setNom($nom);
          }
          if(is_string($email)){
              $user->setEmail($email);
          }
          if(is_string($adresse)){
              $user->setAdresse($adresse);
          }
          if(is_integer($cp)){
              $user->setCodePostal($cp);
          }
          if(is_string($ville)){
              $user->setVille($ville);
          }
          if(is_string($pays)){
              $user->setPays($pays);
          }

          $em->persist($user);
          $em->flush();

          return $this->redirectToRoute($route);
//          return $this->render('debug.html.twig', [
//              'debug' => $user->getId(),
//          ]);
      }
      else {
          $form = $this->createForm(UpdateUserType::class, $user);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
              $this->getDoctrine()->getManager()->flush();
              return $this->redirectToRoute('compte', ['id' => $userCurrent->getId()]);
          }
         return $this->redirectToRoute('compte', ['id'=>$userCurrent->getId()]);
      }
   }






    /**
    * @Route("/leaveReview/{id}", name="leaveReview")
    */
   public function leaveReview(Commande $commande, Request $request, EntityManagerInterface $em)
   {
      if ($request->request->get('stars')) {
         $note = new Note();
         $note->setRestaurant($commande->getRestaurant())
            ->setNote($request->request->get('stars'));

         $em->persist($note);
         $em->flush();

         return $this->redirectToRoute('accueil');
      }

      return $this->render('membre/leave-review.html.twig', [
         'accueil' => 'IndexController',
         'commande' => $commande,
      ]);
   }

   /**
    * @Route("/createOrder", name="createOrder")
    */
   public function createOrder(EntityManagerInterface $em, UserRepository $ur, PlatRepository $pr, StatusRepository $statusRepository, MailerInterface $mailer)
   {
       $commandeDetail = new CommandeDetail();
       $commande = new Commande();
       $plats = [];
       $quantites = [];
       $totalCommande = 0;

       //recuperation l'entity user grace au systeme de login
       $userSecu = $this->getUser();
       $userEmail= $userSecu->getUsername();
       $user =  $ur->findOneByEmail($userEmail);

        //recuperation de la liste des id des plats commandés et validé
       $listIdPlatAsc = $this->session->get("plats-".$user->getId());
        //transformation du tableau associatif en array simple
       foreach ($listIdPlatAsc as $item){
           $tempList[]=$item["id"];
       }
       //compte le nombre de meme plat.id ==> quantity, permet de gerer les quantité multiple de plats
       $listIdPlat = array_count_values($tempList);

        //mapping des quantite sur les detail commande
       foreach ($listIdPlat as $id => $quantity){

            $quantite = new Quantite();
            $plat = $pr->findOneBy(["id" => $id]);

            $quantite->setPlat($plat);
            $quantite->setNombre($quantity);
            $quantite->setCommandeDetail($commandeDetail);

            $plats[] = $plat;
            $quantites[] = $quantite;

            //calcul du prix de la commande
            $totalCommande += $plat->getPrix() * $quantity;
            $restaurant = $plat->getRestaurant();
            $commande->setRestaurant($restaurant);

            //persit en base chaque quantite
            $em->persist($quantite);

       }

        $totalCommande= $totalCommande + getenv('DELIVERY_PRICE');
        //creation en base des nouveaux element relatif a la comande
        $status = $statusRepository->findOneByState("En attente");
        $commande->setStatus($status);
        $commande->setMembre($user);

        $commande->setDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $commandeDetail->setCommande($commande);
        $commandeDetail->setPrix($totalCommande);

        $restaurateur = $restaurant->getRestaurateur();
        $restaurateurEmail =  $restaurateur->getEmail();
        $restaurantEmail = $restaurant->getEmail();
        $soldeMembre = $user->getSolde();
        $restaurateurSolde = $restaurateur->getSolde();
        $user->setSolde($soldeMembre-$totalCommande);
        $restaurateur->setSolde($restaurateurSolde+$totalCommande - getenv('DELIVERY_PRICE'));

        //persit en base
        $em->persist($commandeDetail);
        $em->persist($commande);
        $em->flush();

        $idcommande = $commande->getId();


       $email = (new TemplatedEmail())
           ->from('delivroomvroom@gmail.com')
           ->to($restaurantEmail)
           ->cc($restaurateurEmail)
           ->priority(Email::PRIORITY_HIGH)
           ->subject('Votre restaurant'.$restaurant->getNom().'à recu une commande')
           ->htmlTemplate('email/restaurateur-email.html.twig')
           ->context([
               'commande' => $commande,
               'commandeDetail' => $commandeDetail,
               'quantites' => $quantites,
               'membre' => $user,
               'heure_de_commande' => $commande->getDate()->format('H:i'),
               'heure_de_livraison_estimation' => $commande->getDate()->add(new DateInterval('PT1H'))->format('H:i'),
               'delivery_fee' => getenv('DELIVERY_PRICE'),
           ]);
       $mailer->send($email);
       $this->session->clear();

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
         $user->setSolde($user->getSolde() + $data["somme"]);
         $em->persist($user);
         $em->flush();
         return $this->redirectToRoute('accueil');
      } elseif ($request->isMethod('GET')) {
         return $this->render('membre/add-balance.html.twig', [
            'accueil' => 'IndexController',
            'form' => $form->createView()
         ]);
      }
   }
}
