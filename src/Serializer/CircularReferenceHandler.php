<?php

namespace App\Serializer;

class CircularReferenceHandler
{
    public static function handle(object $object): mixed
    {
        if (method_exists($object, 'getId')) {
            return [
                'id' => $object->getId(),
                'lastName' => $object->getLastName(),
                'firstName' => $object->getFirstName(),
                'gender' => $object->getGender(),
                'email' => $object->getEmail()
            ];
        }

        return spl_object_id($object);
    }
}
