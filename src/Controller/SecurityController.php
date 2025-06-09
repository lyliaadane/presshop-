<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UtilisateursRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function reset(Request $request, string $token, UtilisateursRepository $repo, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager): Response
    {
        $user = $repo->findOneBy(['resetToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Lien invalide ou expiré.');
        }

        if ($request->isMethod('POST')) {
            $newPassword = $request->request->get('password');
            $user->setPassword($hasher->hashPassword($user, $newPassword));
            $user->setResetToken(null);
            //$repo->save($user, true);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe mis à jour avec succès.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'token' => $token,
        ]);
    }

    /*#[Route('/generate-reset-token', name: 'app_generate_reset_token')]
    public function generateResetToken(UtilisateursRepository $repo, UserInterface $user): Response
    {
        $token = bin2hex(random_bytes(32));
        $user->setResetToken($token);
        $repo->save($user, true);

        return $this->redirectToRoute('app_reset_password', ['token' => $token]);
    }*/

    #[Route('/demande-reset', name: 'app_request_reset')]
    public function requestReset(Request $request, UtilisateursRepository $repo, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('nom_utilisateur');
            $user = $repo->findOneBy(['nom_utilisateur' => $username]);

            if (!$user) {
                $this->addFlash('error', 'Utilisateur introuvable.');
                return $this->redirectToRoute('app_request_reset');
            }

            $token = bin2hex(random_bytes(32));
            $user->setResetToken($token);
            //$repo->save($user, true);

           // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('app_reset_password', ['token' => $token]);
        }

        return $this->render('security/request_reset.html.twig');
    }

}
