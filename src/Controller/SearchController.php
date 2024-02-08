<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use App\Form\SearchType;
use App\Service\ApiDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search/{searchWord}', name: 'app_search')]
    public function index($searchWord, ApiDataService $apiDataService): Response
    {

        $searchWordUpdated = strtr($searchWord, '-', ' ');

        $apiKey = "85c1e762dda2428786a58b352a42ade2";

        $apiUrl = "https://api.rawg.io/api/games?key=$apiKey&search=$searchWord&ordering=-metacritic&limit=100";
        $apiData = $apiDataService->fetchDataFromApi($apiUrl);

        $count = count($apiData);
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'games' => $apiData,
            'searchedGame'=>$searchWordUpdated,
            'count'=>$count
        ]);
    }
}
