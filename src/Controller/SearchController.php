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
    #[Route('/search/{searchWord}', name: 'app_search', defaults: ['page' => 1])]
    #[Route('/search/{searchWord}/{page}', name: 'app_search_paginated', requirements: ['page' => '\d+'])]
    public function index($searchWord, ApiDataService $apiDataService,int $page): Response
    {

        $searchWordUpdated = strtr($searchWord, '-', ' ');
        $apiKey = $this->getParameter('my_api_key');
        $apiUrl = "https://api.rawg.io/api/games?key=$apiKey&search=$searchWord&ordering=-metacritic&page=$page";
        $apiData = $apiDataService->fetchDataFromApi($apiUrl);

        $count = count($apiData);
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'games' => $apiData,
            'searchedGame'=>$searchWordUpdated,
            'searchedWord'=>$searchWord,
            'count'=>$count,
            'page'=>$page,
        ]);
    }
}
