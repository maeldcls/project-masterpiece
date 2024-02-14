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
       
        // $apiUrl = "https://api.rawg.io/api/games?key=$apiKey&platform=$platform&ordering=-metacritic&page_size=$limit";
        // $apiData = $apiDataService->fetchDataFromApi($apiUrl);
        $apiKey = "85c1e762dda2428786a58b352a42ade2";
        $apiUrl = "https://api.rawg.io/api/platforms?&key=$apiKey";
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
        dd($data);

        return $this->render('platform/platform_selector.html.twig', [
            'controller_name' => 'PlatformController',

        ]);
    }
}
