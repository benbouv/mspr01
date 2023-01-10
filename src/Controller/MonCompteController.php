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

            return $this->render('MonCompte/MonCompte.html.twig', [

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
            if($_POST['infoEmail']!==null || $_POST['infoEmail']!==$user->getEmail())
            {
                $user->setEmail($_POST['infoEmail']);
            }
            if($_POST['infoNom']!==null || $_POST['infoNom']!==$user->getNom())
            {
                $user->setNom($_POST['infoNom']);
            }
            if($_POST['infoTel']!==null || $_POST['infoTel']!==$user->getTelephone())
            {
                $user->setTelephone($_POST['infoTel']);
            }
            if($_POST['infoPseudo']!==null || $_POST['infoPseudo']!==$user->getPseudo())
            {
                $user->setPseudo($_POST['infoPseudo']);
            }

            if ($_POST['infoPass']===$_POST['infoPassVerif'])
            {
                $user->setPassword(
                    $userPasswordHasherInterface->hashPassword($user, $_POST['infoPass'])
                );
            }


            $this->repository_user->save($user,true);

            return $this->redirectToRoute('app_moncompte');
        }
        return $this->redirectToRoute('app_register');
    }


    // SET GARDIEN OR NOT \\
    #[Route('/user/MonCompte/setgard', name: 'app_moncompte_setgard')]
    public function comptesetgard()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $roles = $user->getRoles();
        //Is gardien?
            if ($_POST['infoIsGardien']){
                foreach($roles as $role){
                    if($role==="ROLE_GARDIEN")
                    {
                        break;
                    }
                    if( !next( $roles ) ) 
                    {
                        array_push($roles,"ROLE_GARDIEN");
                        $user->setRoles($roles);
                        break; 
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
                        break; 
                    }
                }
            }
        //end

        $this->repository_user->save($user,true);

        return $this->redirectToRoute('app_moncompte');
    }

    // SET BOTANIST OR NOT \\
    #[Route('/user/MonCompte/setbota', name: 'app_moncompte_setbota')]
    public function comptesetbota()
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $roles = $user->getRoles();
        //Is botanist?
            if ($_POST['infoIsBotanist']){
                foreach($roles as $role){
                    if($role==="ROLE_BOTANIST")
                    {
                        break;
                    }
                    if( !next( $roles ) ) 
                    {
                        array_push($roles,"ROLE_BOTANIST");
                        $user->setRoles($roles);
                        break; 
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
                        break; 
                    }
                }
            }
        //end

        $this->repository_user->save($user,true);

        return $this->redirectToRoute('app_moncompte');
    }


}
