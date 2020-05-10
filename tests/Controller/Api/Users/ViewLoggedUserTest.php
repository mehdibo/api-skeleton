<?php


namespace App\Tests\Controller\Api\Users;


use App\DataFixtures\UserFixtures;
use App\Tests\Controller\Api\ProtectedRouteTest;
use Symfony\Component\HttpFoundation\Response;

class ViewLoggedUserTest extends ProtectedRouteTest
{

    protected function getEndpoint(): string
    {
        return "/api/users/me";
    }

    protected function getMethod(): string
    {
        return "GET";
    }

    /**
     * @return string[]
     */
    protected function getRequiredScopes(): array
    {
        return ['profile:read'];
    }

    protected function getAllowedUserEmail(): string
    {
        return UserFixtures::DEFINED_USERS[UserFixtures::NORMAL_USER_REFERENCE]['email'];
    }

    protected function getNotAllowedUserEmail(): ?string
    {
        return NULL;
    }

    public function testUserCanViewHisData():void
    {
        $this->authenticateAs($this->getAllowedUserEmail(), $this->getRequiredScopes());
        $this->client->request(
            $this->getMethod(),
            $this->getEndpoint()
        );
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $user = json_decode($this->client->getResponse()->getContent());
        $this->assertObjectOnlyHasAttributes([
            'id',
            'email',
            'roles',
            'settings',
            'lastLogin',
            'createdAt',
        ], $user);
        $this->assertIsInt($user->id);
        $this->assertIsString($user->email);
        $this->assertIsArray($user->roles);
        $this->assertIsObject($user->settings);
        $this->assertIsString($user->createdAt);

    }

}