<?php

namespace App\Controller;

use App\Repository\MeetupRepository;
use App\Repository\UserRepository;
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

       return $this->json($events, 200 );
    }

    #[Route('/api/public/events/{id}', name: 'app_meetup_detail' , methods: ['GET'])]
    public function getEventById(MeetupRepository $meetupRepository, int $id): Response
    {
        $event = $meetupRepository->find($id);

        if (!$event) {
            return $this->json(['error' => 'Event not found'], 404);
        }

        return $this->json($event, 200);
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

            switch($type) {
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

        return $this->json($events, 200);
    }

    #[Route('/api/public/events/users/{userId}', name: 'app_meetup_search', methods: ['GET','OPTIONS'])]
    public function getUserEvents(UserRepository $userRepository,MeetupRepository $meetupRepository, int $userId): Response
    {
        $user = $userRepository->findOneBy(['id'=> $userId]);
        $events = $user->getMeetups();

        return $this->json($events, 200);
    }


}