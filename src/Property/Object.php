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

    function __construct($name, $class, $properties)
    {
        $this->class = $class;
        $this->properties = $properties;

        parent::__construct($name);
    }

    //FIXME: stare podejście
    public function serialize($serializedObject)
    {
        $storing = $this->getValue($serializedObject);
        return array_reduce($this->properties, function(array $carry, Strategy $property) use ($storing) {
            return $property->giveStorable($storing, $carry);
        }, []);
    }

    public function restore($restoredObject, $value)
    {
        $this->setValue($restoredObject, (new ObjectRestorer($this->class, $this->properties))->create($value));
    }
}