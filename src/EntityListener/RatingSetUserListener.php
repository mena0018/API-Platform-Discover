<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Rating;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class RatingSetUserListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Rating $rating)
    {
    }
}

