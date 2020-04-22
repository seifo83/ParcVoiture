<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ModeleController extends AbstractController
{
    /**
     * @Route("/modele", name="modele")
     */
    public function index()
    {
        return $this->render('modele/index.html.twig', [
            'controller_name' => 'ModeleController',
        ]);
    }
}
