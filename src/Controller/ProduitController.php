<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Entity\Categories;
use App\Form\ProduitsType;
use App\Form\RechercheType;
use App\Repository\ProduitsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    //produits client 
    #[Route(name: 'app_produit_index',  methods: ['GET', 'POST'])]
    public function index(
        ProduitsRepository $produitRepo,
        CategoriesRepository $categorieRepo,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $categories = $categorieRepo->findAll();

        $searchForm = $this->createForm(RechercheType::class);
        $searchForm->handleRequest($request);
    
        $produits = null;
        $produitsParCategorie = [];

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $query = $searchForm->get('query')->getData();
            if ($query) {
                // Paginer les résultats de la recherche
                $searchResults = $produitRepo->searchProductsAndCategories($query);
                $produits = $paginator->paginate(
                    $searchResults,
                    $request->query->getInt('page', 1),
                    16
                );
            }
        }
        if (!$produits) {
            $produits = $paginator->paginate(
                $produitRepo->createQueryBuilder('p')->orderBy('p.nom', 'ASC')->getQuery(),
                $request->query->getInt('page', 1),
                16
            );

            foreach ($categories as $categorie) {
                $query = $produitRepo->createQueryBuilder('p')
                    ->where('p.id_categorie = :categorie')
                    ->setParameter('categorie', $categorie)
                    ->orderBy('p.nom', 'ASC')
                    ->getQuery();

                $produitsParCategorie[$categorie->getId()] = $paginator->paginate(
                    $query,
                    $request->query->getInt('page_' . $categorie->getId(), 1),
                    8
                );
            } 
        } else {
            // En cas de recherche, on initialise quand même les clés vides pour éviter les erreurs Twig
            foreach ($categories as $categorie) {
                $produitsParCategorie[$categorie->getId()] = null;
            }
        }

        return $this->render('produit/index.html.twig', [
            'categories' => $categories,
            'produits' => $produits,
            'produitsParCategorie' => $produitsParCategorie,
            'form' => $searchForm->createView(),
        ]);
    }
    
    

    //gestion produits
    #[Route('/produits', name: 'app_produits', methods: ['GET'])]
    public function produts(ProduitsRepository $produitsRepository, CategoriesRepository $CategoriesRepository,AuthorizationCheckerInterface $authChecker,): Response
    {
        // ✅ Tester `is_granted`
        $isAdmin = $authChecker->isGranted('ROLE_ADMIN');
      
        return $this->render('produit/gestionProduits.html.twig', [
            'produits' => $produitsRepository->findAll(),
            'categories' => $CategoriesRepository->findAll(),
            'isAdmin' => $isAdmin,
        ]);
    }
    

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $produit = new Produits();
    
        $produit->setNom($request->request->get('nom'));
        $produit->setPrix($request->request->get('prix'));
        $produit->setUniteVente($request->request->get('unite_vente'));
        $produit->setQuantite((int) $request->request->get('quantite'));
        $produit->setSeuil((int) $request->request->get('seuil'));
    
        // Gérer la catégorie
        $categorieId = $request->request->get('categorie_id');
        $categorie = $em->getRepository(Categories::class)->find($categorieId);
        if ($categorie) {
            $produit->setIdCategorie($categorie);
        }
    
        // Gérer l'image sans Slugger
        /** @var UploadedFile $imageFile */
        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $originalName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $imageFile->guessExtension() ?? 'jpg';
            $newFilename = $originalName . '-' . uniqid() . '.' . $extension;
    
            $imageFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );
    
            $produit->setImage($newFilename);
        }
    
        $em->persist($produit);
        $em->flush();
    
        return $this->redirectToRoute('app_produits'); 
    }


    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['POST'])]
    public function edit(Request $request, Produits $produit, EntityManagerInterface $em): Response
    {
        $produit->setNom($request->request->get('nom'));
        $produit->setPrix($request->request->get('prix'));
        $produit->setUniteVente($request->request->get('unite_vente'));
        $produit->setQuantite($request->request->get('quantite'));
        $produit->setSeuil($request->request->get('seuil'));

        // Catégorie
        $categorieId = $request->request->get('categorie_id');
        $categorie = $em->getRepository(Categories::class)->find($categorieId);
        if ($categorie) {
            $produit->setIdCategorie($categorie);
        }

        // Image
        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $originalName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $imageFile->guessExtension() ?? 'jpg';
            $newFilename = $originalName . '-' . uniqid() . '.' . $extension;

            $imageFile->move($this->getParameter('images_directory'), $newFilename);
            $produit->setImage($newFilename);
        }

        $em->flush();

        return $this->redirectToRoute('app_produits'); 
    }


    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produits $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            foreach ($produit->getCommandeProduits() as $commandeProduit) {
                $entityManager->remove($commandeProduit);
            }
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produits', [], Response::HTTP_SEE_OTHER);
    }

}
