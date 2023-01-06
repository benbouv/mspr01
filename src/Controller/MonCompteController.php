<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Form\PlanteEntrerType;
use App\Repository\PhotoRepository;
use App\Repository\MessageRepository;
use App\Repository\PlanteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonCompteController extends AbstractController
{

    // private
        private $repository;
        private $repository_photo;
        private $repository_messages;
        private $em;
        private $security;
    // end

    public function __construct(PlanteRepository $planteRepository,  
        EntityManagerInterface $em, 
        Security $security,
        MessageRepository $messageRepository,
        PhotoRepository $photoRepository
        )
    {
        $this->repository = $planteRepository;
        $this->repository_photo = $photoRepository;
        $this->repository_messages = $messageRepository;
        $this->em = $em;
        $this->security = $security;
    }




    #[Route('/user/MonCompte', name: 'app_moncompte')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if(!empty($user))
        {
            $userId = $user->getId();

            return $this->render('MonCompte/MonCompte.html.twig', [

            ]);
        }
        return $this->redirectToRoute('app_register');
    }


    // EDIT COMPTE \\

    // #[Route('/user/mesplantes/edit_plante', name: 'app_mesplantesedit')]
    // public function editplante(Request $request)
    // {
    //     $plantes = $this->repository->findById($_POST['idplanteinput']);
    //     dump($_POST['idplanteinput']);
    //     if (isset($plantes[0])){
    //         $plante = $plantes[0];
            
    //         //traitement du choice familly
    //         $choices = $plante::FAMILLELIST;
    //         foreach($choices as $k => $v)
    //         {
    //             if($v === $plante->getFamille())
    //             {
    //                 $plante->setFamille($k);
    //                 break 1;
    //             }
    //         }

    //         $form = $this->createForm(PlanteEntrerType::class, $plante);
    //         $form->handleRequest($request);

    //         if($form->isSubmitted() && $form->isValid())
    //         {
    //             $user = $this->security->getUser();
    //             if($plante->getUserOwningPlant()===$user)
    //             {
    //                 $image = $form->get('image')->getData();
    //                 if ($image !== null)
    //                 {
    //                     $fichier_nom = md5( uniqid("image")) . '.' . $image->guessExtension();
    //                     $fichier_directory = $this->getParameter('images_directory');
    
    //                     //supression de l'ancienne image
    //                     if (is_file($fichier_directory . '/' . $plante->getImage()) && $plante->getImage() !== null)
    //                     {
    //                         unlink($fichier_directory . '/' . $plante->getImage());
    //                     }
    
    //                     $image->move( $fichier_directory, $fichier_nom);
    //                     $plante->setImage($fichier_nom);
    //                 }
    
    //                 $plante->setFamille($plante::FAMILLELIST[$plante->getFamille()]);
    //                 $this->em->flush();
    //             }
    //             else
    //             {
    //                 return $this->redirectToRoute('app_home');
    //             }

    //             return $this->redirectToRoute('app_mesplantes');
    //         }

    //         return $this->render('MesPlantes/MesPlantesEdit.html.twig',[
    //             'planteselected' => $plante,
    //             'form' => $form->createView()
    //         ]);

    //         //$this->repository->save($plante,true);
    //     }
        
    //     return $this->redirectToRoute('app_mesplantes');
    
    // }
}
