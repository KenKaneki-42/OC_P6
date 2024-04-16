<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is verified, the user may be notified
        if (!$user->isVerified()) {
            throw new CustomUserMessageAuthenticationException('Veuillez vérifier votre compte via l\'e-mail envoyé avant de pouvoir vous connecter. Vérifiez vos spams.');
        }
    }
}
