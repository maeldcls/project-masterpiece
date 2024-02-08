<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameDetailsController extends AbstractController
{
    #[Route('/game/details/{id}', name: 'app_game_details')]
    public function index($id): Response
    {
        $apiKey = "85c1e762dda2428786a58b352a42ade2";
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
       $game->setBackgroundImage($data['background_image']);
         $game->setGameId($id);
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
            if (isset($data['platforms']['image_background'])) {
                $platform->setImage($platformData['platform']['image_background']);
            }
            $game->addPlatform($platform);
        }
    }

        $apiKey = "85c1e762dda2428786a58b352a42ade2";
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

        foreach ($data['results'] as $screenshot) {
            $screenshots[] = $screenshot['image'];
        }

        $game->setScreenshots($screenshots);
        return $this->render('game_details/index.html.twig', [
            'controller_name' => 'GameDetailsController',
            'game'=>$game,
            'data'=>$data
        ]);
    }
}
?>