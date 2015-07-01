<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 10:27
 */

namespace Diodac\Restorer\Property;


interface ValueObjectConstructor
{
    public function construct($data);
}