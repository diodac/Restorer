<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:35
 */

namespace Diodac\Restorer\SerializeStrategy;


use Diodac\Restorer\Property\Property;

class SingleKey implements Strategy
{
    private $key;
    private $definition;

    /**
     * @param string $key
     * @param Property $definition
     */
    function __construct($key, Property $definition)
    {
        $this->key = $key;
        $this->definition = $definition;
    }

    public function giveStorable($obj, array $result)
    {
        $result[$this->key] = $this->definition->serialize($obj);
        return $result;
    }

    public function restore($obj, array $data)
    {
        $this->definition->restore($obj, $data[$this->key]);
    }
}