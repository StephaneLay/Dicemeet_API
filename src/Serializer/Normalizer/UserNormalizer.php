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
        // $data = $this->normalizer->normalize($object, $format, $context);

        $data["id"] = $object->getId();
        $data["name"] = $object->getName();
        $data["email"] = $object->getEmail();
        $data["createdAt"] = $object->getCreationDate()->format('Y-m-d ');
        $data['city'] = $object->getCity() ? $object->getCity()->getName() : '';
        $data['imgUrl'] = $object->getImgUrl();
        $data['bio'] = $object->getBio();
        foreach ($object->getPersonalityTraits() as $trait) {
            $data['traits'][] = $trait->getName();
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
