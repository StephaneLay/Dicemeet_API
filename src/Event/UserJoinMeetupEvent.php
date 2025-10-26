<?php

namespace App\Event;

use App\Entity\Meetup;
use App\Entity\User;

class UserJoinMeetupEvent
{
    public const NAME = 'meetup.user.joined';
    public function __construct(public Meetup $meetup, public User $user)
    {
    }
    public function getMeetup(): Meetup
    {
        return $this->meetup;
    }
    public function getUser(): User
    {
        return $this->user;
    }
}