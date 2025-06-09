<?php

namespace App\Controller;

use App\Entity\Promos;
use App\Entity\Produits;
use App\Entity\Categories;
use App\Form\PromosType;
use App\Form\RechercheType;
use App\Repository\PromosRepository;
use App\Repository\ProduitsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/promo')]
final class PromosController extends AbstractController
{
    // Affichage promos côté client, avec recherche et pagination par catégorie
    #[Route(name: 'app_promo_index', methods: ['GET', 'POST'])]
    public function index(
        PromosRepository $promoRepo,
        CategoriesRepository $categoriesRepo,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $categories = $categoriesRepo->findAll();

        $searchForm = $this->createForm(RechercheType::class);
        $searchForm->handleRequest($request);

        $promos = null;
        $promosParCategorie = [];

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $query = $searchForm->get('query')->getData();
            if ($query) {
                $queryBuilder = $promoRepo->createQueryBuilder('promo')
                    ->leftJoin('promo.id_produit', 'produit')
                    ->addSelect('produit')
                    ->where('promo.description LIKE :query OR produit.nom LIKE :query')
                    ->setParameter('query', '%' . $query . '%');

                $promos = $paginator->paginate(
                    $queryBuilder->getQuery(),
                    $request->query->getInt('page', 1),
                    12
                );
            }
        }

        if (!$promos) {
            $queryBuilder = $promoRepo->createQueryBuilder('promo')
                ->leftJoin('promo.id_produit', 'produit')
                ->addSelect('produit')
                ->orderBy('promo.date_debut', 'DESC');

            $promos = $paginator->paginate(
                $queryBuilder->getQuery(),
                $request->query->getInt('page', 1),
                12
            );

            // Pagination par catégorie 
            foreach ($categories as $categorie) {
                $queryCat = $promoRepo->createQueryBuilder('promo')
                    ->leftJoin('promo.id_produit', 'produit')
                    ->where('produit.id_categorie = :catId')
                    ->setParameter('catId', $categorie)
                    ->orderBy('promo.date_debut', 'DESC')
                    ->getQuery();

                $promosParCategorie[$categorie->getId()] = $paginator->paginate(
                    $queryCat,
                    $request->query->getInt('page_' . $categorie->getId(), 1),
                    8
                );
            }
        } else {
            // Recherche active, on initialise les clés vides pour éviter erreurs Twig
            foreach ($categories as $categorie) {
                $promosParCategorie[$categorie->getId()] = null;
            }
        }

        return $this->render('promos/index.html.twig', [
            'categories' => $categories,
            'promos' => $promos,
            'promosParCategorie' => $promosParCategorie,
            'form' => $searchForm->createView(),
        ]);
    }

    // Gestion promos (backend)
    #[Route('/promos', name: 'app_promos_gestion', methods: ['GET'])]
    public function gestion(PromosRepository $promosRepository, ProduitsRepository $produitsRepository): Response
    {
        return $this->render('promos/gestionPromos.html.twig', [
            'promos' => $promosRepository->findAll(),
            'produits' => $produitsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_promos_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em, ProduitsRepository $produitsRepository): Response
    {
        $promo = new Promos();

        $produitId = $request->request->get('produit_id');
        $nouveauPrix = $request->request->get('nouveauPrix');
        $description = $request->request->get('Description');
        $dateDebut = $request->request->get('dateDebut');
        $dateFin = $request->request->get('dateFin');

        $produit = $produitsRepository->find($produitId);
        if (!$produit) {
            throw $this->createNotFoundException('Produit introuvable');
        }

        $promo->setIdProduit($produit);
        $promo->setMontant($nouveauPrix);
        $promo->setDescription($description);
        $promo->setDateDebut(new \DateTime($dateDebut));
        $promo->setDateFin(new \DateTime($dateFin));

        $em->persist($promo);
        $em->flush();

        return $this->redirectToRoute('app_promos_gestion');
    }


   #[Route('/{id}/edit', name: 'app_promos_edit', methods: ['POST'])]
    public function edit(Request $request, EntityManagerInterface $em, int $id): Response
    {
        if (!$request->isMethod('POST')) {
            return $this->redirectToRoute('app_promos_gestion');
        }
        // Vérification CSRF
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('edit_promo' . $id, $submittedToken)) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }

        $promo = $em->getRepository(Promos::class)->find($id);
        if (!$promo) {
            throw $this->createNotFoundException("Cette promotion n'existe pas.");
        }

        // Récupération des données
        $nouveauPrix = $request->request->get('nouveauPrix');
        $description = $request->request->get('Description');
        $dateDebutStr = $request->request->get('dateDebut');
        $dateFinStr = $request->request->get('dateFin');
        $produitId = $request->request->get('produit_id');

        if (!$produitId) {
            throw new \Exception("produit_id est manquant dans la requête.");
        }

        $produit = $em->getRepository(Produits::class)->find($produitId);
        if (!$produit) {
            throw new \Exception("Produit introuvable.");
        }

        // Mise à jour des champs
        $promo->setMontant($nouveauPrix);
        $promo->setDescription($description);
        $promo->setDateDebut(new \DateTime($dateDebutStr));
        $promo->setDateFin(new \DateTime($dateFinStr));
        $promo->setIdProduit($produit);

        $em->flush();

        return $this->redirectToRoute('app_promos_gestion');
    }




   #[Route('/{id}', name: 'app_promos_delete', methods: ['POST'])]
    public function delete(Request $request, Promos $promo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $promo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($promo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_promos_gestion', [], Response::HTTP_SEE_OTHER);
    }

}
