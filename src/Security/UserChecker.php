<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\HttpFoundation\Response;
class UserChecker implements UserCheckerInterface 
{
    public function checkPreAuth(UserInterface $user )
    {

        if ($user->getisBanned()) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Sorry, your user account has been suspended');

        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        /*
        */
    }
}