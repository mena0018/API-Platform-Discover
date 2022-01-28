<?php
declare(strict_types=1);

namespace App\Tests\Functional;

use App\Factory\UserFactory;
use App\Tests\TestCases\ApiPlatformTestCase;

class UserGetTest extends ApiPlatformTestCase
{
    protected static function getProperties(): array
    {
        return [
            'id',
            'login',
            'firstname',
            'lastname',
        ];
    }

    public function testAnonymousUserGetSimpleUserElement()
    {
        // 1. 'Arrange'
        $data = ['login' => 'user1', 'firstname' => 'firstname1', 'lastname' => 'lastname1'];
        UserFactory::createOne($data);

        // 2. 'Act'
        self::jsonld_request('GET', '/api/users/1');

        // 3. 'Assert'
        $json = self::lastJsonResponseWithAsserts(ApiPlatformTestCase::ENTITY, 'User');
        self::assertJsonIsAnItem($json, self::getProperties(), $data);
    }

    public function testAuthenticatedUserGetSimpleUserElementForOthers()
    {
        // 1. 'Arrange'
        $data = ['login' => 'user1', 'firstname' => 'firstname1', 'lastname' => 'lastname1'];
        $user = UserFactory::createOne()->object();
        UserFactory::createOne($data);
        self::$client->loginUser($user);

        // 2. 'Act'
        self::jsonld_request('GET', '/api/users/2');

        // 3. 'Assert'
        $json = self::lastJsonResponseWithAsserts(ApiPlatformTestCase::ENTITY, 'User');
        self::assertJsonIsAnItem($json, self::getProperties(), $data);
    }
}