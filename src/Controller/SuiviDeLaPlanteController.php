<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Photo;
use App\Entity\Plante;
use App\Form\PhotoentrerType;
use App\Repository\MessageRepository;
use App\Repository\PhotoRepository;
use App\Repository\PlanteRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuiviDeLaPlanteController extends AbstractController
{

    private $repository;

    public function __construct(PlanteRepository $planteRepository, 
        PhotoRepository $photoRepository, 
        MessageRepository $messageRepository,
        EntityManagerInterface $em,
        Security $security
        )
    {
        $this->repository = $planteRepository;
        $this->repository_photo = $photoRepository;
        $this->repository_messages = $messageRepository;
        $this->em = $em;
        $this->security = $security;
    }



    #[Route('/user/mesplantes/SuiviDeLaPlante{id}', name: 'app_suiviplante')]
    public function index(Plante $plante): Response
    {
        $user = $this->security->getUser();
        if(!empty($user))
        {
            $photos = $this->repository_photo->findByPlanteId($plante->getId());
            $photos = array_reverse($photos);

            //$userId = $user->getId();
            // $us_pr_messages = $this->repository_messages->findByUserPrId($userId);
            // $us_other_messages = $this->repository_messages->findByUserOtherId($userId);
            // $us_botanist_messages = $this->repository_messages->findByUserBotanistId($userId);
            // $messages = array_merge($us_pr_messages,$us_other_messages,$us_botanist_messages);

            $messages = $this->repository_messages->findByAllByIdPlante($plante->getId());

            return $this->render('MesPlantes/MaPlante.html.twig', [
                'photosdelaplante' => $photos,
                'planteselected' => $plante,
                'messagess' => $messages
            ]);
        }
        return $this->redirectToRoute('app_register');
    }


    // ADD MESSAGE \\

    #[Route('/user/mesplantes/SuiviDeLaPlante{id}/addmes', name: 'app_suiviplante_addmessage')]
    public function formaddmessage(Plante $plante)
    {
        $message = new Message;
        $message->setContenu($_POST['infomessage']);
        $message->setDate(new \DateTime());
        $message->setUserWritingMessage($this->security->getUser());
        $message->setPlantInformedByMessage($plante);

        $this->repository_messages->save($message,true);
        
        return $this->redirectToRoute('app_suiviplante', [
            'id' => $plante->getId()
        ]);
    }



    // ADD PHOTO \\

    #[Route('/user/mesplantes/SuiviDeLaPlante{id}/addphoto', name: 'app_suiviplanteaddphoto')]
    public function formaddphot(Plante $plante, Request $request)
    {
        $photo = new Photo;
        $form = $this->createForm(PhotoentrerType::class, $photo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $image = $form->get('name')->getData();
            if ($image !== null)
            {
                $fichier_nom = md5( uniqid("image")) . '.' . $image->guessExtension();
                $fichier_directory = $this->getParameter('images_directory');

                //supression de l'ancienne image
                if (is_file($fichier_directory . '/' . $photo->getName()) && $photo->getName() !== null)
                {
                    unlink($fichier_directory . '/' . $photo->getName());
                }

                $image->move( $fichier_directory, $fichier_nom);
                $photo->setName($fichier_nom);
                $photo->setDate(new \DateTime());
                $photo->setPlantePossedePhoto($plante);

                //set l'image de la carte de la plante, si pas d'image
                if($plante->getImage()===null)
                {
                    $plante->setImage($fichier_nom);
                }

                $this->repository_photo->save($photo,true);
            }
            return $this->redirectToRoute('app_suiviplante', [
                'id' => $plante->getId()
            ]);
        }

        return $this->render('MesPlantes/MaPlanteAddPhoto.html.twig',[
            'form' => $form->createView()
        ]);
    
    }
}
