<?php

namespace App\Controller;

use App\Entity\User as EntityUser;
use App\Repository\CityRepository;
use App\Repository\PersonalityTraitRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\City;
use Proxies\__CG__\App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class UserController extends AbstractController
{
    #[Route('/api/private/users/me', methods: ['GET'])]
    public function getCurrentUser(Security $security): Response
    {
        $user = $security->getUser();


        if (!$user) {
            return $this->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        return $this->json($user, 200,[], ['add_favorites' => true]);
    }

    #[Route('/api/private/users/me', name: 'user_update', methods: ['PATCH', 'POST'])]
    public function updateUser(
        Request $request,
        CityRepository $cityRepository,
        PersonalityTraitRepository $personalityTraitRepository,
        EntityManagerInterface $em
    ): Response {
        /** @var EntityUser $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non connecté'], 401);
        }

        $contentType = $request->headers->get('Content-Type') ?? '';

        if (strpos($contentType, 'multipart/form-data') !== false) {
            // reçu via FormData (POST)
            $data = $request->request->all();
            $files = $request->files;
        } else {
            // JSON
            $data = json_decode($request->getContent(), true) ?? [];
            $files = $request->files;
        }


        // --- Name ---
        if (isset($data['name'])) {
            $user->setName(trim($data['name']));
        }

        // --- Bio ---
        if (isset($data['bio'])) {
            $user->setBio($data['bio']);
        }

        // --- City ---
        if (isset($data['city'])) {
            $cityName = trim($data['city']);
            $city = $cityRepository->findOneBy(['name' => $cityName]);
            if (!$city) {
                $city = new \App\Entity\City();
                $city->setName($cityName);
                $em->persist($city);
            }
            $user->setCity($city);
        }

        // --- Traits ---
        if (isset($data['traits'])) {
            $traitsArray = is_string($data['traits']) ? json_decode($data['traits'], true) : $data['traits'];
            if (is_array($traitsArray)) {
                // clear old
                foreach ($user->getPersonalityTraits() as $t) {
                    $user->removePersonalityTrait($t);
                }
                // add new
                foreach ($traitsArray as $inputTrait) {
                    $id = $inputTrait['id'] ?? $inputTrait;
                    $trait = $personalityTraitRepository->find($id);
                    if ($trait) {
                        $user->addPersonalityTrait($trait);
                    }
                }
            }
        }

        // --- Image ---
        if ($files->has('img')) {
            $file = $files->get('img');
            if ($file && $file->isValid()) {
                $extension = $file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'bin';
                $fileName = uniqid() . '.' . $extension;

                $file->move(
                    $this->getParameter('uploads_directory'),
                    $fileName
                );

                $user->setImgUrl($fileName);
            }
        }

        $em->persist($user);
        $em->flush();

        return $this->json($user, 200);
    }


    #[Route('/api/private/users/{id}', methods: ['GET'])]
    public function getUserInfo(UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        return $this->json($user, 200, [], ['add_favorites' => true]);
    }


}


