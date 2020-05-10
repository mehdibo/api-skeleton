<?php

namespace App\EventListener;

use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Trikoder\Bundle\OAuth2Bundle\Event\UserResolveEvent;

final class UserResolveListener
{

    private UserProviderInterface $userProvider;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * @param UserProviderInterface $userProvider
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserProviderInterface $userProvider, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userProvider = $userProvider;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param UserResolveEvent $event
     * @throws OAuthServerException
     */
    public function onUserResolve(UserResolveEvent $event): void
    {
        $hint = 'Invalid username or password';
        try {
            $user = $this->userProvider->loadUserByUsername($event->getUsername());
        } catch (UsernameNotFoundException $e)
        {
            throw OAuthServerException::invalidGrant($hint);
        }

        if (!$this->userPasswordEncoder->isPasswordValid($user, $event->getPassword()))
            throw OAuthServerException::invalidGrant($hint);

        $event->setUser($user);
    }
}