<?php

namespace App\Tests\Functional;

use App\Factory\UserFactory;
use App\Tests\TestCases\ApiPlatformTestCase;

class RatingCreateTest extends ApiPlatformTestCase
{
    public function testAnonymousUserCantCreateNote()
    {
        // 1. 'Arrange'
        UserFactory::createOne();

        // 2. 'Act'
        self::jsonld_request('POST', '/api/ratings');

        // 3. 'Assert'
        $this->assertResponseStatusCodeSame(401);
    }
}
