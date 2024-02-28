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

#[Route('/game/user')]
class GameUserController extends AbstractController
{
    #[Route('/', name: 'app_game_user_index', defaults: ['orderBy' => 'title' ,'direction'=> 'ASC'] ,methods: ['GET'])]
    #[Route('/{orderBy}/{direction}', name: 'app_game_user_index_order', methods: ['GET'])]
    public function index(GameUserRepository $gameUserRepository, string $orderBy, string $direction): Response
    {
      if($orderBy == 'title'){
        $order = 'g.title';
      } else {
        $order = 'gu.'.$orderBy;
      }
        
        $idUser = $this->getUser()->getId();
        $result = $gameUserRepository->showMyGames($idUser,$order,$direction);

        $form = $this->createForm(GameUserType::class);

        return $this->render('game_user/index.html.twig', [
            'controller_name' => 'GameUserController',
            'gameUser' => $result,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_game_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gameUser = new GameUser();
        $form = $this->createForm(GameUserType::class, $gameUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gameUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_game_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game_user/new.html.twig', [
            'game_user' => $gameUser,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_game_user_show', methods: ['GET'])]
    public function show(GameUser $gameUser): Response
    {
        return $this->render('game_user/show.html.twig', [
            'game_user' => $gameUser,
        ]);
    }

    

    #[Route('/{id}', name: 'app_game_user_delete', methods: ['POST'])]
    public function delete(Request $request, GameUser $gameUser, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gameUser->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gameUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_game_user_index', [], Response::HTTP_SEE_OTHER);
    }

    

   
}
