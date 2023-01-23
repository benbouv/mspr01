<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Repository\DemandeRepository;
use App\Repository\PlanteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemandePageController extends AbstractController
{
    private $repository_user;
    private $repository;
    private $repository_demandes;
    private $security;
    


    public function __construct(
        Security $security,
        DemandeRepository $demandeRepository,
        PlanteRepository $planteRepository
        )
    {
        $this->security = $security;
        $this->repository = $planteRepository;
        $this->repository_demandes = $demandeRepository;
    }


    
    #[Route('/user/MesDemandes', name: 'app_mesdemandes')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if(!empty($user))
        {
            $userId = $user->getId();
            $demandesenvoyees = array_reverse( $this->repository_demandes->findByUserFaitId($userId));
            $demandesrecus = array_reverse($this->repository_demandes->findByUserRecoiId($userId));


            return $this->render('MonCompte/DemandePage.html.twig', [
                'demandesrecus' => $demandesrecus,
                'demandesenvoyers' => $demandesenvoyees
            ]);
        }
        return $this->redirectToRoute('app_register');
    }




    // DEMANDE VALIDATION FINAL \\

    #[Route('/user/MesDemandes/valide{id}', name: 'app_mesdemandes_valide')]
    public function validedemande(Demande $demande)
    {
        $user = $this->security->getUser();
        if($user===$demande->getUserFaitDemande() && $demande->getEtat()==="DEMANDE_ACCEPTÉE" && $demande->getPlanteContientDemande() !==null)
        {
            $demande->setEtat("DEMANDE_VALIDÉE");
            $this->repository_demandes->save($demande,true);

            $plante = $demande->getPlanteContientDemande();
            $plante->setUserKeepingPlant($demande->getUserRecoiDemande());
            $this->repository->save($plante,true);
        }
        else
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_mesdemandes');
    }



    // SET DEMANDE ACCEPTER \\

    #[Route('/user/MesDemandes/accept{id}', name: 'app_mesdemandes_accept')]
    public function acceptdemande(Demande $demande)
    {
        $user = $this->security->getUser();
        if($user===$demande->getUserRecoiDemande())
        {
            $demande->setEtat("DEMANDE_ACCEPTÉE");
            $this->repository_demandes->save($demande,true);
        }
        else
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_mesdemandes');
    }



    // SET DEMANDE REFUSER \\

    #[Route('/user/MesDemandes/refus{id}', name: 'app_mesdemandes_refus')]
    public function refusedemande(Demande $demande)
    {
        $user = $this->security->getUser();
        if($user===$demande->getUserRecoiDemande())
        {
            $demande->setEtat("DEMANDE_REFUSÉE");
            $this->repository_demandes->save($demande,true);
        }
        else
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_mesdemandes');
    }




    // SUPPR DEMANDE \\

    #[Route('/user/MesDemandes/suppr{id}', name: 'app_mesdemandes_suppr')]
    public function supprdemande(Demande $demande)
    {
        $user = $this->security->getUser();
        if($user===$demande->getUserFaitDemande())
        {
            $this->repository_demandes->remove($demande,true);
        }
        else
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_mesdemandes');
    }


    // REVOQUER DEMANDE \\

    #[Route('/user/MesDemandes/revoc{id}', name: 'app_mesdemandes_revoc')]
    public function revocdemande(Demande $demande)
    {
        $user = $this->security->getUser();
        if($user===$demande->getUserFaitDemande() && $demande->getEtat()==="DEMANDE_VALIDÉE")
        {
            $demande->setEtat("DEMANDE_RÉVOQUÉE");
            $this->repository_demandes->save($demande,true);

            $plante = $demande->getPlanteContientDemande();
            $plante->setUserKeepingPlant(null);
            $this->repository->save($plante,true);
        }
        else
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_mesdemandes');
    }
    
}
