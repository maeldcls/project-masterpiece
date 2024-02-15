<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use App\Service\ApiDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatformController extends AbstractController
{
    #[Route('/platform', name: 'app_platform')]
    public function index(ApiDataService $apiDataService): Response
    {
       
  
        $apiKey = $_ENV['API_KEY'];
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
        ]);
    }

    #[Route('/platform/{id}', name: 'app_platform_search')]
    public function promptPlatform(ApiDataService $apiDataService,int $id): Response
    {
        $apiKey = $_ENV['API_KEY'];
        $apiUrl = "https://api.rawg.io/api/games?platforms=$id&key=$apiKey";

        $games=null;
        $games = $apiDataService->fetchDataFromApi($apiUrl);

        return $this->render('platform/index.html.twig', [
            'controller_name' => 'PlatformController',
            'games' => $games,
        ]);
    }
}
