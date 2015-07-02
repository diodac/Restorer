<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:05
 */

namespace Diodac\Restorer\Property;


use Diodac\Restorer\ObjectRestorer;
use Diodac\Restorer\SerializeStrategy\Strategy;

class Object extends Accessible implements Property
{
    private $class;
    private $properties;
    private $strategy;

    function __construct($name, $class, $properties, Strategy $strategy)
    {
        $this->class = $class;
        $this->properties = $properties;
        $this->strategy = $strategy;

        parent::__construct($name);
    }

    public function serialize($serializedObject, array $serializedData)
    {
        $storing = $this->getValue($serializedObject);
        $result = array_reduce($this->properties, function(array $carry, Property $property) use ($storing) {
            return $property->serialize($storing, $carry);
        }, []);
        return $this->strategy->injectResult($result, $serializedData);
    }

    public function restore($restoredObject, array $serializedData)
    {
        $this->setValue($restoredObject, (new ObjectRestorer($this->class, $this->properties))->create($serializedData));
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}