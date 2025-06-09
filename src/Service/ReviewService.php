<?php

namespace App\Service;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class ReviewService
{
    private string $filePath;

    // Le constructeur reçoit le répertoire du projet (par exemple, %kernel.project_dir%)
    
    public function __construct(ParameterBagInterface $params)
    {  
        $projectDir = $params->get('kernel.project_dir');
        $this->filePath = $projectDir . '/public/data/reviews.json';
    }

    // Méthode pour récupérer les avis depuis le fichier JSON
    public function getReviews(): array
    {
        // Vérifie si le fichier existe
        if (!file_exists($this->filePath)) {
            return []; // Retourne un tableau vide si le fichier n'existe pas
        }

        // Lit le contenu du fichier JSON
        $content = file_get_contents($this->filePath);

        // Décode le contenu JSON en tableau associatif et retourne le tableau
        return json_decode($content, true) ?? [];
    }
}
