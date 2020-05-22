<?php

namespace App\EventListener;

use Nyholm\Psr7\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Trikoder\Bundle\OAuth2Bundle\Event\AuthorizationRequestResolveEvent;
use Trikoder\Bundle\OAuth2Bundle\Model\Scope;
use Twig\Environment;

final class AuthRequestResolve
{

    private CsrfTokenManagerInterface $csrfTokenManager;
    private Environment $twig;
    private Request $request;

    /**
     * AuthRequestResolve constructor.
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param Environment $twig
     * @param RequestStack $requestStack
     */
    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, Environment $twig, RequestStack $requestStack)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->twig = $twig;

        if ($requestStack->getCurrentRequest() === NULL)
            throw new \LogicException('The request is null');
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param Scope[] $scopes
     * @return array<string, string>
     */
    private function getScopes(array $scopes):array
    {
        $data = [];
        foreach ($scopes as $scope) {
            $scopeName = (string) $scope;
            $data[$scopeName] = 'grant.scopes.'.\str_replace(':', '_', $scopeName);
        }
        return $data;
    }

    public function onAuthRequestResolve(AuthorizationRequestResolveEvent $event): void
    {
        // TODO: add client name
        $error = NULL;
        if ($this->request->query->has('grant'))
        {
            $csrfToken = new CsrfToken('grant', $this->request->query->get('_csrf_token'));
            if ($this->csrfTokenManager->isTokenValid($csrfToken))
            {
                $this->csrfTokenManager->refreshToken('grant');
                $answer = $this->request->query->getAlnum('grant', 'deny');
                $event->resolveAuthorization($answer === 'accept');
                return;
            }
            $error = 'common.errors.invalid_csrf';
        }
        $hiddenFields = [
            'client_id',
            'redirect_uri',
            'scope',
            'response_type',
            'response_mode',
            'nonce',
            'state',
        ];
        $data = [
            'error' => $error,
            'scopes' => $this->getScopes($event->getScopes()),
            'hidden_fields'
        ];
        foreach ($hiddenFields as $hiddenField) {
            $data['hidden_fields'][$hiddenField] = $this->request->query->get($hiddenField);
        }
        $content = $this->twig->render('auth/grant.html.twig', $data);
        $response = new Response(200, [], $content);
        $event->setResponse($response);
    }
}