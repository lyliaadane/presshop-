<?php
namespace App\Service;

use Google\Client;
use Google\Service\Places;

class GooglePlacesService
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getPlaceDetails(string $placeId): ?array
    {
        $url = sprintf(
            'https://maps.googleapis.com/maps/api/place/details/json?place_id=%s&fields=name,rating,reviews&key=%s',
            $placeId,
            $this->apiKey
        );

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        return $data['result']['reviews'] ?? null;
    }
}
