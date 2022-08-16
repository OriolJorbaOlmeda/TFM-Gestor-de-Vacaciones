<?php

use App\Entity\User;
use App\Modules\User\Infrastucture\UserRepository;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class UserTest extends TestCase
{

/*
    public function testPassword(): void
    {
        $user= new User();
        $user->setPassword('pepito');
        $employeeRepository = $this->createMock(UserRepository::class);
       $user2= $employeeRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $this->assertEquals('pepito', $user2->method());
    }
    public function testName(): void
    {
        $user= new User();
        $user->setPassword('pepito');
        $employeeRepository = $this->createMock(UserRepository::class);
        $employeeRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $this->assertEquals('pepito', $user->getPassword());
    }

    public function testSave(): void
    {
        $user= new User();
        $user->setName('pepito2');
        $employeeRepository = $this->createMock(UserRepository::class);
        $employeeRepository->add($user);

        $user2=$employeeRepository->findOneBy(array('name','pepito2'));

        $this->assertEquals('pepito2', $user2->getName());
    }*/
    public function testApi(): void
    {

        /*$client = new Client([
            'base_uri' => 'http://localhost:1000/'
        ]);
        $response = $client->request('GET', '/login');
        $this->assertEquals(200, $response->getStatusCode());
        echo $response->getStatusCode();*/

        // Create a mock and queue two responses.
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://localhost:1000/login',
            // You can set any number of default request options.
        ]);
// The first request is intercepted with the first response.
        $response = $client->request('GET', '/');
        $this->assertEquals(200, $response->getStatusCode());
    }

}