<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/api/private/games', name: 'app_game', methods: ['GET'])]
    public function getAll(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findAll();

        return $this->json($games, 200);
    }

    #[Route('/api/private/games/{id}', name: 'app_game_by_id', methods: ['GET'])]
    public function getGameById(GameRepository $gameRepository, int $id): Response
    {
        $game = $gameRepository->find($id);
        if (!$game) {
            return $this->json(['error' => 'Jeu non trouvé'], 404);
        }

        return $this->json($game, 200);
    }
}