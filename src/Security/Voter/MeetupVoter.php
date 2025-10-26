<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;

use App\Entity\Meetup;


final class MeetupVoter extends Voter
{
    public function __construct(private Security $security){}

    public const UPDATE = 'MEETUP_UPDATE';
    public const DELETE = 'MEETUP_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        
        return in_array($attribute, [self::UPDATE, self::DELETE])
            && $subject instanceof Meetup;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface || $subject->getOwner() !== $user) {
            return false;
        }

        if($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        if($subject->getOwner() === $user) {
            return true;
        }
        return false;
    }
}
