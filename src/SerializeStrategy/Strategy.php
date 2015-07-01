<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:30
 */

namespace Diodac\Restorer\SerializeStrategy;


interface Strategy
{
    public function giveStorable($obj, array $result);
    public function restore($obj, array $data);
}