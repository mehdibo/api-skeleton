<?php


namespace App\Controller\Api\Users;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ViewLoggedUser extends UserController
{

    /**
     * @return string[][]
     */
    protected function getContext(): array
    {
        $defaultContext = parent::getContext();
        $context = array_merge($defaultContext[AbstractNormalizer::ATTRIBUTES], ['email', 'roles', 'settings', 'lastLogin', 'createdAt']);
        return [
            AbstractNormalizer::ATTRIBUTES => $context,
        ];
    }

    /**
     * @Route("/users/me", name="user_viewLoggedUser", methods={"GET"})
     * @IsGranted("ROLE_OAUTH2_PROFILE:READ")
     * @return JsonResponse
     */
    public function viewUser():JsonResponse
    {
        return $this->entityResponse($this->getUser());
    }
}