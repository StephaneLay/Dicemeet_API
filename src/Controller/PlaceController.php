<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlaceController extends AbstractController
{
    #[Route('/place', name: 'app_place')]
    public function index(): Response
    {
        return $this->render('place/index.html.twig', [
            'controller_name' => 'PlaceController',
        ]);
    }

    #[Route('/api/public/places/search', name: 'app_place_search', methods: ['GET'])]
    public function search(Request $request, PlaceRepository $placeRepository): Response
    {
        $query = $request->query->get('search');

        $results = $placeRepository->searchByName($query);

        return $this->json($results);
    }
}
