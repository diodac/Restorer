<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-03
 * Time: 10:53
 */

namespace Diodac\Restorer\Property;


class IdentityProperty implements Identity, Property
{
    private $property;

    function __construct(Property $property)
    {
        $this->property = $property;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->property->getName();
    }

    public function serialize($serializedObject, array $serializedData)
    {
        return $this->property->serialize($serializedObject, $serializedData);
    }

    public function restore($restoredObject, array $serializedData)
    {
        return $this->property->restore($restoredObject, $serializedData);
    }

}