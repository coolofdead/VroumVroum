<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/test", name="accueil_admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'admin' => 'AdminController',
        ]);
    }
}
