<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Form\SearchType;
use App\Service\ApiDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{

    #[Route('/genre', name: 'app_genre_selector')]
    public function genreSelector(Request $request): Response
    {
        $formSearch = $this->createForm(SearchType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $searchWord = $formSearch->get('searchText')->getData();
            $searchWordUpdated = strtr($searchWord, ' ', '-');
            
            //nouvelle instance du form pour vider sa value 
            $formSearch = $this->createForm(SearchType::class);
            return $this->redirectToRoute('app_search',['searchWord' => $searchWordUpdated]);
        }

        return $this->render('genre/genre_selector.html.twig', [
            'controller_name' => 'GameController',
            'formSearch'=>$formSearch->createView(),
        ]);
    }

    #[Route('/genre/{genre}', name: 'app_genre', defaults: ['page' => 1])]
    #[Route('/genre/{genre}/{page}', name: 'app_genre_paginated', requirements: ['page' => '\d+'])]
    public function index(Request $request, string $genre,ApiDataService $apiDataService, int $page): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $genre = strtolower($genre);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchWord = $form->get('searchText')->getData();
            $searchWordUpdated = strtr($searchWord, ' ', '-');
            
            //nouvelle instance du form pour vider sa value 
            $form = $this->createForm(SearchType::class);
            return $this->redirectToRoute('app_search',['searchWord' => $searchWordUpdated]);
        }
        
        
        $formOrder = $this->createFormBuilder()
        ->add('ordering', ChoiceType::class, [
            'choices'  => [
                'Revelance' => '-relevance',
                'Highest Metacritics' => '-metacritic',
                'Best rating' => '-rating',
                'Recent released' => '-released',
                'Name A-Z' => '-name',
                'Name Z-A' => 'name',
                
            ],
            'data' => $request->query->get('ordering', ''),
        ])
        ->getForm();

        $formOrder->handleRequest($request);

        if ($formOrder->isSubmitted() && $formOrder->isValid()) {
            // récupère la valeur du champ 'ordering'
            $ordering = $formOrder->get('ordering')->getData();
            $page = 1;
            return $this->redirectToRoute('app_genre_paginated', ['ordering' => $ordering,'page'=>$page,'genre'=>$genre]);
            
        }

        $ordering = $request->query->get('ordering', '');
        

        $apiKey = $this->getParameter('my_api_key');
        $apiUrl = "https://api.rawg.io/api/games?ordering=$ordering&key=$apiKey&genres=$genre&page=$page";
        $games=null;
        $games = $apiDataService->fetchDataFromApi($apiUrl);
        $ordering = '';

        if(empty($games)){
            $page --;
            $apiUrl = "https://api.rawg.io/api/games?ordering=$ordering&key=$apiKey&genres=$genre&page=$page";
            $games = $apiDataService->fetchDataFromApi($apiUrl);
        }
       
        return $this->render('genre/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $games,
            'formSearch'=>$form->createView(),
            'formOrderGenre'=>$formOrder->createView(),
            'page'=>$page,
            'genre'=>$genre
        ]);
    }


}
