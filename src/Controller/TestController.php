<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        $apiKey = $this->getParameter('my_api_key');
        $gameSlug = "the-witcher-3-wild-hunt"; // Remplacez par le slug du jeu que vous souhaitez rechercher

        $limit = 24; // Nombre de jeux à récupérer
        $keyword = "final-fantasy";
        $id= 11 ;

        // https://api.rawg.io/api/games?key=$apiKey&platforms=$id&ordering=-metacritic 
        // requete fonctionnel pour trouver des jeux sortis sur une plateforme donné ci dessus, pour cela il faut d'abord avoir l'id de la plateforme

        $ordering = "-released"; // Trier par popularité
        $apiUrl = "https://api.rawg.io/api/platforms?&key=$apiKey";
        //$apiUrl = "https://api.rawg.io/api/games?key=$apiKey&ordering=-popularity&page_size=$limit";



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
        // Vérifiez si des résultats ont été renvoyés
         dump($data);

        // $results = $data['results'];
        // var_dump($results);
        // foreach ($results as $data) {
            
        // }
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
