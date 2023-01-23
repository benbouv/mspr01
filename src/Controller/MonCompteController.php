<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Entity\User;
use App\Form\PlanteEntrerType;
use App\Repository\DemandeRepository;
use App\Repository\PhotoRepository;
use App\Repository\MessageRepository;
use App\Repository\PlanteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class MonCompteController extends AbstractController
{

    // private
        private $repository;
        private $repository_photo;
        private $repository_messages;
        private $em;
        private $security;
        private $repository_user;
        private $repository_demandes;
    // end

    public function __construct(PlanteRepository $planteRepository,  
        EntityManagerInterface $em, 
        Security $security,
        MessageRepository $messageRepository,
        PhotoRepository $photoRepository,
        DemandeRepository $demandeRepository,
        UserRepository $userRepository
        )
    {
        $this->repository = $planteRepository;
        $this->repository_photo = $photoRepository;
        $this->repository_messages = $messageRepository;
        $this->em = $em;
        $this->security = $security;
        $this->repository_user = $userRepository;
        $this->repository_demandes = $demandeRepository;
    }




    #[Route('/user/MonCompte', name: 'app_moncompte')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if(!empty($user))
        {
            $userId = $user->getId();

            $roles = $user->getRoles();

            //Is gardien?
            $isgardien = false;
            foreach($roles as $role){
                if($role==="ROLE_GARDIEN")
                {
                    $isgardien = true;
                    break;
                }
            }

            //Is botanist?
            $isbotanist = false;
            foreach($roles as $role){
                if($role==="ROLE_BOTANIST")
                {
                    $isbotanist = true;
                    break;
                }
            }

            return $this->render('MonCompte/MonCompte.html.twig', [
                'IsGardien' => $isgardien,
                'IsBotanist' => $isbotanist

            ]);
        }
        return $this->redirectToRoute('app_register');
    }


    // EDIT COMPTE \\

    #[Route('/user/MonCompte/traitement', name: 'app_moncompte_traitement')]
    public function editcompte(UserPasswordHasherInterface $userPasswordHasherInterface)
    {

        /** @var User $user */
        $user = $this->security->getUser();
        if(!empty($user))
        {
            if($_POST['infoEmail']!==null && $_POST['infoEmail']!=="" && $_POST['infoEmail']!==$user->getEmail())
            {
                if(filter_var($_POST['infoEmail'], FILTER_VALIDATE_EMAIL))
                {
                    $user->setEmail($_POST['infoEmail']);
                }
                else
                {
                    $this->addFlash('EmailNonValide','Email non valide.');
                }
            }
            if($_POST['infoNom']!==null && $_POST['infoNom']!=="" && $_POST['infoNom']!==$user->getNom())
            {
                $user->setNom($_POST['infoNom']);
            }
            if($_POST['infoTel']!==null && $_POST['infoTel']!=="" && $_POST['infoTel']!==$user->getTelephone())
            {
                if (preg_match('/^[0-9]{10}+$/', $_POST['infoTel'])) 
                {
                    $user->setTelephone($_POST['infoTel']);
                }
                else
                {
                    $this->addFlash('TelNonValide','n° de téléphone non valide.');
                }
            }
            if($_POST['infoPseudo']!==null && $_POST['infoPseudo']!=="" && $_POST['infoPseudo']!==$user->getPseudo())
            {
                $user->setPseudo($_POST['infoPseudo']);
            }

            if($_POST['infoPass']!==null && $_POST['infoPass']!=="")
            {
                if($_POST['infoPassVerif']!==null && $_POST['infoPassVerif']!=="")
                {
                    if ($_POST['infoPass']===$_POST['infoPassVerif'])
                    {
                        $user->setPassword(
                            $userPasswordHasherInterface->hashPassword($user, $_POST['infoPass'])
                        );
                    }
                }
            }


            $this->repository_user->save($user,true);

            return $this->redirectToRoute('app_moncompte');
        }
        return $this->redirectToRoute('app_register');
    }


    // SET OPTIONS \\
    #[Route('/user/MonCompte/setoptions', name: 'app_moncompte_setoptions')]
    public function comptesetoptions()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $roles = $user->getRoles();

        //Is gardien?
            if (isset($_POST['setIsGardien'])){
                foreach($roles as $role){
                    if($role==="ROLE_GARDIEN")
                    {
                        break 1;
                    }
                    if( !next( $roles ) ) 
                    {
                        array_push($roles,"ROLE_GARDIEN");
                        $user->setRoles($roles);
                        break 1; 
                    }
                }
            }
            else
            {
                foreach($roles as $key => $role){
                    if($role==="ROLE_GARDIEN")
                    {
                        unset($roles[$key]);
                        $user->setRoles($roles);
                        break 1; 
                    }
                }
            }
        //end

        //Is botanist?
            if (isset($_POST['setIsBotanist'])){
                foreach($roles as $role){
                    if($role==="ROLE_BOTANIST")
                    {
                        break 1;
                    }
                    if( !next( $roles ) ) 
                    {
                        array_push($roles,"ROLE_BOTANIST");
                        $user->setRoles($roles);
                        break 1; 
                    }
                }
            }
            else
            {
                foreach($roles as $key => $role){
                    if($role==="ROLE_BOTANIST")
                    {
                        unset($roles[$key]);
                        $user->setRoles($roles);
                        break 1; 
                    }
                }
            }
        //end

        $this->repository_user->save($user,true);

        return $this->redirectToRoute('app_moncompte');
    }


    // SUPPR COMPTE \\

    #[Route('/user/MonCompte/suppresion', name: 'app_moncompte_suppr')]
    public function supprcompte(UserPasswordHasherInterface $userPasswordHasherInterface)
    {

        /** @var User $user */
        $user = $this->security->getUser();
        if(!empty($user))
        {

            //suppresion des informations users
            $user->setEmail(md5( uniqid("em"))."@nn.nn");
            $user->setNom(null);
            $user->setTelephone(null);
            $user->setPseudo("NoName");
            $user->setPassword(
                $userPasswordHasherInterface->hashPassword($user, md5( uniqid("pw")))
            );
            $user->setLat(null);
            $user->setLng(null);
            $user->setRoles([]);

            //suppresion des plantes
            $userId = $user->getId();
            $plantes = $this->repository->findByUserId($userId);

            foreach($plantes as $plante)
            {
                //supresion des demandes
                $demandes = $this->repository_demandes->findByPlanteId($plante->getId());
                foreach($demandes as $demande)
                {
                    $this->repository_demandes->remove($demande,true);
                }

                //supression de l'image
                $fichier_directory = $this->getParameter('images_directory');
                if (is_file($fichier_directory . '/' . $plante->getImage()) && $plante->getImage() !== null)
                {
                    unlink($fichier_directory . '/' . $plante->getImage());
                }
                
                //suppression de toutes les photos
                $photos = $this->repository_photo->findByPlanteId($plante->getId());
                foreach($photos as $photo)
                {
                    if (is_file($fichier_directory . '/' . $photo->getName()) && $photo->getName() !== null)
                    {
                        unlink($fichier_directory . '/' . $photo->getName());
                    }
                    $this->repository_photo->remove($photo,true);
                }

                //suppression de tous les messages
                $messages = $this->repository_messages->findByAllByIdPlante($plante->getId());
                foreach($messages as $message)
                {
                    $this->repository_messages->remove($message,true);
                }

                $this->repository->remove($plante,true);
            }
            

            $this->repository_user->save($user,true);

            return $this->redirectToRoute('app_moncompte');
        }
        return $this->redirectToRoute('app_register');
    }

}
