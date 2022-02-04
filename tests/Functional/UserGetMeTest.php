<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Tests\TestCases\ApiPlatformTestCase;

class UserGetMeTest extends ApiPlatformTestCase
{
    public function testAnonymousMeIsUnauthorized()
    {
        // 1. 'Arrange'
        UserFactory::createOne();

        // 2. 'Act'
        self::jsonld_request('GET', '/api/me');

        // 3. 'Assert'
        $this->assertResponseStatusCodeSame(401);
    }

    public function testAuthenticatedUserOnMeGetData()
    {
        // 1. 'Arrange'
        UserFactory::createMany(2);
        $properties = ['id', 'login', 'firstname', 'lastname', 'mail'];

        $repository = UserFactory::repository();
        /** @var User $user */
        $user = $repository->find(2)->object();
        self::$client->loginUser($user);

        // 2. 'Act'
        self::jsonld_request('GET', '/api/me');

        // 3. 'Assert'
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $response = self::$client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        self::assertJsonIsAnItem($responseData, $properties);
        $this->assertEquals($user->getLogin(), $responseData['login']);
    }
}
