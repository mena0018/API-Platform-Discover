<?php
declare(strict_types=1);

namespace App\Tests\Functional;

use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use App\Tests\TestCases\ApiPlatformTestCase;


class RatingDeleteTest extends ApiPlatformTestCase
{
    public function testAnonymousUserCantDeleteMark()
    {
        // 1. 'Arrange'
        UserFactory::createOne()->object();
        RatingFactory::createOne()->object();

        // 2. 'Act'
        self::jsonld_request('DELETE', '/api/ratings/1');

        // 3. 'Assert'
        $this->assertResponseStatusCodeSame(401);
    }

}
