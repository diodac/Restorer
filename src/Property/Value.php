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
    public function serialize($serializedObject)
    {
        return $this->getValue($serializedObject);
    }

    public function restore($restoredObject, $value)
    {
        $this->setValue($restoredObject, $value);
    }
}