<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameUser;
use App\Form\GameUserEditType;
use App\Form\GameUserType;
use App\Repository\GameRepository;
use App\Repository\GameUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyGameListController extends AbstractController
{
    #[Route('/my/game/list', name: 'app_my_game_list')]
    public function index(GameUserRepository $gameUserRepository, EntityManagerInterface $entityManager): Response
    {
     
        $orderBy = 'gu.rate';
        $direction = 'ASC';
        


        $form = $this->createForm(GameUserType::class);

        return $this->render('my_game_list/index.html.twig', [
            'controller_name' => 'MyGameListController',
            'formEdit' => $form->createView(),
        ]);
    }

    #[Route('/remove/{gameUserId}', name: 'app_remove')]
    public function deleteGameUserAction(Request $request,EntityManagerInterface $entityManager, int $gameUserId)
    {
        $url = $request->headers->get('referer');
        $repository = $entityManager->getRepository(GameUser::class);
        $gameUser = $repository->find($gameUserId);

        if ($gameUser) {
            $entityManager->remove($gameUser);
            $entityManager->flush();

            return $this->redirect($url);
        } else {
            // si entiy GameUser pas trouvé
            return $this->redirect($url);
        }
    }
    #[Route('/fav/{gameUserId}', name: 'app_fav')]
    public function changeFav(EntityManagerInterface $entityManager, int $gameUserId)
    {
        $repository = $entityManager->getRepository(GameUser::class);
        $gameUser = $repository->find($gameUserId);

        if ($gameUser) {
            $gameUser->toggleIsFav();
            $entityManager->flush();

            return $this->redirectToRoute('app_game_user_index');
        } else {
            // si entiy GameUser pas trouvé
            return $this->redirectToRoute('app_game_user_index');
        }
    }
   
    #[Route('/{id}/edit', name: 'app_game_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GameUser $gameUser,GameRepository $gameRepository, GameUserRepository $gameUserRepository,EntityManagerInterface $entityManager,int $id): Response
    {
        $form = $this->createForm(GameUserType::class, $gameUser);
        $form->handleRequest($request);
        if (!$gameUser) {
            throw $this->createNotFoundException('GameUser not found');
        }
        $gameUser = $gameUserRepository->find($id);
        $game = new Game();
        $game = $gameRepository->find($gameUser->getGame()->getId());
        

        if ($form->isSubmitted() &&$form->isValid()) {
            $gameUser->setComments($form->get('comments')->getData());
            $entityManager->persist($gameUser);
            $entityManager->flush();
            dump($gameUser);
            return $this->redirectToRoute('app_game_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game_user/edit.html.twig', [
            'game_user' => $gameUser,
            'form' => $form,
            'game' => $game,
        ]);
    }
    
    #[Route('/edit/{gameUserId}', name: 'app_edit')]
    public function editGame(EntityManagerInterface $entityManager, Request $request, int $gameUserId)
    {
        // Récupérer l'entité GameUser correspondante à partir de la base de données
        $repository = $entityManager->getRepository(GameUser::class);
        $gameUser = $repository->find($gameUserId);
    
        if (!$gameUser) {
            // Si l'entité GameUser n'est pas trouvée, rediriger vers la liste des jeux
            return $this->redirectToRoute('app_game_user_index');
        }
    
        // Créer le formulaire et le lier à l'entité GameUser
        $form = $this->createForm(GameUserEditType::class, $gameUser);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont automatiquement appliquées à l'entité GameUser
    
            $gameUser->setComments($form->get('comments')->getData());
            // Persist et flush l'entité pour enregistrer les modifications dans la base de données
            $entityManager->persist($gameUser);
            $entityManager->flush();
            
            // Redirection ou autres actions après le traitement
            
        }
    
        // Si le formulaire n'est pas soumis ou n'est pas valide, afficher le formulaire
        return $this->redirectToRoute('app_my_game_list');
    }
    

}
