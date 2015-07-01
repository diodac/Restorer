<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 08:59
 */

namespace Diodac\Restorer\Property;


abstract class Accessible implements Property
{
    private $name;

    function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    protected function setValue($object, $value)
    {
        $refProp = new \ReflectionProperty($object, $this->getName());
        $refProp->setAccessible(true);
        $refProp->setValue($object, $value);
        $refProp->setAccessible(false);
    }

    protected function getValue($object)
    {
        $refProp = new \ReflectionProperty($object, $this->getName());
        $refProp->setAccessible(true);
        $value = $refProp->getValue($object);
        $refProp->setAccessible(false);

        return $value;
    }
}