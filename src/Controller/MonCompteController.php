<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Entity\User;
use App\Form\PlanteEntrerType;
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
    // end

    public function __construct(PlanteRepository $planteRepository,  
        EntityManagerInterface $em, 
        Security $security,
        MessageRepository $messageRepository,
        PhotoRepository $photoRepository,
        UserRepository $userRepository
        )
    {
        $this->repository = $planteRepository;
        $this->repository_photo = $photoRepository;
        $this->repository_messages = $messageRepository;
        $this->em = $em;
        $this->security = $security;
        $this->repository_user = $userRepository;
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
                $user->setEmail($_POST['infoEmail']);
            }
            if($_POST['infoNom']!==null && $_POST['infoNom']!=="" && $_POST['infoNom']!==$user->getNom())
            {
                $user->setNom($_POST['infoNom']);
            }
            if($_POST['infoTel']!==null && $_POST['infoTel']!=="" && $_POST['infoTel']!==$user->getTelephone())
            {
                $user->setTelephone($_POST['infoTel']);
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



}
