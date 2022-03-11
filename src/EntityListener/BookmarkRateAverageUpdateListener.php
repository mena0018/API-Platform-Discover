<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Bookmark;
use App\Entity\Rating;
use Doctrine\ORM\EntityManagerInterface;


class BookmarkRateAverageUpdateListener
{
    private EntityManagerInterface $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function updateRateAverage(Rating $rating)
    {
        $repo = $this->entityManagerInterface->getRepository(Bookmark::class);
        $repo->updateRateAverage($rating->getBookmark()->getId());
    }

    public function postPersist(Rating $rating){

    }
    public function postRemove(Rating $rating){

    }
    public function postUpdate(Rating $rating){

    }

}
