<?php
declare(strict_types=1);

namespace App\Tests\Functional;

use App\Factory\UserFactory;
use App\Factory\RatingFactory;
use App\Tests\TestCases\ApiPlatformTestCase;

class RatingPatchTest extends ApiPlatformTestCase
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

    public function testAnonymousUserCantPatchRating()
    {
        // 1. 'Arrange'
        RatingFactory::createOne();

        // 2. 'Act'
        self::jsonld_request('PATCH', '/api/ratings/1');

        // 3. 'Assert'
        $this->assertResponseStatusCodeSame(401);
    }

    public function testAuthenticatedUserCantPatchOtherUser()
    {
        // 1. 'Arrange'
        $user = UserFactory::createOne()->object();
        UserFactory::createOne();
        self::$client->loginUser($user);

        // 2. 'Act'
        self::jsonld_request('PATCH', '/api/users/2');

        // 3. 'Assert'
        $this->assertResponseStatusCodeSame(403);
    }

    public function testAuthenticatedUserCanPatchOwnData()
    {
        // 1. 'Arrange'
        $user = UserFactory::createOne()->object();
        self::$client->loginUser($user);

        $dataInit = [
            'user' => $user,
            'value' => 5
        ];
        RatingFactory::createOne($dataInit);

        // 2. 'Act'
        $dataPatch = [
            'value' => 7
        ];
        $parameters = [
            'contentType' => 'application/merge-patch+json',
            'content' => json_encode($dataPatch),
        ];
        self::jsonld_request('PATCH', '/api/ratings/1', $parameters);

        // 3. 'Assert'
        $json = self::lastJsonResponseWithAsserts(ApiPlatformTestCase::ENTITY, 'Rating', '/api/ratings/1');
        self::assertJsonIsAnItem($json, self::getProperties(),  $dataPatch);
    }
}