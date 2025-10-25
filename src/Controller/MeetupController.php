<?php

namespace App\Controller;

use App\Entity\Meetup;
use App\Repository\MeetupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;
use App\Repository\PlaceRepository;
use App\Repository\GameRepository;


final class MeetupController extends AbstractController
{
    #[Route('/api/public/events', name: 'app_meetup' , methods: ['GET'])]
    public function getAll(MeetupRepository $meetupRepository): Response
    {
        $events = $meetupRepository->findAll();

       return $this->json($events, 200 );
    }

    #[Route('/api/private/events/search', name: 'app_meetup_search', methods: ['GET','OPTIONS'])]
    public function search(Request $request, MeetupRepository $meetupRepository): Response
    {
        $filters = $request->query->get('filters', '');
        $filterArray = explode(',', $filters);

        $cityNames = [];
        $gameNames = [];
        $placeNames  = [];

        foreach ($filterArray as $filter) {
            [$type, $name] = explode(':', $filter);

            switch($type) {
                case 'ville':
                    $cityNames[] = $name;
                    break;
                case 'jeu':
                    $gameNames[] = $name;
                    break;
                case 'lieu':
                    $placeNames[] = $name;
                    break;
            }
        }

        $events = $meetupRepository->findByFilters($cityNames, $gameNames, $placeNames);

        return $this->json($events, 200);
    }

    #[Route('/api/private/events', name: 'app_meetup_create', methods: ['POST'])]
    public function createEvent(Request $request, EntityManagerInterface $em, GameRepository $gameRepository, PlaceRepository $placeRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $meetup = new Meetup();
        $game = $gameRepository->findOneBy(['name' => $data['game']]);

        $meetup->setTime(\DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $data['date']));
        $meetup->setGame($game);
        $meetup->setPlace($placeRepository->findOneBy(['name' => $data['place']]));
        $meetup->setCapacity($game->getMaxPlayers());
        $meetup->setOwner($this->getUser());
        $meetup->addUser($this->getUser());
        $em->persist($meetup);
        $em->persist($this->getUser());
        $em->flush();

        return $this->json(['success' => true], 201);
    }

    #[Route('/api/private/events/{id}', name: 'app_meetup_detail' , methods: ['GET'])]
    public function getEventById(MeetupRepository $meetupRepository, int $id): Response
    {
        $event = $meetupRepository->find($id);

        if (!$event) {
            return $this->json(['error' => 'Event not found'], 404);
        }

        return $this->json($event, 200);
    }

    #[Route('/api/private/events/{id}', name: 'app_meetup_update', methods: ['PATCH', 'POST'])]
public function updateEvent(MeetupRepository $meetupRepository, int $id, Request $request, EntityManagerInterface $em, PlaceRepository $placeRepository, GameRepository $gameRepository): Response
{
    $event = $meetupRepository->find($id);
    if (!$event) {
        return $this->json(['error' => 'Event not found'], 404);
    }

    // On décode le JSON brut envoyé dans le corps de la requête
    $data = json_decode($request->getContent(), true);

    if (!$data) {
        return $this->json(['error' => 'Invalid JSON'], 400);
    }

    // Validation basique (optionnelle)
    if (isset($data['date'])) {
        $date = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['date']);
        if (!$date) {
            return $this->json(['error' => 'Invalid date format. Expected Y-m-d H:i:s'], 400);
        }
        $event->setTime($date);
    }

    if (isset($data['place'])) {
        $place = $placeRepository->findOneBy(['name' => $data['place']]);
        if (!$place) {
            return $this->json(['error' => 'Place not found'], 404);
        }
        $event->setPlace($place);
    }

    

    $em->persist($event);
    $em->flush();

    return $this->json($event, 200);
}


   

    #[Route('/api/private/events/{id}/users', name: 'app_meetup_users', methods: ['GET','OPTIONS'])]
    public function getEventUsers(MeetupRepository $meetupRepository, int $id): Response
    {
        $event = $meetupRepository->find($id);
        $users = $event->getUsers();
        return $this->json($users, 200);
    }

    #[Route('/api/private/events/{id}/messages', name: 'app_meetup_messages', methods: ['GET','OPTIONS'])]
    public function getEventMessages(MeetupRepository $meetupRepository, int $id): Response
    {
        $event = $meetupRepository->find($id);
        $messages = $event->getMessages();
        return $this->json($messages, 200);
    }

}