<?php

namespace App\Controller;

use App\Entity\GameUser;
use App\Form\GameUserEditType;
use App\Form\GameUserType;
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
      
        /** @var \App\Entity\User $user */
        $result = $gameUserRepository->showMyGames($this->getUser()->getId());

        dump($result);
        $form = $this->createForm(GameUserType::class);

        return $this->render('my_game_list/index.html.twig', [
            'controller_name' => 'MyGameListController',
            'gameUser' => $result,
            'formEdit' => $form->createView(),
        ]);
    }

    #[Route('/remove/{gameUserId}', name: 'app_remove')]
    public function deleteGameUserAction(EntityManagerInterface $entityManager, int $gameUserId)
    {
        $repository = $entityManager->getRepository(GameUser::class);
        $gameUser = $repository->find($gameUserId);

        if ($gameUser) {
            $entityManager->remove($gameUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_game_user_index');
        } else {
            // si entiy GameUser pas trouvé
            return $this->redirectToRoute('app_game_user_index');
        }
    }

   

    #[Route('/edit/{gameUserId}', name: 'app_edit')]
public function editGame(EntityManagerInterface $entityManager, Request $request, int $gameUserId)
{
    // Récupérer l'entité GameUser correspondante à partir de la base de données
    $repository = $entityManager->getRepository(GameUser::class);
    $gameUser = $repository->find($gameUserId);

    if (!$gameUser) {
        // Si l'entité GameUser n'est pas trouvée, rediriger vers la liste des jeux
        return $this->redirectToRoute('app_my_game_list');
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
