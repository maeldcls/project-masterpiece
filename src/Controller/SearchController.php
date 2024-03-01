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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search/{searchWord}', name: 'app_search', defaults: ['page' => 1])]
    #[Route('/search/{searchWord}/{page}', name: 'app_search_paginated', requirements: ['page' => '\d+'])]
    public function index(Request $request,$searchWord, ApiDataService $apiDataService,int $page): Response
    {
        $searchWordUpdated = strtr($searchWord, '-', ' ');
     
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);


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
            return $this->redirectToRoute('app_search_paginated', ['ordering' => $ordering,'page'=>$page,'searchWord'=>$searchWord]);
            
        }

        $ordering = $request->query->get('ordering', '');
        

        $apiKey = $this->getParameter('my_api_key');
        $apiUrl = "https://api.rawg.io/api/games?key=$apiKey&search=$searchWord&ordering=$ordering&page=$page";
        $games=null;
        $games = $apiDataService->fetchDataFromApi($apiUrl);
        $ordering = '';

        if(empty($games)){
            $page --;
            $apiUrl = "https://api.rawg.io/api/games?key=$apiKey&search=$searchWord&ordering=$ordering&page=$page";
            $games = $apiDataService->fetchDataFromApi($apiUrl);
        }
       

        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'games' => $games,
            'searchedGame'=>$searchWordUpdated,
            'searchWord'=>$searchWord,
            'formSearch'=>$form->createView(),
            'formOrder'=>$formOrder->createView(),
            'page'=>$page,
        ]);
    }
}
