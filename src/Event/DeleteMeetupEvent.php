<?php

namespace App\Event;

use App\Entity\Meetup;
class DeleteMeetupEvent
{
    public const NAME = 'meetup.deleted';
    public function __construct(public Meetup $meetup)
    {
    }
    public function getMeetup(): Meetup
    {
        return $this->meetup;
    }
}