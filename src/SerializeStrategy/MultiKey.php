<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:41
 */

namespace Diodac\Restorer\SerializeStrategy;


use Diodac\Restorer\Property\Property;

class MultiKey implements Strategy
{
    private $keys;
    private $definition;

    /**
     * @param array $keys
     * @param Property $definition
     */
    function __construct(array $keys, Property $definition)
    {
        $this->keys = $keys;
        $this->definition = $definition;
    }

    /**
     * @param $obj
     * @param array $result
     * @return array
     */
    public function giveStorable($obj, array $result)
    {
        /** @var array $serialized */
        $serialized = $this->definition->serialize($obj);
        foreach($this->keys as $key => $propName) {
            $result[$key] = $serialized[$propName];
        }
        return $result;
    }

    public function restore($obj, array $data)
    {
        $this->definition->restore($obj, array_map(function($key) use ($data) {
            return $data[$key];
        }, array_flip($this->keys)));
    }
}