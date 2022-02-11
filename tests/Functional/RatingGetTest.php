<?php

namespace App\Tests\Functional;

use App\Factory\BookmarkFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use App\Tests\TestCases\ApiPlatformTestCase;

class RatingGetTest extends ApiPlatformTestCase
{
    protected static function getProperties(): array
    {
        return [
            'id',
            'user',
            'bookmark',
            'value',
        ];
    }

    public function testGetRatingDetail()
    {
        // 1. 'Arrange'
        $user = UserFactory::createOne()->object();
        $bookmark = BookmarkFactory::createOne()->object();
        RatingFactory::createOne(['user' => $user, 'bookmark' => $bookmark, 'value' => 5]);

        // 2. 'Act'
        self::jsonld_request('GET', '/api/ratings/1');

        // 3. 'Assert'
        $json = self::lastJsonResponseWithAsserts(ApiPlatformTestCase::ENTITY, 'Rating');
        self::assertJsonIsAnItem($json, self::getProperties(), [
            'user' => '/api/users/1',
            'bookmark' => '/api/bookmarks/1',
            'value' => 5
        ]);
    }

    public function testGetCollection()
    {
        // 1. 'Arrange'
        UserFactory::createMany(3);
        BookmarkFactory::createMany(3);
        RatingFactory::createMany(3);

        // 2. 'Act'
        self::jsonld_request('GET', '/api/ratings');

        // 3. 'Assert'
        $json = self::lastJsonResponseWithAsserts(ApiPlatformTestCase::COLLECTION, 'Rating');
        self::assertSame(3, $json['hydra:totalItems']);
        self::assertCount(3, $json['hydra:member']);
    }
}

