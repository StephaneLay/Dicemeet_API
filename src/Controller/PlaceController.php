<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlaceController extends AbstractController
{
    #[Route('/api/private/places/{id}', name: 'app_game_details', methods: ['GET'])]
    public function getDetails(PlaceRepository $placeRepository, int $id): Response
    {
        $place = $placeRepository->find($id);
        if (!$place) {
            return $this->json(['error' => 'Endroit non trouvé'], 404);
        }

        return $this->json($place, 200);
    }

   
}
