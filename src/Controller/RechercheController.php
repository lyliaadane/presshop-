<?php


namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Produits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\RechercheType;
use App\Repository\CategoriesRepository;
use App\Repository\ProduitsRepository;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function searchFormController(Request $request, CategoriesRepository $categoriesRepository, ProduitsRepository $produitsRepository)
    {
        $searchForm = $this->createForm(RechercheType::class);
        $searchForm->handleRequest($request);
        $produits = [];
        $categories = [];

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchData = $searchForm->getData();
            $query = $searchData['query']; 
        
          if ($query) {
                $produits = $produitsRepository->searchProductsAndCategories($query); 
            }
           
        }

        return $this->render('recherche/index.html.twig', [
            'form' => $searchForm->createView(),
           'produits' => $produits,
        ]);
    }
}

