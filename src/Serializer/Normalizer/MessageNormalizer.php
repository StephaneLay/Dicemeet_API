<?php

namespace App\Serializer\Normalizer;

use App\Entity\Message;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MessageNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        // TODO: add, edit, or delete some data

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Message;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Message::class => true];
    }
}
