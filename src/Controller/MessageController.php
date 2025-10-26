<?php

namespace App\Controller;

use App\Entity\Message;
use App\Event\PostedMessageMeetupEvent;
use App\Repository\MeetupRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class MessageController extends AbstractController
{
    #[Route('/api/private/messages/{id}', name: 'app_chat', methods: ['GET'])]
    public function getChat(int $id, MessageRepository $messageRepository): Response
    {
        /** @var EntityUser $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $messages = $messageRepository->findMessagesBetweenUsers($user->getId(), $id);

    return $this->json($messages);
    }


    #[Route('/api/private/messages', name: 'app_interlocutors', methods: ['GET'])]
    public function getCurrentUserInterlocutors(MessageRepository $messageRepository, UserRepository $userRepository): Response
    {
        /** @var EntityUser $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $conversations = $messageRepository->findUserInterlocutorsByLastMessage($user->getId());

        $interlocutors = [];
        foreach ($conversations as $conversation) {
            $interlocutors[] = $userRepository->find($conversation['interlocutorId']);
        }

        return $this->json($interlocutors);
    }

    #[Route('/api/private/messages/{id}', name: 'app_message_create', methods: ['POST'])]
    public function createMessage(Request $request, EntityManagerInterface $em, int $id, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $message = new Message();
        $message->setContent($data['content']);
        $message->setSender($this->getUser());
        $message->setReceiver($userRepository->find($id));
        $message->setTime(new \DateTimeImmutable());
        $message->setIsRead(false);
        $em->persist($message);
        $em->flush();
        return $this->json(['success' => true], 201);
    }
    
    #[Route('/api/private/events/{id}/messages', name: 'app_message_update', methods: ['POST'])]
    public function updateMessage(Request $request, 
    EntityManagerInterface $em, 
    int $id, 
    MeetupRepository $meetupRepository, 
    EventDispatcherInterface $eventDispatcher): Response
    {
        $data = json_decode($request->getContent(), true);
        $message = new Message();
        $meetup = $meetupRepository->find($id);
        $message->setContent($data['content']);
        $message->setSender($this->getUser());
        $message->setMeetup($meetup);
        $message->setTime(new \DateTimeImmutable());
        $message->setIsRead(false);
        $eventDispatcher->dispatch(new PostedMessageMeetupEvent($message, $this->getUser(), $meetup),PostedMessageMeetupEvent::NAME);
        $em->persist($message);
        $em->flush();
        return $this->json(['success' => true], 201);
    }
       
    
    
}
