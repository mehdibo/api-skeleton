<?php


namespace App\Responses;


use Symfony\Component\HttpFoundation\JsonResponse;

class EntityResponse extends JsonResponse
{

    /**
     * EntityResponse constructor.
     * @param string $json
     * @param int $status
     * @param string[] $headers
     */
    public function __construct(string $json, int $status = 200, array $headers = [])
    {
        parent::__construct($json, $status, $headers, TRUE);
    }

}