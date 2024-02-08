<?php
namespace App\Service;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiDataService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchDataFromApi($url)
    {
        // Logique pour effectuer la requête API et récupérer les données
        // $response = $this->client->request('GET', $url);
        // $data = $response->toArray();

        
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


        $results = $data['results'];
        $games = [];

        foreach ($results as $data) {
            $game = new Game();
            if (isset($data['background_image']) && !empty($data['background_image'])) {
                // Publishers
                $game->setTitle($data['name']);
                //$game->setSummary($data['description']);
                if (isset($data['metacritic'])) {
                    $game->setMetacritics($data['metacritic']);
                }
                // pour accéder aux différents screenshots 
                // $game->setBackgroundImage($data['short_screenshots'][0]['image']);



                foreach ($data['short_screenshots'] as $screenshot) {
                    $screenshots[] = $screenshot['image'];
                }

                $game->setBackgroundImage($data['background_image']);
                // $jsonScreenshots = json_encode($screenshots);
                $game->setScreenshots($screenshots);
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
                            $uniquePlatforms[] = $platformSlug; // Ajouter la plateforme au tableau des éléments uniques
                        }
                    }
                    $game->setParentPlatform($parentPlatforms);
                }
                if (isset($data['id'])) {
                    $game->setGameId($data['id']);
                }
                array_push($games, $game);
                
                
            }
        }
        
        return $games;
    }
}