<?php

namespace App\Controller;

use App\Repository\MeetupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class MeetupController extends AbstractController
{
    #[Route('/api/public/events', name: 'app_meetup' , methods: ['GET'])]
    public function getAll(MeetupRepository $meetupRepository, ): Response
    {
        $events = $meetupRepository->findAll();

       return $this->json($events, 200, [] ,context: ['groups' => 'category:read']);
    }

    #[Route('/api/public/events/search', name: 'app_meetup_search', methods: ['GET'])]
    public function search(Request $request,MeetupRepository $meetupRepository): Response
    {
        $filters = $request->query->get('filters', '');
        $filterArray = explode(',', $filters);

        $cityIds = [];
        $gameIds = [];
        $barIds = [];

        foreach ($filterArray as $filter) {
            [$type, $id] = explode(':', $filter);
            switch ($type) {
                case 'ville':
                    $cityIds[] = (int) $id;
                    break;
                case 'jeu':
                    $gameIds[] = (int) $id;
                    break;
                case 'lieu':
                    $barIds[] = (int) $id;
                    break;
            }
        }

        $events = $meetupRepository->findByFilters($cityIds, $gameIds, $barIds);

        return $this->json($events);

        
    }
}
