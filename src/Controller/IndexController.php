<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoriesRepository;
use App\Repository\ProduitsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Service\ReviewService;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(CategoriesRepository $categorieRepository, ProduitsRepository $produitRepository,TokenStorageInterface $tokenStorage,AuthorizationCheckerInterface $authChecker, Request $request,
    PaginatorInterface $paginator,ReviewService $reviewService): Response {
        $categories = $categorieRepository->findAll();
         // 🔁 Pagination des produits globaux
    $query = $produitRepository->createQueryBuilder('p')->getQuery();
    $produits = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        9
    );

    // 🔁 Pagination des produits par catégorie
    $produitsParCategorie = [];
    foreach ($categories as $categorie) {
        $queryCategorie = $produitRepository->createQueryBuilder('p')
            ->where('p.id_categorie = :cat')
            ->setParameter('cat', $categorie)
            ->getQuery();

        $produitsParCategorie[$categorie->getId()] = $paginator->paginate(
            $queryCategorie,
            $request->query->getInt('page_categorie_' . $categorie->getId(), 1),
            9
        );
    }

        /*//  Créer un utilisateur fictif pour le test
        $userTest = new InMemoryUser('test_admin', null, ['ROLE_EMPLOYE']);
        // 🔐 Définir l'utilisateur en tant qu'utilisateur authentifié dans Symfony
        $token = new UsernamePasswordToken($userTest, 'main', $userTest->getRoles());
        $tokenStorage->setToken($token);
        // ✅ Tester `is_granted`
        $isAdmin = $authChecker->isGranted('ROLE_ADMIN');*/

        $reviews = $reviewService->getReviews();

        return $this->render('index/index.html.twig', [
            'categories' => $categories,
            'produits' => $produits,
            'produitsParCategorie' => $produitsParCategorie,
            'reviews' => $reviews
            //'isAdmin' => $isAdmin,
        ]);
    }


    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response {

        return $this->render('index/contact.html.twig');
    }

    
    #[Route('/politique-de-confidentialite', name: 'privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('index/privacy_policy.html.twig');
    }

    #[Route('/cgv', name: 'cgv')]
    public function termsAndConditions(): Response
    {
        return $this->render('index/cgv.html.twig');
    }
}
