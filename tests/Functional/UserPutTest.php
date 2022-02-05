<?php

namespace App\Tests\Functional;

use App\Factory\UserFactory;
use App\Tests\TestCases\ApiPlatformTestCase;

class UserPutTest extends ApiPlatformTestCase
{
    protected static function getProperties(): array
    {
        return [
            'id',
            'login',
            'firstname',
            'lastname',
            'mail',
        ];
    }

    public function testAnonymousUserForbiddenToPutUser()
    {
        // 1. 'Arrange'
        UserFactory::createOne();

        // 2. 'Act'
        self::jsonld_request('PUT', '/api/users/1');

        // 3. 'Assert'
        $this->assertResponseStatusCodeSame(401);
    }

    public function testAuthenticatedUserForbiddenToPutOtherUser()
    {
        // 1. 'Arrange'
        $user = UserFactory::createOne()->object();
        UserFactory::createOne();
        self::$client->loginUser($user);

        // 2. 'Act'
        self::jsonld_request('PUT', '/api/users/2');

        // 3. 'Assert'
        $this->assertResponseStatusCodeSame(403);
    }

    public function testAuthenticatedUserCanPutOwnData()
    {
        // 1. 'Arrange'
        $dataInit = ['firstname' => 'firstname1', 'lastname' => 'lastname1'];
        $user = UserFactory::createOne($dataInit)->object();
        self::$client->loginUser($user);

        // 2. 'Act'
        $dataPut = ['lastname' => 'lastname2'];
        $parameters = [
            'contentType' => 'application/json',
            'content' => json_encode($dataPut),
        ];
        self::jsonld_request('PUT', '/api/users/1', $parameters);

        // 3. 'Assert'
        $json = self::lastJsonResponseWithAsserts(ApiPlatformTestCase::ENTITY, 'User');
        self::assertJsonIsAnItem($json, self::getProperties(), array_merge($dataInit, $dataPut));
    }

    public function testAuthenticatedUserCanChangeHisPassword()
    {
        // 1. 'Arrange'
        $data = ['login' => 'user1', 'firstname' => 'firstname1', 'lastname' => 'lastname1'];
        $user = UserFactory::createOne($data)->object();
        self::$client->loginUser($user);

        // 2. 'Act'
        self::jsonld_request('PUT', '/api/users/1', [
            'contentType' => 'application/json',
            'content' => json_encode(['password' => 'new password']),
        ]);
        self::$client->request('GET', '/logout');
        self::$client->request('GET', '/login');
        $crawler = self::$client->submitForm('Authentification', [ 'login' => 'user1', 'password' => 'new password' ]);

        // 3. 'Assert'
        self::assertSame('Redirecting to /api/docs', $crawler->filter('title')->text());
    }
}

