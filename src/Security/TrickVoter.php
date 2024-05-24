<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\Trick;

class TrickVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Trick;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $expectedUser = $subject->getUser();

        if( !$expectedUser || !$expectedUser instanceof User){
          return false;
        }

        /** @var User $user */
        $user = $token->getUser();

        if ($expectedUser !== $user) {
            return false;
        }

        return true;
    }
}
