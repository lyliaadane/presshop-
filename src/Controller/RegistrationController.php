<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private UsersAuthenticator $usersAuthenticator;
    private UserAuthenticatorInterface $userAuthenticator;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UsersAuthenticator $usersAuthenticator,
        UserAuthenticatorInterface $userAuthenticator
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->usersAuthenticator = $usersAuthenticator;
        $this->userAuthenticator = $userAuthenticator;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateurs();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_ADMIN']);

            $plainPassword = $form->get('plainPassword')->getData();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->userAuthenticator->authenticateUser(
                $user,
                $this->usersAuthenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
