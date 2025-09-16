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

        return $this->json($user, 200);
    }

    #[Route('/api/private/users/me', name: 'user_update', methods: ['PATCH'])]
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

        // --- Récupérer les données ---
        $data = $request->request->all(); // tous les champs sauf fichiers
        $files = $request->files;

        // --- Bio ---
        if (isset($data['bio'])) {
            $user->setBio($data['bio']);
        }

        // --- City ---
        if (isset($data['city'])) {
            $cityName = trim($data['city']);
            $city = $cityRepository->findOneBy(['name' => $cityName]);
            if (!$city) {
                $city = new City();
                $city->setName($cityName);
                $em->persist($city);
            }
            $user->setCity($city);
        }

        // --- Traits ---
        if (isset($data['traits'])) {
            $traitsArray = json_decode($data['traits'], true);
            if (is_array($traitsArray)) {
                // Supprimer anciens traits
                foreach ($user->getPersonalityTraits() as $trait) {
                    $user->removePersonalityTrait($trait);
                }
                // Ajouter les nouveaux
                foreach ($traitsArray as $inputTrait) {
                    $trait = $personalityTraitRepository->find($inputTrait['id']);
                    if ($trait) {
                        $user->addPersonalityTrait($trait);
                    }
                }
            }
        }

        // --- Image ---
        if ($files->has('img')) {
            /** @var UploadedFile $file */
            $file = $files->get('img');

            $fileName = uniqid() . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('uploads_directory'), 
                $fileName
            );

            // Stocker le chemin relatif à public
            $user->setImgUrl('/uploads/' . $fileName);
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

        return $this->json($user);
    }


}


