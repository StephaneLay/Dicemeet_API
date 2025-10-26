<?php

namespace App\EventSubscriber;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MeetupUpdateSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }
    public function onMeetupDateUpdated($event): void
    {
        $notifiedUsers = $event->getMeetup()->getUsers();
        foreach ($notifiedUsers as $user) {
            if ($user !== $event->getMeetup()->getOwner()) {
                $notification = new Notification();
                $notification->setUser($user);
                $notification->setContent(
                    "La date de l'evenement n°" . $event->getMeetup()->getId() . " de'" . $event->getMeetup()->getGame()->getName() . "' a été modifiée");
                $notification->setCreatedAt(new \DateTimeImmutable());
                $user->addNotification($notification);
                $this->em->persist($notification);
                $this->em->persist($user);
            }
        }
        $this->em->flush();
    }

    public function onMeetupPlaceUpdated($event): void
    {
        $notifiedUsers = $event->getMeetup()->getUsers();
        foreach ($notifiedUsers as $user) {
            if ($user !== $event->getMeetup()->getOwner()) {
                $notification = new Notification();
                $notification->setUser($user);
                $notification->setContent(
                    "Le lieu de l'evenement n°" . $event->getMeetup()->getId() . " de'" . $event->getMeetup()->getGame()->getName() . "' a été modifié");
                $notification->setCreatedAt(new \DateTimeImmutable());
                $user->addNotification($notification);
                $this->em->persist($notification);
                $this->em->persist($user);
            }
        }
        $this->em->flush();
    }

    public function onMeetupMessagePosted($event): void
    {
        $notifiedUsers = $event->getMeetup()->getUsers();
        foreach ($notifiedUsers as $user) {
            if ($user !== $event->getUser()) {
                $notification = new Notification();
                $notification->setUser($user);
                $notification->setContent(
                    "Un nouveau message a été posté dans l'evenement n°" . $event->getMeetup()->getId() . " de'" . $event->getMeetup()->getGame()->getName() . "' par " . $event->getUser()->getName());
                $notification->setCreatedAt(new \DateTimeImmutable());
                $user->addNotification($notification);
                $this->em->persist($notification);
                $this->em->persist($user);
            }
        }
        $this->em->flush();
        
    }

    public function onMeetupUserJoined($event): void
    {
        $owner = $event->getMeetup()->getOwner();
        $notification = new Notification();
        $notification->setUser($owner);
        $notification->setContent(
            "Un nouvel utilisateur, " . $event->getUser()->getName() . ", a rejoint l'evenement n°" . $event->getMeetup()->getId() . " de'" . $event->getMeetup()->getGame()->getName() . "'");
        $notification->setCreatedAt(new \DateTimeImmutable());
        $owner->addNotification($notification);
        $this->em->persist($notification);
        $this->em->persist($owner);
        $this->em->flush();
    }

    public function onMeetupDeleted($event): void
    {
        $notifiedUsers = $event->getMeetup()->getUsers();
        foreach ($notifiedUsers as $user) {
            if ($user !== $event->getMeetup()->getOwner()) {
                $notification = new Notification();
                $notification->setUser($user);
                $notification->setContent(
                    "L'evenement n°" . $event->getMeetup()->getId() . " de'" . $event->getMeetup()->getGame()->getName() . "' a été supprimé");
                $notification->setCreatedAt(new \DateTimeImmutable());
                $user->addNotification($notification);
                $this->em->persist($notification);
                $this->em->persist($user);
            }
        }
        $this->em->flush();
    }
    public function onMeetupUserKicked($event): void
    {
        $kickedUser = $event->getUser();
        $notification = new Notification();
        $notification->setUser($kickedUser);
        $notification->setContent(
            "Vous ne faites plus partie de l'evenement n°" . $event->getMeetup()->getId() . " de'" . $event->getMeetup()->getGame()->getName() . "'");
        $notification->setCreatedAt(new \DateTimeImmutable());
        $kickedUser->addNotification($notification);
        $this->em->persist($notification);
        $this->em->persist($kickedUser);
        $this->em->flush();
    }
    public static function getSubscribedEvents(): array
    {
        return [
            'meetup.date.updated' => 'onMeetupDateUpdated',
            'meetup.place.updated' => 'onMeetupPlaceUpdated',
            'meetup.message.posted' => 'onMeetupMessagePosted',
            'meetup.user.joined' => 'onMeetupUserJoined',
            'meetup.deleted' => 'onMeetupDeleted',
            'meetup.user.kicked' => 'onMeetupUserKicked',
        ];
    }

   
}
