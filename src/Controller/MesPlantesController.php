<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesPlantesController extends AbstractController
{
    #[Route('/mesplantes', name: 'app_mesplantes')]
    public function index(): Response
    {
        return $this->render('MesPlantes/MesPlantes.html.twig', [
            'controller_name' => 'MesPlantesController'
        ]);
    }
}
