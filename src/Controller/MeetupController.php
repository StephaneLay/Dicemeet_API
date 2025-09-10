<?php

namespace App\Controller;

use App\Repository\MeetupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class MeetupController extends AbstractController
{
    #[Route('/api/public/events', name: 'app_meetup' , methods: ['GET'])]
    public function getAll(MeetupRepository $meetupRepository): Response
    {
        $events = $meetupRepository->findAll();

       return $this->json($events, 200, [] ,context: ['groups' => 'category:read']);
    }


#[Route('/api/public/events/search', name: 'app_meetup_search', methods: ['GET','OPTIONS'])]
public function search(Request $request, MeetupRepository $meetupRepository): Response
{
    $filters = $request->query->get('filters', '');
    $filterArray = explode(',', $filters);

    $cityNames = [];
    $gameNames = [];
    $barNames  = [];

    foreach ($filterArray as $filter) {
        [$type, $name] = explode(':', $filter);

        switch ($type) {
            case 'ville':
                $cityNames[] = $name;
                break;
            case 'jeu':
                $gameNames[] = $name;
                break;
            case 'bar':
                $barNames[] = $name;
                break;
        }
    }

    $events = $meetupRepository->findByFilters($cityNames, $gameNames, $barNames);

    return $this->json($events, 200, [], context: ['groups' => 'category:read']);
}

}