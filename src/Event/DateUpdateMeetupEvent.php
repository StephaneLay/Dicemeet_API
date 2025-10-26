<?php

namespace App\Event;

use App\Entity\Meetup;

class DateUpdateMeetupEvent
{
    public const NAME = 'meetup.date.updated';
    public function __construct(public Meetup $meetup)
    {
    }
    public function getMeetup(): Meetup
    {
        return $this->meetup;
    }
}