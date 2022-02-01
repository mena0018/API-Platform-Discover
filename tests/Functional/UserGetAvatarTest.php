<?php

namespace App\Tests\Functional;

use App\Factory\UserFactory;
use App\Tests\TestCases\ApiPlatformTestCase;

class UserGetAvatarTest extends ApiPlatformTestCase
{
    public function testGetAvatar()
    {
        // 1. 'Arrange'
        $user = UserFactory::createOne();
        UserFactory::repository()->assert()->count(1);


        // 2. 'Act'
        self::$client->request('GET', '/api/users/1/avatar');

        // 3. 'Assert'
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'image/png');
        $response = self::$client->getResponse();
        $this->assertEquals(stream_get_contents($user->getAvatar(), -1, 0), $response->getContent());
    }
}

