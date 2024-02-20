<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Service\ApiDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{

    #[Route('/genre', name: 'app_genre_selector')]
    public function genreSelector(): Response
    {
        

        return $this->render('genre/genre_selector.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    #[Route('/genre/{genre}', name: 'app_genre')]
    public function index($genre,ApiDataService $apiDataService): Response
    {
        $game = new Game();
        $user = $this->getUser();

        $genre = strtolower($genre);

        $apiKey = $this->getParameter('my_api_key');
        $limit = 24; // Nombre de jeux à récupérer
        $ordering = "-metacritic"; 
        $apiUrl = "https://api.rawg.io/api/games?ordering=$ordering&key=$apiKey&genres=$genre&page_size=$limit";
        $apiData = $apiDataService->fetchDataFromApi($apiUrl,$user);
       

        return $this->render('genre/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $apiData
        ]);
    }


}
