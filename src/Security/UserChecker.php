<?php

namespace App\Security;

use DateTime;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserChecker implements UserCheckerInterface{

    /**
     * @param User $user
     */

    public function checkPreAuth(UserInterface $user): void{
        // se non e nullo vuol dire che l'utente e stato bannato.
        if (null === $user->getBannedUntil()) {
            return;
        }

        //controlliamo se e stato bannato nel passato o nel futuro
        $now = new DateTime();
        if ($now < $user->getBannedUntil()) {
            throw new AccessDeniedHttpException('Sei stato bannato');
        }
    }

    /**
     * @param User $user
     */

    public function checkPostAuth(UserInterface $user): void{
    
    }
}