<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:07
 */

namespace Diodac\Restorer\Property;

use Diodac\Restorer\ObjectRestorer;
use Diodac\Restorer\SerializeStrategy\Strategy;

class ObjectCollection extends Accessible
{
    private $creator;
    private $strategy;

    /**
     * @param $name
     * @param ObjectRestorer $creator
     * @param Strategy $strategy
     */
    function __construct($name, ObjectRestorer $creator, Strategy $strategy)
    {
        $this->creator = $creator;
        $this->strategy = $strategy;
        parent::__construct($name);
    }

    public function serialize($serializedObject, array $serializedData)
    {
        $serialized = array_map(function($obj) {
            return $this->serializeObjectAsArray($obj, $this->creator->getProperties());
        }, $this->getValue($serializedObject));

        return $this->strategy->injectResult($serialized, $serializedData);
    }

    private function serializeObjectAsArray($storing, $properties)
    {
        return array_reduce($properties, function(array $carry, Property $property) use ($storing) {
            return $property->serialize($storing, $carry);
        }, []);
    }

    public function restore($restoredObject, array $serializedData)
    {
        $restored = array_map(function($data) {
            return $this->restoreObject($data);
        }, $this->strategy->selectRequiredData($serializedData));

        $this->setValue($restoredObject, $restored);
    }

    /**
     * @param $data
     * @return object
     */
    private function restoreObject($data)
    {
        return $this->creator->restore($data);
    }
}