<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 11:49
 */

namespace Diodac\Restorer;


use Diodac\Restorer\Property\Property;

class ObjectSerializator
{
    private $creator;

    public function __construct(ObjectRestorer $creator)
    {
        $this->creator = $creator;
    }

    public function serialize($object)
    {
        $serialized = [];
        /** @var Property $property */
        foreach($this->creator->getProperties() as $property) {
            $serialized = $property->serialize($object, $serialized);
        }
        return $serialized;
    }
}