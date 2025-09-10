<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/api/games', name: 'app_game', methods: ['GET'])]
    public function getAll(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findAll();

        return $this->json($games, 200, [] ,['groups' => 'game:read']);
    }

    #[Route('/api/public/games/search', name: 'app_game_search', methods: ['GET'])]
    public function search(Request $request, GameRepository $gameRepository): Response
    {
        $query = $request->query->get('search');

        $results = $gameRepository->searchByName($query);

        return $this->json($results);
    }
}
