<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Form\PlanteEntrerType;
use App\Repository\PlanteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesPlantesController extends AbstractController
{

    private $repository;

    public function __construct(PlanteRepository $planteRepository,  EntityManagerInterface $em)
    {
        $this->repository = $planteRepository;
        $this->em = $em;
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
        // image traitement et envoi
        // if (!empty($_FILES['photo']))
        // {
        //     $image_brut = $_FILES['photo'];
        //     $fichier_nom = md5(uniqid()) . '.' . substr(strrchr($image_brut['name'], '.'), 1);
        //     $fichier_dir = $this->getParameter('images_directory') . $fichier_nom;

        //     move_uploaded_file($image_brut['name'], $fichier_dir);
        //     $plante->setImage($fichier_nom);
            
        //     //$image_blob = addslashes(file_get_contents($image_brut["tmp_name"]));
        //     //$plante->setImage($image_brut["name"]);
        // }
        $this->repository->save($plante,true);
        
        return $this->redirect("/mesplantes");
    }

    #[Route('/mesplantes/suppr_plante', name: 'app_mesplantessuppr')]
    public function supprimeplante()
    {
        $plantes = $this->repository->findById($_POST['idplanteinput']);
        dump($_POST['idplanteinput']);

        if (isset($plantes[0])){
            $plante = $plantes[0];

            //supression de l'image
            $fichier_directory = $this->getParameter('images_directory');
            if (is_file($fichier_directory . '/' . $plante->getImage()) && $plante->getImage() !== null)
            {
                unlink($fichier_directory . '/' . $plante->getImage());
            }

            $this->repository->remove($plante,true);
        }
        
        return $this->redirect("/mesplantes");
    
    }

    #[Route('/mesplantes/edit_plante', name: 'app_mesplantesedit')]
    public function editplante(Request $request)
    {
        $plantes = $this->repository->findById($_POST['idplanteinput']);
        dump($_POST['idplanteinput']);
        if (isset($plantes[0])){
            $plante = $plantes[0];

            $form = $this->createForm(PlanteEntrerType::class, $plante);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $image = $form->get('image')->getData();
                if ($image !== null)
                {
                    $fichier_nom = md5( uniqid("image")) . '.' . $image->guessExtension();
                    $fichier_directory = $this->getParameter('images_directory');

                    //supression de l'ancienne image
                    if (is_file($fichier_directory . '/' . $plante->getImage()) && $plante->getImage() !== null)
                    {
                        unlink($fichier_directory . '/' . $plante->getImage());
                    }

                    $image->move( $fichier_directory, $fichier_nom);
                    $plante->setImage($fichier_nom);
                }
                $this->em->flush();
                return $this->redirect("/mesplantes");
            }

            return $this->render('MesPlantes/MesPlantesEdit.html.twig',[
                'planteselected' => $plante,
                'form' => $form->createView()
            ]);

            //$this->repository->save($plante,true);
        }
        
        return $this->redirect("/mesplantes");
    
    }
}
