<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GetAvatarController extends AbstractController
{
    public function __invoke(User $data): Response
    {
        $avatar = stream_get_contents($data->getAvatar(),null,0);
        return new Response(
            $avatar,
            Response::HTTP_OK,
            ['content-type' => 'image/png']
        );
    }
}
