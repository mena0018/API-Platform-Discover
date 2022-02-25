<?php
declare(strict_types=1);

namespace App\Tests\Functional;

use App\Factory\RatingFactory;
use App\Tests\TestCases\ApiPlatformTestCase;
use App\Factory\UserFactory;

class RatingPatchTest extends ApiPlatformTestCase
{
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
}