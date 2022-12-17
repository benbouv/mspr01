<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Repository\PlanteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuiviDeLaPlanteController extends AbstractController
{

    private $repository;

    public function __construct(PlanteRepository $planteRepository)
    {
        $this->repository = $planteRepository;
    }




    #[Route('/SuiviDeLaPlante', name: 'app_suiviplante')]
    public function index(): Response
    {
        $plantes = $this->repository->findById($_POST['planteselectinput']);
        dump($_POST['planteselectinput']);
        $plante = $plantes[0];
        
        return $this->render('MesPlantes/MaPlante.html.twig', [
            'controller_name' => 'SuiviDeLaPlanteController',
            'planteselected' => $plante
        ]);
    }

}
