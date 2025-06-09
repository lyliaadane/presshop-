<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ReviewService;

#[Route('/avis')]
final class AvisController extends AbstractController
{
    /*#[Route('/avis', name: 'app_avis')]
    public function avis(GooglePlacesService $googlePlacesService): Response
    {
        $placeId = 'ChIJN1t_tDeuEmsRUsoyG83frY4'; // à remplacer par ton ID d’établissement
        $reviews = $googlePlacesService->getPlaceDetails($placeId);

        return $this->render('avis/index.html.twig', [
            'reviews' => $reviews
        ]);
    }*/

    #[Route('', name: 'app_avis')]
    public function index(ReviewService $reviewService): Response
    {
        $reviews = $reviewService->getReviews();
        return $this->render('avis/index.html.twig', [
            'reviews' => $reviews
        ]);
    }

}
