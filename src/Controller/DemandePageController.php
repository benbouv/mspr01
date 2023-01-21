<?php

namespace App\Controller;

use App\Repository\DemandeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemandePageController extends AbstractController
{
    private $repository_user;
    private $repository_demandes;
    private $security;
    


    public function __construct(
        Security $security,
        DemandeRepository $demandeRepository
        )
    {
        $this->security = $security;
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
}
