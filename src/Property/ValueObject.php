<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:08
 */

namespace Diodac\Restorer\Property;


class ValueObject extends Accessible
{
    private $constructor;
    private $serializator;

    public function __construct($name, ValueObjectConstructor $constructor, ValueObjectSerializator $serializator)
    {
        $this->constructor = $constructor;
        $this->serializator = $serializator;
        parent::__construct($name);
    }

    public function restore($object, $value)
    {
        $this->setValue($object, $this->constructor->construct($value));
    }

    public function serialize($object)
    {
        return $this->serializator->serialize($this->getValue($object));
    }
}