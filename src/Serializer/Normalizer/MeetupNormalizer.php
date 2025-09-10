<?php

namespace App\Serializer\Normalizer;

use App\Entity\Meetup;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MeetupNormalizer implements NormalizerInterface
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
        $data['game'] = $object->getGame()->getName();
        $data['place'] = $object->getPlace()->getName();
        $data['city'] = $object->getPlace()->getCity()->getName();
        $data['date'] = $object->getTime()->format('Y-m-d H:i:s');
        $data['participants'] = count($object->getUsers());
        $data['maxParticipants'] = $object->getCapacity();
        $data['owner'] = $object->getOwner()->getName();

        
        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Meetup;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Meetup::class => true];
    }
}
