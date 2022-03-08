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

    /**
     * Si aucun utilisateur n'est fourni mais qu'un utilisateur est connecté,
     * l'utilisateur connecté est utilisé comme utilisateur de la note.
     */
    public function prePersist(Rating $rating)
    {
        $currentUser =  $this->security->getUser();
        if (!$rating->getUser() || $currentUser !== null)
        {
            /* @var $currentUser User */
            $rating->setUser($currentUser);
        }
    }
}

