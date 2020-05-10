<?php

namespace App\EventListener;

use Trikoder\Bundle\OAuth2Bundle\Event\AuthorizationRequestResolveEvent;

final class AuthRequestResolve
{

    public function onAuthRequestResolve(AuthorizationRequestResolveEvent $event): void
    {
        // TODO: if using 3rd party clients, make sure the user approves access
        $event->resolveAuthorization(TRUE);
    }
}