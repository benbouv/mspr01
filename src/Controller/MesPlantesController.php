<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Repository\PlanteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesPlantesController extends AbstractController
{

    private $repository;

    public function __construct(PlanteRepository $planteRepository)
    {
        $this->repository = $planteRepository;
    }




    #[Route('/mesplantes', name: 'app_mesplantes')]
    public function index(): Response
    {
        $plantes = $this->repository->findAll();
        // $plante = new Plante;
        // $plante->setNom("rose");
        // $this->repository->save($plante,false);
        //dump($plantes);
        // $this->repository->remove($plante,true);
        
        return $this->render('MesPlantes/MesPlantes.html.twig', [
            'controller_name' => 'MesPlantesController',
            'mesplantes' => $plantes
        ]);
    }

    #[Route('/mesplantes/form', name: 'app_mesplantesform')]
    public function formaddplante()
    {
        $plante = new Plante;
        $plante->setNom($_POST['nom']);
        $plante->setFamille($_POST['type']);
        $plante->setPeriodeArrosage($_POST['description']);
        $this->repository->save($plante,true);
        
        return $this->redirect("/mesplantes");
    
    }

    #[Route('/mesplantes/suppr_plante', name: 'app_mesplantessuppr')]
    public function supprimeplante()
    {
        $plantes = $this->repository->findById($_POST['idplanteinput']);
        dump($_POST['idplanteinput']);
        $plante = $plantes[0];
        
        $this->repository->remove($plante,true);
        
        return $this->redirect("/mesplantes");
    
    }
}
