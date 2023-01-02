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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
        if($this->UserIsPlanteAllowed($user,$plante))
        {
            $photos = $this->repository_photo->findByPlanteId($plante->getId());
            $photos = array_reverse($photos);

            $messages = $this->repository_messages->findByAllByIdPlante($plante->getId());

            return $this->render('MesPlantes/MaPlante.html.twig', [
                'photosdelaplante' => $photos,
                'planteselected' => $plante,
                'messagess' => $messages
            ]);
        }
        else
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_register');
    }




    // ADD MESSAGE \\

    #[Route('/user/mesplantes/SuiviDeLaPlante{id}/addmes', name: 'app_suiviplante_addmessage')]
    public function formaddmessage(Plante $plante)
    {
        $user = $this->security->getUser();
        if($this->UserIsPlanteAllowed($user,$plante))
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
        else
        {
            $this->addFlash('AddMessageNonPermis','Non autorisé. Seul les utilisateurs responsables de la plante peuvent ajouter un message.');
        }
        return $this->redirectToRoute('app_suiviplante', [
            'id' => $plante->getId()
        ]);
    }

    // SUPPR MESSAGE \\

    #[Route('/user/mesplantes/SuiviDeLaPlante{id}/supprmessage{message_id}', name: 'app_suiviplante_supprmessage')]
    #[ParamConverter('message', options: ['mapping' => ['message_id' => 'id']])]
    public function supprmessage(Plante $plante, Message $message)
    {
        $user = $this->security->getUser();
        if($this->UserIsPlanteAllowed($user,$plante))
        {
            if($message->getUserWritingMessage()===$user)
            {
                $this->repository_messages->remove($message,true);
            }
            else
            {
                $this->addFlash('AddMessageNonPermis','Non autorisé. Seul l\'utilisateur responsable du message peut le supprimer à partir de la page.');
            }
        }
        else
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_suiviplante', [
            'id' => $plante->getId()
        ]);
    }

    // EDIT MESSAGE \\

    #[Route('/user/mesplantes/SuiviDeLaPlante{id}/editmessage{message_id}', name: 'app_suiviplante_editmessage')]
    #[ParamConverter('message', options: ['mapping' => ['message_id' => 'id']])]
    public function editmessage(Plante $plante, Message $message)
    {
        $user = $this->security->getUser();
        if($this->UserIsPlanteAllowed($user,$plante))
        {
            if($message->getUserWritingMessage()===$user)
            {
                $message->setContenu($_POST['infomessage']);
                $this->repository_messages->save($message,true);

            }
            else
            {
                $this->addFlash('AddMessageNonPermis','Non autorisé. Seul l\'utilisateur responsable du message peut le modifier à partir de la page.');
            }
        }
        else
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_suiviplante', [
            'id' => $plante->getId()
        ]);
    }




    // ADD PHOTO \\

    #[Route('/user/mesplantes/SuiviDeLaPlante{id}/addphoto', name: 'app_suiviplanteaddphoto')]
    public function formaddphot(Plante $plante, Request $request)
    {
        $user = $this->security->getUser();
        if($this->UserIsPlanteAllowed($user,$plante))
        {
            if($plante->getUserOwningPlant()===$user || $plante->getUserKeepingPlant()===$user)
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
                        //ajout de la nouvelle photo
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
            else
            {
                $this->addFlash('AddPhotoNonPermise','Non autorisé. Seul les utilisateurs responsables de la plante peuvent ajouter une photo.');
            }
        }
        else
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_suiviplante', [
            'id' => $plante->getId()
        ]);
    
    }

    // SUPPR PHOTO \\

    #[Route('/user/mesplantes/SuiviDeLaPlante{id}/supprphoto{photo_id}', name: 'app_suiviplante_supprphoto')]
    #[ParamConverter('photo', options: ['mapping' => ['photo_id' => 'id']])]
    public function supprphoto(Plante $plante, Photo $photo)
    {
        $user = $this->security->getUser();
        if($this->UserIsPlanteAllowed($user,$plante))
        {
            if($plante->getUserOwningPlant()===$user || $plante->getUserKeepingPlant()===$user)
            {
                $fichier_directory = $this->getParameter('images_directory');
                if (is_file($fichier_directory . '/' . $photo->getName()) && $photo->getName() !== null)
                {
                    unlink($fichier_directory . '/' . $photo->getName());
                }
                $this->repository_photo->remove($photo,true);
            }
            else
            {
                $this->addFlash('AddPhotoNonPermise','Non autorisé. Seul les utilisateurs responsables de la plante peuvent supprimer une photo.');
            }
        }
        else
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_suiviplante', [
            'id' => $plante->getId()
        ]);
    }


    //user is allowed if gardien ou proprio de la plante
    private function UserIsPlanteAllowed($user,$plante): bool
    {
        if($plante->getUserKeepingPlant()===$user || $plante->getUserOwningPlant()===$user)
        {
            return true;
        }

        //botaniste?
        $a = $user->getRoles();
        foreach ($a as $v) {
            if($v==="ROLE_BOTANIST")
            {
                return true;
            }
        }

        return false;
    }

}
