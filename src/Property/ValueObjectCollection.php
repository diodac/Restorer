<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 10:38
 */

namespace Diodac\Restorer\Property;


use Diodac\Restorer\SerializeStrategy\Strategy;

class ValueObjectCollection extends Accessible
{
    private $class;
    private $constructor;
    private $serializator;
    private $strategy;

    function __construct($name, ValueObjectConstructor $constructor, ValueObjectSerializator $serializator, Strategy $strategy)
    {
        $this->constructor = $constructor;
        $this->serializator = $serializator;
        $this->strategy = $strategy;
        parent::__construct($name);
    }

    public function restore($restoredObject, array $serializedData)
    {
        $restored = array_map(function($data) {
            return $this->restoreObject($data);
        }, $this->strategy->selectRequiredData($serializedData));

        $this->setValue($restoredObject, $restored);
    }

    private function restoreObject($data)
    {
        return $this->constructor->construct($data);
    }

    public function serialize($serializedObject, array $serializedData)
    {
        $serialized = array_map(function($vo) {
            return $this->serializeObject($vo);
        }, $this->getValue($serializedObject));
        return $this->strategy->injectResult($serialized, $serializedData);
    }

    private function serializeObject($object)
    {
        return $this->serializator->serialize($object);
    }
}