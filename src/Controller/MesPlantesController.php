<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Form\PlanteEntrerType;
use App\Repository\PlanteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesPlantesController extends AbstractController
{

    private $repository;

    public function __construct(PlanteRepository $planteRepository,  EntityManagerInterface $em, Security $security)
    {
        $this->repository = $planteRepository;
        $this->em = $em;
        $this->security = $security;
    }




    #[Route('/user/mesplantes', name: 'app_mesplantes')]
    public function index(): Response
    {
        $user = $this->security->getUser();
        if(!empty($user))
        {
            $userId = $user->getId();
            $plantes = $this->repository->findByUserId($userId);
            $plantes = array_reverse($plantes);
            return $this->render('MesPlantes/MesPlantes.html.twig', [
                'controller_name' => 'MesPlantesController',
                'mesplantes' => $plantes
            ]);
        }
        return $this->redirectToRoute('app_register');
    }



    

    // ADD PLANTE \\

    #[Route('/user/mesplantes/form', name: 'app_mesplantesform')]
    public function formaddplante()
    {
        $plante = new Plante;
        $plante->setNom($_POST['nom']);
        $plante->setFamille($_POST['type']);
        $plante->setPeriodeArrosage($_POST['description']);
        $plante->setUserOwningPlant($this->security->getUser());

        $this->repository->save($plante,true);
        
        return $this->redirectToRoute('app_mesplantes');
    }

    // SUPPR PLANTE \\

    #[Route('/user/mesplantes/suppr_plante', name: 'app_mesplantessuppr')]
    public function supprimeplante()
    {
        $plantes = $this->repository->findById($_POST['idplanteinput']);
        //dump($_POST['idplanteinput']);
        if (isset($plantes[0])){
            $plante = $plantes[0];

            $user = $this->security->getUser();
            if($plante->getUserOwningPlant()===$user)
            {
                //supression de l'image
                $fichier_directory = $this->getParameter('images_directory');
                if (is_file($fichier_directory . '/' . $plante->getImage()) && $plante->getImage() !== null)
                {
                    unlink($fichier_directory . '/' . $plante->getImage());
                }
                $this->repository->remove($plante,true);
            }
            else
            {
                return $this->redirectToRoute('app_home');
            }

        }
        return $this->redirectToRoute('app_mesplantes');
    }

    // EDIT PLANTE \\

    #[Route('/user/mesplantes/edit_plante', name: 'app_mesplantesedit')]
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
                $user = $this->security->getUser();
                if($plante->getUserOwningPlant()===$user)
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
    
                    $plante->setFamille($plante::FAMILLELIST[$plante->getFamille()]);
                    $this->em->flush();
                }
                else
                {
                    return $this->redirectToRoute('app_home');
                }

                return $this->redirectToRoute('app_mesplantes');
            }

            return $this->render('MesPlantes/MesPlantesEdit.html.twig',[
                'planteselected' => $plante,
                'form' => $form->createView()
            ]);

            //$this->repository->save($plante,true);
        }
        
        return $this->redirectToRoute('app_mesplantes');
    
    }
}
