<?php


namespace App\Controller\Api\Users;


use App\Controller\Api\ApiController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

abstract class UserController extends ApiController
{

    /**
     * The default context for the user entity
     * @return string[][]
     */
    protected function getContext():array
    {
        return [
            AbstractNormalizer::ATTRIBUTES => [
                'id',
            ]
        ];
    }

}