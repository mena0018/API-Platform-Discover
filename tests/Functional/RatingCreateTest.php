<?php

namespace App\Tests\Functional;

use App\Factory\BookmarkFactory;
use App\Factory\UserFactory;
use App\Tests\TestCases\ApiPlatformTestCase;

class RatingCreateTest extends ApiPlatformTestCase
{
    protected static function getProperties(): array
    {
        return [
            'id',
            'bookmark',
            'user',
            'value',
        ];
    }

    public function testAnonymousUserCantCreateNote()
    {
        // 1. 'Arrange'
        UserFactory::createOne();

        // 2. 'Act'
        self::jsonld_request('POST', '/api/ratings');

        // 3. 'Assert'
        $this->assertResponseStatusCodeSame(401);
    }

    public function testAuthenticatedUserCanCreateNote()
    {
        $data = [
            'bookmark' => '/api/bookmarks/1',
            'user' => '/api/users/1',
            'value' => 5
        ];

        // 1. 'Arrange'
        $user = UserFactory::createOne()->object();
        BookmarkFactory::createOne()->object();
        self::$client->loginUser($user);

        // 2. 'Act'
        $parameters = [
            'contentType' => 'application/ld+json',
            'content' => json_encode($data),
        ];
        self::jsonld_request('POST', '/api/ratings', $parameters);

        // 3. 'Assert'
        $json = self::lastJsonResponseWithAsserts(ApiPlatformTestCase::ENTITY, 'Rating', '/api/ratings/1');
        self::assertJsonIsAnItem($json, self::getProperties(), $data);
    }
}
