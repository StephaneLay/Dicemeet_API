<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/games', name: 'app_game', methods: ['GET'])]
    public function getAll(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findAll();

        return $this->json($games, 200, [] ,['groups' => 'game:read']);
    }
}
