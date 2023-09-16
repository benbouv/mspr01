<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils,Security $security): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }



    // #[Route(path: '/jsonLogin', name: 'app_jsonLogin', methods: ['POST'])]
    // public function jsonLogin(#[CurrentUser] ?User $user, Request $request, Security $security, UserRepository $userRepository)
    // {
    //     if (null === $user) 
    //     {
    //         return $this->json([
    //             'message' => 'missing credentials 2 msprA',
    //         ], Response::HTTP_UNAUTHORIZED);
    //     }

    //     if ($user->getEmail()=="systema_LOGIN@fg.fg")
    //     {
    //         $rep = $request->getContent();          
    //         $data = json_decode($rep, true);
    //         $user = $userRepository->findOneByEmail($data["username"]);
    //         //$security->logout();
    //         if ($user) 
    //         {
    //             $security->login($user);
    //             return $this->json([
    //                 'token' => "FING&GGHYJ&485",
    //                 'message' => 'Welcome to your new controller!',
    //                 'username' => $user->getUserIdentifier(),
    //                 'roles' => $user->getRoles()
    //             ]);
    //         }
            
    //     }

    //     return $this->json([
    //         'message' => 'missing credentials 2 mspr01B',
    //     ], Response::HTTP_UNAUTHORIZED);
    // }

    // #[Route(path: '/api/login', name: 'api_login', methods: ['POST'])]
    // public function apiLogin()
    // {
    //     $user = $this->getUser();
    //     return $this->json([
    //         'username' => $user->getUserIdentifier(),
    //         'roles' => $user->getRoles()
    //     ]);
    // }

}
