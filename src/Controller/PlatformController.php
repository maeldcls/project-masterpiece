<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use App\Service\ApiDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatformController extends AbstractController
{
    #[Route('/platform/{platform}', name: 'app_platform')]
    public function index($platform,ApiDataService $apiDataService): Response
    {
       
        // Remplacez "YOUR_API_KEY" par votre clé API RAWG.io
        $apiKey = "85c1e762dda2428786a58b352a42ade2";
        $limit = 25; // Nombre de jeux à récupérer

        $apiUrl = "https://api.rawg.io/api/games?key=$apiKey&platform=$platform&ordering=-metacritic&page_size=$limit";
        $apiData = $apiDataService->fetchDataFromApi($apiUrl);

        return $this->render('platform/index.html.twig', [
            'controller_name' => 'PlatformController',
            'games' => $apiData
        ]);
    }
}
