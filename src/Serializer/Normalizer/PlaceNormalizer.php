<?php

namespace App\Serializer\Normalizer;

use App\Entity\Place;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PlaceNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {

        $data['id'] = $object->getId();
        $data['name'] = $object->getName();
        $data['capacity'] = $object->getCapacity();
        $data['imgUrl'] = $object->getImgUrl();
        $data['location'] = $object->getAdressNumber() . ' ' .$object->getAdressStreet() ;
        $data['cityName'] = $object->getCity()->getName() ;
 
        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Place;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Place::class => true];
    }
}
