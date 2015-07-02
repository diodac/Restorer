<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 08:58
 */

namespace Diodac\Restorer\Property;


interface Property
{
    /**
     * @return string
     */
    public function getName();
    public function serialize($serializedObject, array $serializedData);
    public function restore($restoredObject, array $serializedData);
}