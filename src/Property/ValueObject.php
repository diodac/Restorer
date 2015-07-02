<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:08
 */

namespace Diodac\Restorer\Property;


use Diodac\Restorer\SerializeStrategy\Strategy;

class ValueObject extends Accessible
{
    private $constructor;
    private $serializator;
    private $strategy;

    public function __construct($name,
        ValueObjectConstructor $constructor,
        ValueObjectSerializator $serializator,
        Strategy $strategy)
    {
        $this->constructor = $constructor;
        $this->serializator = $serializator;
        $this->strategy = $strategy;
        parent::__construct($name);
    }

    public function restore($restoredObject, array $serializedData)
    {
        $data = $this->strategy->selectRequiredData($serializedData);
        $this->setValue($restoredObject, $this->constructor->construct($data));
    }

    public function serialize($serializedObject, array $serializedData)
    {
        return $this->strategy->injectResult(
            $this->serializator->serialize($this->getValue($serializedObject)),
            $serializedData);
    }
}