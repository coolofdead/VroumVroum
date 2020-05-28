<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserResgisterType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoderInterface)
    {
        $this->passwordEncoder = $userPasswordEncoderInterface;
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('accueil');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserRepository $ur)
    {
        $user = new User();
        $form = $this->createForm(UserResgisterType::class, $user);
        $form->handleRequest($request);



        if ($form->isSubmitted()) {

            $email = $form->get('email')->getViewData();

            if(count($ur->findBy(['email' => $email])) === 0){
                $entityManager = $this->getDoctrine()->getManager();
                $user->setPassword($this->passwordEncoder->encodePassword($user,$user->getPassword()));
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_login');
            }
            else{
                return $this->render('security/register.html.twig', [
                    'roles' => ['Membre', 'Restaurateur'],
                    'form' => $form->createView(),
                    'emailError' => false
                ]);

            }



        }

             return $this->render('security/register.html.twig', [
            'roles' => ['Membre', 'Restaurateur'],
            'form' => $form->createView(),
            'emailError' => false
        ]);
    }



    /**
     * Redirige le user en fonction de son role
     * @Route("/login_redirect", name="_login_redirect")
     */
    public function loginRedirectAction(Request $request)
    {
        if($this->isGranted('ROLE_ADMIN'))
        {
            // TODO changer la route par défaut pour les admin
            return $this->redirectToRoute('accueil');
        }
        else if($this->isGranted('ROLE_RESTAURATEUR'))
        {
            return $this->redirectToRoute('accueil');
        }
        else
        {
            return $this->redirectToRoute('accueil');
        }
    }

}
