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
        $baseUrl = 'http://localhost:8000'; // A CHANGER SI DEPLOIEMENT SERVEUR


         $data["id"] = $object->getId();
         $data["content"] = $object->getContent();
         $data["time"] = $object->getTime()->format('Y-m-d H:i:s');
        //  $data["isRead"] = $object->isIsRead(); A VOIR CE QU'ON FINIT PAR EN FOUTRE
         $data["sender"] = [
            "id" => $object->getSender()->getId(),
            "name" => $object->getSender()->getName(),
         ];
         if ($data['sender']['id'] > 100) {
            $data['sender']['imgUrl'] = $baseUrl . '/uploads/' . $object->getSender()->getImgUrl();
        }else {
            $data['sender']['imgUrl'] = $object->getSender()->getImgUrl();
        }
         if ($object->getMeetup()) {
            $data["meetup"] = [
                "id" => $object->getMeetup()->getId(),
                "title" => $object->getMeetup()->getTitle()
            ];
         } else {
            $data["meetup"] = null;
         }

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
