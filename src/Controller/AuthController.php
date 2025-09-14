<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    #[Route('/api/public/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? null;
        $name = $data['name'] ?? null;
        $password = $data['password'] ?? null;

        // Vérif email déjà existant
        if ($userRepository->findOneBy(['email' => $email])) {
            return $this->json(['error' => 'Email déjà utilisé'], 400);
        }

        // Vérif nom déjà existant
        if ($userRepository->findOneBy(['name' => $name])) {
            return $this->json(['error' => 'Nom déjà utilisé'], 400);
        }

        // Création utilisateur
        $user = new User();
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($passwordHasher->hashPassword($user, $password))
             ->setCreationDate(new \DateTimeImmutable())
             ->setRoles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();

        return $this->json(['success' => true], 201);
    }
}

