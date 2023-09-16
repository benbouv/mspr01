<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL))
            {
                $this->addFlash('EmailNonValide','Email non valide.');
                return $this->redirectToRoute('app_register');
            }


            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/registerJson', name: 'app_registerJson')]
    public function registerJson(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, UserRepository $userRepository): Response
    {
        $userNew = new User;
        $rep = $request->getContent();          
        $data = json_decode($rep, true);
        $userNew->setEmail($data["username"]);
        $userNew->setPassword(
            $userPasswordHasherInterface->hashPassword(
                $userNew,
                md5( uniqid("mdp"))
            ));

        $userRepository->save($userNew,true);

        return $this->json([
            'token' => "FING&GGHYJ&759",
            'message' => 'User added',
            'username' => $userNew->getUserIdentifier(),
            'roles' => $userNew->getRoles()
        ]);


    }
}
