<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Plante;
use App\Entity\User;
use App\Repository\DemandeRepository;
use App\Repository\PlanteRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    private $repository_user;
    private $repository;
    private $security;
    private $repository_demandes;


    public function __construct(
        UserRepository $userRepository,
        PlanteRepository $planteRepository,
        DemandeRepository $demandeRepository,
        Security $security
        )
    {
        $this->repository_user = $userRepository;
        $this->repository = $planteRepository;
        $this->repository_demandes = $demandeRepository;
        $this->security = $security;
    }


    
    #[Route('/user/profil{id}', name: 'app_profilpage')]
    public function index(User $userprofil): Response
    {

        /** @var User $user */
        $user = $this->security->getUser();

        if($this->UserIsAllowed($userprofil))
        {
            $userId = $user->getId();
            $plantes = $this->repository->findByUserId($userId);
            $plantes = array_reverse($plantes);

            return $this->render('MonCompte/ProfilPage.html.twig', [
                'userprofilselcted' => $userprofil,
                'mesplantes' => $plantes
            ]);
        }
        else
        {
            $this->addFlash('Profilnondispo','Profil non disponible.');
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_register');
    }




    // SET PLANTE TO PROFIL \\

    #[Route('/user/profil{id}/setplante{plante_id}', name: 'app_profilpagetraitement')]
    #[ParamConverter('plante', options: ['mapping' => ['plante_id' => 'id']])]
    public function TraitementGardienToPlante(User $userprofil, Plante $plante)
    {
        $user = $this->security->getUser();
        if($plante->getUserOwningPlant()===$user)
        {
            if($this->UserIsAllowed($userprofil))
            {

                $demande = new Demande;
                $demande->setPlanteContientDemande($plante);
                $demande->setUserFaitDemande($user);
                $demande->setUserRecoiDemande($userprofil);
                $demande->setDateDeCreation(new \DateTime());
                $demande->setEtat("EN_ATTENTE_DE_REPONSE");

                $this->repository_demandes->save($demande,true);



                $this->addFlash('ProfilSuccess','Le profil a reçu votre proposition.');
                return $this->redirectToRoute('app_home');
            }
            else
            {
                $this->addFlash('Profilnondispo','Profil non disponible.');
                return $this->redirectToRoute('app_home');
            }
        }
        else{
            return $this->redirectToRoute('app_home');
        }
    }






    //user is allowed if userprofil is gardien 
    private function UserIsAllowed($userprofil): bool
    {
        $roles = $userprofil->getRoles();

        //Is gardien?
        foreach($roles as $role){
            if($role==="ROLE_GARDIEN")
            {
                return true;
                break;
            }
        }

        return false;
    }
}
