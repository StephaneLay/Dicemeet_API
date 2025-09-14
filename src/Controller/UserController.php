<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Proxies\__CG__\App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class UserController extends AbstractController
{
   #[Route('/api/private/users/{id}', methods: ['GET'])]
   public function getUserInfo(UserRepository $userRepository, int $id): Response
   {
       $user = $userRepository->find($id);

       if (!$user) {
           return $this->json(['error' => 'Utilisateur non trouvé'], 404);
       }
       
       return $this->json($user);
   }
}

 
