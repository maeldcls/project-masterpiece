<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use App\Entity\Tag;
use App\Form\SearchType;
use App\Repository\GameRepository;
use App\Repository\GameUserRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameDetailsController extends AbstractController
{
    #[Route('/game/details/{id}', name: 'app_game_details',methods: ['GET','POST'])]
    public function index(Request $request,$id, GameRepository $gameRepository, GameUserRepository $gameUserRepository): Response
    {
        //initialisation du formulaire de recherche
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
        $game = new Game();
        $game->setTitle($data['name']);
        if (!empty($data['background_image'])) {
            $game->setBackgroundImage($data['background_image']);
        }else{
            $game->setBackgroundImage("/assets/other/default.jpg");
        }
        $game->setGameId($id);
        if (isset($data['released'])) {
            $game->setReleaseDate(new DateTimeImmutable($data['released']));
        }
        if (isset($data['description_raw'])) {
            $game->setSummary($data['description_raw']);
        }
        if (isset($data['publishers'])) {
            foreach ($data['publishers'] as $publisher) {
                $publi = new Publisher();
                $publi->setName($publisher['name']);
                $game->addPublisher($publi);
            }
        }
        if (isset($data['metacritic'])) {
            $game->setMetacritics($data['metacritic']);
        }

        // Developers
        if (isset($data['developers'])) {
            foreach ($data['developers'] as $developer) {
                $dev = new Developer();
                $dev->setName($developer['name']);
                $game->addDeveloper($dev);
            }
        }

        // Genres
        if (isset($data['genres'])) {
            foreach ($data['genres'] as $genreData) {
                $genre = new Genre();
                $genre->setName($genreData['name']);
                $game->addGenre($genre);
            }
        }

        // Platforms
        if (isset($data['platforms'])) {
            foreach ($data['platforms'] as $platformData) {
                $platform = new Platform();
                $platform->setName($platformData['platform']['name']);
                if (isset($data['platforms']['image_background'])) {
                    $platform->setImage($platformData['platform']['image_background']);
                }
                $game->addPlatform($platform);
            }
        }
        if(isset($data['tags'])){

            foreach($data['tags'] as $tag){
                $newTag = new Tag();
                $newTag->setName($tag['name']);
                $game->addTag($newTag);
            }
        }
        if(isset($data['website'])){
            $game->setWebsite($data['website']);
        }

        $apiKey = $this->getParameter('my_api_key');
        $apiUrl = "https://api.rawg.io/api/games/$id/screenshots?key=$apiKey";

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

        if(isset($data['results'])){
            foreach ($data['results'] as $screenshot) {
                $screenshots[] = $screenshot['image'];
            }
            if(!empty($screenshots)){
                $game->setScreenshots($screenshots);
            }
        }
        
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!empty($user)) {
            $gameInDatabase = $gameRepository->findOneBy(['gameId' => $game->getGameId()]);

            if (!empty($gameInDatabase)) {
                $gameUserOfGameInDatabase = $gameUserRepository->findOneBy(['game' => $gameInDatabase, 'user' => $user]);
                if (!empty($gameUserOfGameInDatabase)) {
                    $game->addGameUser($gameUserOfGameInDatabase);
                }
            }
        }

        return $this->render('game_details/index.html.twig', [
            'controller_name' => 'GameDetailsController',
            'game' => $game,
            'data' => $data,
            'formSearch' =>$formSearch->createView(),
        ]);
    }
}
