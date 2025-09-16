<?php

namespace App\Serializer\Normalizer;

use App\Entity\PersonalityTrait;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PersonalityTraitNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {

        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof PersonalityTrait;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [PersonalityTrait::class => true];
    }
}
