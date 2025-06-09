<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\UtilisateursType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UtilisateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/utilisateurs')]
final class UtilisateursController extends AbstractController
{
    #[Route(name: 'app_utilisateurs_index', methods: ['GET'])]
    public function index(UtilisateursRepository $utilisateursRepository): Response
    {
        return $this->render('utilisateurs/index.html.twig', [
            'utilisateurs' => $utilisateursRepository->findAll(),
        ]);
    }

    #[Route('/utilisateurs/new', name: 'app_utilisateurs_new', methods: ['POST'])]
        public function new(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
        {
            $utilisateur = new Utilisateurs();
            $utilisateur->setPrenom($request->request->get('prenom'));
            $utilisateur->setNom($request->request->get('nom'));
            $utilisateur->setNomUtilisateur($request->request->get('nom_utilisateur'));

            $motDePasse = $request->request->get('mot_de_passe');
            $utilisateur->setPassword($hasher->hashPassword($utilisateur, $motDePasse));

            $roles = $request->request->all('roles');
            $utilisateur->setRoles($roles);

            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('app_utilisateurs_index');
        }


    #[Route('/{id}', name: 'app_utilisateurs_show', methods: ['GET'])]
    public function show(Utilisateurs $utilisateur): Response
    {
        return $this->render('utilisateurs/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/utilisateurs/{id}/edit', name: 'app_utilisateurs_edit', methods: ['POST'])]
public function edit(Request $request, Utilisateurs $utilisateur, EntityManagerInterface $em): Response
{
    $utilisateur->setPrenom($request->request->get('prenom'));
    $utilisateur->setNom($request->request->get('nom'));
    $utilisateur->setNomUtilisateur($request->request->get('nom_utilisateur'));

    $roles = $request->request->all('roles');
    $utilisateur->setRoles($roles);

    $em->flush();

    return $this->redirectToRoute('app_utilisateurs_index');
}


    #[Route('/{id}', name: 'app_utilisateurs_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateurs $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateurs_index', [], Response::HTTP_SEE_OTHER);
    }
}
