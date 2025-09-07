<?php

namespace App\Serializer\Normalizer;

use App\Entity\Game;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GameNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        
        $data["id"] = $object->getId();
        $data["name"] = $object->getName();
        $data["minPlayers"] = $object->getMinPlayers();
        $data["maxPlayers"] = $object->getMaxPlayers();
        $data["imgUrl"] = $object->getImgUrl();
        $data["description"] = $object->getDescription();
        $data['category'] = $object->getCategory() ? $object->getCategory()->getName() : null;

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Game;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Game::class => true];
    }
}
