<?php

namespace App\Controller;

use App\Repository\PersonalityTraitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TraitsController extends AbstractController
{
    #[Route('/api/private/traits', name: 'app_traits')]
    public function getAll(PersonalityTraitRepository $personalityTraitRepository): Response
    {
        $traits = $personalityTraitRepository->findAll();

        return $this->json($traits);
    }
}
