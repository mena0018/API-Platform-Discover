<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetMeController extends AbstractController
{
   public function __invoke(): User
   {
       // Récupère l'utilisateur courant depuis les données de session.
       /** @var User $currentUser */
       $currentUser = $this->getUser();
        if ($currentUser === null) {
            throw $this->createNotFoundException('Aucun utilisateur courant depuis les données de session');
        }
        return $currentUser;
   }
}
