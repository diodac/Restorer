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
    public function injectResult($result, array $serializedData);
    public function selectRequiredData(array $serializedData);
}