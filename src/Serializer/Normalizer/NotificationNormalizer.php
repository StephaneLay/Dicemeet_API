<?php

namespace App\Serializer\Normalizer;

use App\Entity\Notification;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NotificationNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {

        $data['id'] = $object->getId();
        $data['content'] = $object->getContent();
        $data['createdAt'] = $object->getCreatedAt()->format('Y-m-d H:i:s');

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Notification;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Notification::class => true];
    }
}
