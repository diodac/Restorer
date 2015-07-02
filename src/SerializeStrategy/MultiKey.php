<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:41
 */

namespace Diodac\Restorer\SerializeStrategy;

class MultiKey implements Strategy
{
    private $keys;

    /**
     * @param array $keys
     */
    function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    /**
     * @param array $result
     * @param array $serializedData
     * @return array
     * @internal param $obj
     */
    public function injectResult($result, array $serializedData)
    {
        foreach($this->keys as $key => $propName) {
            $serializedData[$key] = $result[$propName];
        }
        return $serializedData;
    }

    public function selectRequiredData(array $serializedData)
    {
        return array_map(function($key) use ($serializedData) {
            return $serializedData[$key];
        }, array_flip($this->keys));
    }
}