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

class PlatformController extends AbstractController
{
    #[Route('/platform', name: 'app_platform')]
    public function index(Request $request): Response
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
  
        $apiKey = $this->getParameter('my_api_key');
        $apiUrl = "https://api.rawg.io/api/platforms?key=$apiKey";
        $ch = curl_init($apiUrl);
        $response = curl_exec($ch);
         // Configuration des options cURL
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

         // Ignorer la vérification SSL (À utiliser avec précaution !)
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

         // Exécution de la requête
         $response = curl_exec($ch);
 
         // Vérification des erreurs cURL
         if (curl_errno($ch)) {
             die('Erreur cURL : ' . curl_error($ch));
         }
 
         // Fermeture de la session cURL
         curl_close($ch);
        $data = json_decode($response, true);
        $platforms = [];
        foreach($data['results'] as $platformdata){
            $platform = new Platform();
            $platform->setImage($platformdata['image_background']);
            $platform->setName($platformdata['name']);
            $platform->setapiId($platformdata['id']);
            array_push($platforms, $platform);
        }



        return $this->render('platform/platform_selector.html.twig', [
            'controller_name' => 'PlatformController',
            'platforms' => $platforms,
            'formSearch'=>$formSearch->createView(),
        ]);
    }


    #[Route('/platform/{id}', name: 'app_platform_search', defaults: ['page' => 1])]
    #[Route('/platform/{id}/{page}', name: 'app_platform_search_paginated', requirements: ['page' => '\d+'])]
    public function promptPlatform(Request $request, ApiDataService $apiDataService,int $id,int $page): Response
    {
        $apiKey = $this->getParameter('my_api_key');
        $apiUrl = "https://api.rawg.io/api/games?platforms=$id&key=$apiKey";

        $games=null;
        $games = $apiDataService->fetchDataFromApi($apiUrl);

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
            return $this->redirectToRoute('app_platform_search_paginated', ['ordering' => $ordering,'page'=>$page,'id'=>$id]);
            
        }

        $ordering = $request->query->get('ordering', '');
        

        $apiKey = $this->getParameter('my_api_key');
        $apiUrl = "https://api.rawg.io/api/games?platforms=$id&key=$apiKey&ordering=$ordering&page=$page";
        $games=null;
        $games = $apiDataService->fetchDataFromApi($apiUrl);
        $ordering = '';

        if(empty($games)){
            $page --;
            $apiUrl = "https://api.rawg.io/api/games?platforms=$id&key=$apiKey&ordering=$ordering&page=$page";
            $games = $apiDataService->fetchDataFromApi($apiUrl);
        }

        return $this->render('platform/index.html.twig', [
            'controller_name' => 'PlatformController',
            'games' => $games,
            'formSearch'=>$form->createView(),
            'formOrder'=>$formOrder->createView(),
            'page'=>$page,
            'idPlatform'=>$id
        ]);
    }
}
