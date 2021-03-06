<?php


namespace App\Tests\Controller\Api;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiTest extends WebTestCase
{
    protected ?KernelBrowser $client = NULL;

    protected function setUp():void
    {
        $this->client = self::createClient();
    }

    abstract protected function getEndpoint():string;
    abstract protected function getMethod():string;

    /**
     * @param string $email
     * @param string[] $scopes
     * @throws \Exception
     */
    public function authenticateAs(string $email, array $scopes):void
    {
        $data = [
            'grant_type' => 'password',
            'username' => $email,
            'password' => $email,
            'scope' => implode(' ', $scopes),
            'client_id' => 'client_id',
            'client_secret' => 'client_secret',
        ];
        $this->client->request(
            'POST',
            '/auth/token',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        if ($this->client->getResponse()->getStatusCode() !== 200)
        {
            $message = $this->client->getResponse()->getContent();
            throw new \Exception("Failed to get the authorization token: ".$message);
        }
        $response = json_decode($this->client->getResponse()->getContent());
        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer '.$response->access_token);
    }

    protected function deauthenticate():void
    {
        if ($this->client === NULL)
            throw new \LogicException("Calling deauthenticate on a NULL client");
        $this->client->setServerParameter('HTTP_AUTHORIZATION', '');
    }

    protected function getDoctrine():EntityManagerInterface
    {
        return $this->client->getContainer()->get('doctrine');
    }

    /**
     * @param string[] $attributes
     * @param \stdClass $obj
     */
    protected function assertObjectOnlyHasAttributes(array $attributes, \stdClass $obj):void
    {
        foreach ($attributes as $attribute)
            $this->assertObjectHasAttribute($attribute, $obj);
        $properties = get_object_vars($obj);
        foreach ($properties as $key => $value)
            $this->assertContains($key, $attributes, "Object shouldn't contain '$key' attribute");
    }

}