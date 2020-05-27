<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
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
    public function register()
    {
        return $this->render('security/register.html.twig', [
            'roles' => ['Membre', 'Restaurateur']
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
