<?php

namespace App\Service;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\GameUser;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\GameUserRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiDataService
{
  
    private $gameRepository;
    private $gameUserRepository;
    private $security;

    public function __construct(
        GameRepository $gameRepository,
        GameUserRepository $gameUserRepository,
        Security $security
    ) {
        $this->gameRepository = $gameRepository;
        $this->gameUserRepository = $gameUserRepository;
        $this->security = $security;
    }

    public function fetchDataFromApi($url)
    {
        // Initialisation de cURL
        $ch = curl_init($url);
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
        // Traitement de la réponse
        $data = json_decode($response, true);

        if(!empty($data['results'])){
            $results = $data['results'];
            $games = [];
    
            foreach ($results as $data) {
                $game = new Game();
                if (isset($data['name']) && !empty($data['name'])) {
                    // Publishers
                    $game->setTitle($data['name']);

                    if (isset($data['metacritic'])) {
                        $game->setMetacritics($data['metacritic']);
                    }
    
                    if(isset($data['short_screenshots'])){
                        foreach ($data['short_screenshots'] as $screenshot) {
                            $screenshots[] = $screenshot['image'];
                        }
                        
                    }   
                    if(!empty($screenshots)){
                        $game->setScreenshots($screenshots);
                    }else{
                        $screenshots[0] ="/assets/other/default.jpg";
                        $game->setScreenshots($screenshots);
                    }
    
                    if (isset($data['background_image'])) {
                        $game->setBackgroundImage($data['background_image']);
                    }else if(isset($screenshots[0])){
                        $game->setBackgroundImage($screenshots[0]);
                    }else{
                        $game->setBackgroundImage("/assets/other/default.jpg");
                    }
                    
                    
                    unset($screenshots);
    
                    if (isset($data['publishers'])) {
                        foreach ($data['publishers'] as $publisher) {
                            $publi = new Publisher();
                            $publi->setName($publisher['name']);
                            $game->addPublisher($publi);
                        }
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
                            $platform->setApiId($platformData['platform']['id']);
                            if (isset($data['platforms']['image_background'])) {
                                $platform->setImage($platformData['platform']['image_background']);
                            }
                            $game->addPlatform($platform);
                        }
                    }
                    if (isset($data['parent_platforms'])) {
                        $parentPlatforms = [];
                        $uniquePlatforms = [];
    
                        foreach ($data['parent_platforms'] as $parentPlatformData) {
                            $platformSlug = $parentPlatformData['platform']['slug'];
    
                            if (!in_array($platformSlug, $uniquePlatforms)) {
                                $parentPlatforms[] = $platformSlug;
                                $uniquePlatforms[] = $platformSlug;
                            }
                        }
                        $game->setParentPlatform($parentPlatforms);
                    }
                    if (isset($data['id'])) {
                        $game->setGameId($data['id']);
                    }
    
                    $user = $this->security->getUser();
                    if (!empty($user)) {
                        $gameInDatabase = $this->gameRepository->findOneBy(['gameId' => $game->getGameId()]);
    
                        if (!empty($gameInDatabase)) {
                            $gameUserOfGameInDatabase = $this->gameUserRepository->findOneBy(['game' => $gameInDatabase, 'user' => $user]);
                            if (!empty($gameUserOfGameInDatabase)) {
                                $game->addGameUser($gameUserOfGameInDatabase);
                            }
    
                        }
                    }
                    array_push($games, $game);
                }
            }
            return $games;
        }
        
        return null;
    }
}
