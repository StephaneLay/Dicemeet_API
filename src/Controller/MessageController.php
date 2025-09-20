<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MessageController extends AbstractController
{
    #[Route('api/private/messages/{id}', name: 'app_chat', methods: ['GET'])]
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


    #[Route('api/private/messages', name: 'app_interlocutors', methods: ['GET'])]
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

    
}
