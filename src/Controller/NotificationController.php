<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class NotificationController extends AbstractController
{
    #[Route('/api/private/notifications/{id}', name: 'app_notification_delete', methods: ['DELETE'])]
    public function deleteNotification(int $id, NotificationRepository $notificationRepository, EntityManagerInterface $em): Response
    {
        $notification = $notificationRepository->find($id);
        if (!$notification) {
            return $this->json(['error' => 'Notification non trouvée'], 404);
        }
        $em->remove($notification);
        $em->flush();
        return $this->json(['success' => true], 200);
    }
}
