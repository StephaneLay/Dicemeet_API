<?php

namespace App\Event;

use App\Entity\Message;
use App\Entity\User;
use App\Entity\Meetup;
class PostedMessageMeetupEvent
{
    public const NAME = 'meetup.message.posted';
    public function __construct(public Message $message, public User $user, public Meetup $meetup)
    {
    }
    public function getMessage(): Message
    {
        return $this->message;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function getMeetup(): Meetup
    {
        return $this->meetup;
    }
}