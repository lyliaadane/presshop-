<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'category_index', methods: ['GET'])]
    public function index(CategoriesRepository $categoryRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'categorie_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $nom = $request->request->get('nom');
        $description = $request->request->get('description');

        if ($nom) {
            $categorie = new Categories();
            $categorie->setNom($nom);
            $categorie->setDescription($description);
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Catégorie ajoutée avec succès.');
        }

        return $this->redirectToRoute('category_index');
    }


    #[Route('/{id}', name: 'categorie_show', methods: ['GET'])]
    public function show(Categories $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'categorie_edit', methods: ['POST'])]
    public function edit(Request $request, Categories $categorie, EntityManagerInterface $em): Response
    {
        $categorie->setNom($request->request->get('nom'));
        $categorie->setDescription($request->request->get('description'));

        $em->flush();

        return $this->redirectToRoute('category_index');
    }


    #[Route('/{id}', name: 'categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categories $categorie, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('_token'))) {
            $em->remove($categorie);
            $em->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }
}
