<?php

namespace App\Event;

use App\Entity\Meetup;

class PlaceUpdateMeetupEvent
{
    public const NAME = 'meetup.place.updated';
    public function __construct(public Meetup $meetup)
    {
    }
    public function getMeetup(): Meetup
    {
        return $this->meetup;
    }
}