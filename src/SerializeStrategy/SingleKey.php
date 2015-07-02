<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:35
 */

namespace Diodac\Restorer\SerializeStrategy;


class SingleKey implements Strategy
{
    private $key;

    /**
     * @param string $key
     */
    function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param $result
     * @param array $serializedData
     * @return mixed
     */
    public function injectResult($result, array $serializedData)
    {
        $serializedData[$this->key] = $result;
        return $serializedData;
    }

    /**
     * @param array $serializedData
     * @return mixed
     */
    public function selectRequiredData(array $serializedData)
    {
        return $serializedData[$this->key];
    }
}