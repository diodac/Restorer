<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:05
 */

namespace Diodac\Restorer\Property;


use Diodac\Restorer\ObjectRestorer;

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
    public function serialize($object)
    {
        return array_map(function(Property $property) use ($object) {
            return $property->serialize($object);
        }, $this->properties);
    }

    public function restore($object, $value)
    {
        $this->setValue($object, (new ObjectRestorer($this->class, $this->properties))->create($value));
    }
}