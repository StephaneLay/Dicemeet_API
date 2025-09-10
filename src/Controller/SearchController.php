<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\GameRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/api/public/search', name: 'app_search')]
    public function search(Request $request, CityRepository $cityRepository, GameRepository $gameRepository, PlaceRepository $placeRepository): Response
    {
        $query = $request->query->get('search','');

        $cityResults = $cityRepository->searchByName($query);
        foreach ( $cityResults as $city) {
            $results[] = [
                'name' => $city->getName(),
                'type' => 'ville'
            ];
        }

        $gameResults = $gameRepository->searchByName($query);
        foreach ( $gameResults as $game) {
            $results[] = [
                'name' => $game->getName(),
                'type' => 'jeu'
            ];
        }

        $placeResults = $placeRepository->searchByName($query);
        foreach ( $placeResults as $place) {
            $results[] = [
                'name' => $place->getName(),
                'type' => 'lieu'
            ];
        }

        return $this->json($results);
        
    }
}
