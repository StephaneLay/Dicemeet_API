<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $baseUrl = 'http://localhost:8000'; // A CHANGER SI DEPLOIEMENT SERVEUR
        $maxfavorites = 3; // Nombre maximum de jeux et bars favoris à afficher

        $data["id"] = $object->getId();
        $data["name"] = $object->getName();
        $data["email"] = $object->getEmail();
        $data["createdAt"] = $object->getCreationDate()->format('Y-m-d ');
        $data['city'] = $object->getCity() ? $object->getCity()->getName() : '';

        // On differencie les users fixtures avec les vrais users
        if ($data['id'] > 100) {
            $data['imgUrl'] = $baseUrl . '/uploads/' . $object->getImgUrl();
        }else {
            $data['imgUrl'] = $object->getImgUrl();
        }
        
        $data['bio'] = $object->getBio();
        foreach ($object->getPersonalityTraits() as $trait) {
            $data['traits'][] = ['id' => $trait->getId(), 'name' => $trait->getName()];
        }

        if (!empty($context['add_favorites']) && $context['add_favorites'] === true) {
            $data['favoritesGames'] = [];
            $count = 0;
            foreach ($object->getFavoriteGames() as $favoriteGame) {
                if ($count >= $maxfavorites) {
                    break;
                }
                $game = $favoriteGame->getGame();
                if (!$game) {
                    continue; 
                }
                $data['favoritesGames'][] = ['id' => $game->getId(), 'name' => $game->getName(),'playedTimes' => $favoriteGame->getGamesPlayed(),'imgUrl' => $game->getImgUrl()];
                $count++;
            }
            $data['favoritesPlaces'] = [];
            $count = 0;
            foreach ($object->getFavoritePlaces() as $favoritePlace) {
                if ($count >= $maxfavorites) {
                    break;
                }
                $place = $favoritePlace->getPlace();
                if (!$place) {
                    continue;
                }
                $data['favoritesPlaces'][] = ['id' => $place->getId(), 'name' => $place->getName(),'imgUrl' => $place->getImgUrl(),'location' => $place->getCity()->getName()];
                $count++;
            }
        }
        

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [User::class => true];
    }
}
