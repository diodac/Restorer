<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 10:28
 */

namespace Diodac\Restorer\Property;


interface ValueObjectSerializator
{
    public function serialize($obj);
}