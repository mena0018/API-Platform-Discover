<?php
declare(strict_types=1);

namespace App\Tests\Functional;

use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use App\Tests\TestCases\ApiPlatformTestCase;

class RatingPutTest extends ApiPlatformTestCase
{
    public function testAnonymousUserCantPutRating()
    {
        // 1. 'Arrange'
        RatingFactory::createOne();

        // 2. 'Act'
        self::jsonld_request('PUT', '/api/ratings/1');

        // 3. 'Assert'
        $this->assertResponseStatusCodeSame(401);
    }

    public function testAuthenticatedUserCantPutOtherUser()
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
}