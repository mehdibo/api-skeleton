<?php


namespace App\Tests\Controller\Api;


use Symfony\Component\HttpFoundation\Response;

abstract class ProtectedRouteTest extends ApiTest
{

    /**
     * @return string[]
     */
    abstract protected function getRequiredScopes():array;
    abstract protected function getAllowedUserEmail():string;
    abstract protected function getNotAllowedUserEmail():?string;

    public function testCantViewRouteWithoutAuthentication():void
    {
        $this->client->request(
            $this->getMethod(),
            $this->getEndpoint()
        );
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testCantViewRouteWithoutRequiredScopes():void
    {
        $this->authenticateAs($this->getAllowedUserEmail(), ['testing']);
        $this->client->request(
            $this->getMethod(),
            $this->getEndpoint()
        );
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    public function testCantViewRouteWithoutRequiredRole():void
    {
        if ($this->getNotAllowedUserEmail() === NULL)
        {
            $this->assertTrue(TRUE);
            return;
        }
        $this->authenticateAs($this->getNotAllowedUserEmail(), $this->getRequiredScopes());
        $this->client->request(
            $this->getMethod(),
            $this->getEndpoint()
        );
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    public function testCanViewRouteWithRequiredRoleAndScopes():void
    {
        $this->authenticateAs($this->getAllowedUserEmail(), $this->getRequiredScopes());
        $this->client->request(
            $this->getMethod(),
            $this->getEndpoint()
        );
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

}