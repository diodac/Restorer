<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:01
 */

namespace Diodac\Restorer\Property;


class Value extends Accessible implements Property
{
    public function serialize($object)
    {
        return $this->getValue($object);
    }

    public function restore($object, $value)
    {
        $this->setValue($object, $value);
    }
}