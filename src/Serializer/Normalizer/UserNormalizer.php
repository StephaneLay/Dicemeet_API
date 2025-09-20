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

        $data["id"] = $object->getId();
        $data["name"] = $object->getName();
        $data["email"] = $object->getEmail();
        $data["createdAt"] = $object->getCreationDate()->format('Y-m-d ');
        $data['city'] = $object->getCity() ? $object->getCity()->getName() : '';
        $data['imgUrl'] = $baseUrl . '/uploads/' . $object->getImgUrl();
        $data['bio'] = $object->getBio();
        foreach ($object->getPersonalityTraits() as $trait) {
            $data['traits'][] = ['id' => $trait->getId(), 'name' => $trait->getName()];
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
