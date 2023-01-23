<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $repository_user;


    public function __construct(
        UserRepository $userRepository
        )
    {
        $this->repository_user = $userRepository;
    }


    
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {


        /*
        le code est fonctionnel, le principe est de récuperer 2 champs de la table user qu'il faut créer :
        
        - lat 
        - lng
        
        il faut mettre le type de champ en decimal et la valeur à 10,6
        
        l'email est aussi récupéré pour donner le nom du waypoints
        */

        //$mysqli=mysqli_connect('localhost','root','','arosaje');
        //$sql=mysqli_query($mysqli,"SELECT *, (6371 * ACOS(COS(RADIANS(lat)) * COS(RADIANS(".$_GET["geolat"].")) * COS(RADIANS(lng) - RADIANS(".$_GET["geolng"].")) + SIN(RADIANS(lat)) * SIN(RADIANS(".$_GET["geolat"].")))) AS distance FROM user HAVING distance < 70 ORDER BY distance");

        //$users = $this->repository_user->findByAllBydistance(10,6);
        $users = $this->repository_user->findAll();

        $users_gardien =[];
        foreach($users as $user_i){
            foreach($user_i->getRoles() as $role){
                if($role==="ROLE_GARDIEN")
                {
                    array_push($users_gardien,$user_i);
                    break 1;
                }
            }
        }

        return $this->render('home/index.html.twig', [
            'users' => $users_gardien
        ]);
    }
}
