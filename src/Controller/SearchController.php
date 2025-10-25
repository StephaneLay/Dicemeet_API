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
    #[Route('/api/public/search', name: 'app_search', methods: ['GET','OPTIONS'])]
    public function search(Request $request, CityRepository $cityRepository, GameRepository $gameRepository, PlaceRepository $placeRepository): Response
    {
        $query = $request->query->get('search','');
        $type = $request->query->get('type','');
        $results = [];
        
        
        $cityResults = $cityRepository->searchByName($query);
        foreach ( $cityResults as $city) {
            if ($type == 'city' || $type == '') {
                $results[] = [
                'id' => $city->getId(),
                'name' => $city->getName(),
                'type' => 'ville'
            ];
            }
        }

        $gameResults = $gameRepository->searchByName($query);
        foreach ( $gameResults as $game) {
            if ($type == 'games' || $type == '') {
                $results[] = [
                'id' => $game->getId(),
                'name' => $game->getName(),
                'type' => 'jeu'
            ];
            }
        }

        $placeResults = $placeRepository->searchByName($query);
        foreach ( $placeResults as $place) {
            if ($type == 'places' || $type == '') {
                $results[] = [
                'id' => $place->getId(),
                'name' => $place->getName(),
                'type' => 'lieu'
            ];
            }
        }

        return $this->json($results);
        
    }
}
