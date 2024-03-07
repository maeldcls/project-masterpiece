<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameUser;
use App\Service\ApiDataService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\SearchType;
use App\Repository\GameRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class GameController extends AbstractController
{
    
    #[Route('/', name: 'app_game', defaults: ['page' => 1])]
    #[Route('/{page}', name: 'app_game_paginated', requirements: ['page' => '\d+'])]
    public function index(Request $request, ApiDataService $apiDataService, int $page): Response
    {
        //initialisation du formulaire de recherche
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
            return $this->redirectToRoute('app_game', ['ordering' => $ordering]);
            
        }

        $ordering = $request->query->get('ordering', '');
        
        $apiKey = $this->getParameter('my_api_key');
        $apiUrl = "https://api.rawg.io/api/games?ordering=$ordering&key=$apiKey&page=$page";
        $games=null;
        $games = $apiDataService->fetchDataFromApi($apiUrl);
        $ordering = '';

        if(empty($games)){
            $page --;
            $apiUrl = "https://api.rawg.io/api/games?ordering=$ordering&key=$apiKey&page=$page";
            $games = $apiDataService->fetchDataFromApi($apiUrl);
        }

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $games,
            'formSearch'=>$form->createView(),
            'formOrder'=>$formOrder->createView(),
            'page'=>$page,

        ]);
    }


    #[Route('/new/{id}', name: 'app_add_game')]
    public function addGame(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $url = $request->headers->get('referer');
    
        if($this->getUser()){
            $manager =$entityManager->getRepository(Game::class);

            $foundGame = $manager->findby(['gameId' => $id]);
            $gameUser = new GameUser();
            $game = new Game();
            if(!$foundGame){
                $apiKey = $this->getParameter('my_api_key');
                $apiUrl = "https://api.rawg.io/api/games/$id?key=$apiKey";
        
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

                
                $game->setTitle($data['name']);
                
                if(isset($data['background_image'])){
                    $game->setBackgroundImage($data['background_image']);
                }else{
                    $game->setBackgroundImage("/assets/other/default.jpg");
                }
                if(isset($data['description_raw'])){
                    $game->setSummary($data['description_raw']);
                }
                $game->setReleaseDate(new \DateTimeImmutable($data['released']));
                
                if(isset($data['website'])){
                    $game->setWebsite($data['website']);
                }
                
                $game->setGameId($id);
                if(isset($data['metacritic'])){
                    $game->setMetacritics($data['metacritic']);
                }
                $entityManager->persist($game);
                $entityManager->flush();

                $gameUser->setGame($game);
                $gameId = $game->getid();
            }else{
                $gameId= $foundGame[0]->getId();
                $gameUser->setGame($foundGame[0]);
            }

            $gameUserManager =$entityManager->getRepository(GameUser::class);
            
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $foundGameUser = $gameUserManager->findby(['game' => $gameId, 'user' => $user->getId()]);
            
            if(!$foundGameUser){
                $gameUser->setUser($this->getUser());
                $gameUser->setAddedAt(new DateTimeImmutable());
                $gameUser->setIsFav(false);
                $gameUser->setStatus("Finished");

                $entityManager->persist($gameUser);
                $entityManager->flush();
            }
        }
        
         return $this->redirect($url);
    }

}
