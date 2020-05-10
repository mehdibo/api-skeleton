<?php


namespace App\Controller\Api;

use App\Responses\EntityResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class ApiController extends AbstractController
{

    private SerializerInterface $serializer;
    private TokenStorageInterface $tokenStorage;

    public function __construct(SerializerInterface $serializer, TokenStorageInterface $tokenStorage)
    {
        $this->serializer = $serializer;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Used to get the context for the serializer component to serialize entities
     * @return string[]|string[][]
     */
    abstract protected function getContext():array;

    protected function entityResponse(object $type):EntityResponse
    {
        $json = $this->serializer->serialize($type, 'json', $this->getContext());
        return new EntityResponse($json);
    }

}