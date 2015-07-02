<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:01
 */

namespace Diodac\Restorer\Property;


use Diodac\Restorer\SerializeStrategy\Strategy;

class Value extends Accessible implements Property
{
    private $strategy;

    function __construct($name, Strategy $strategy)
    {
        $this->strategy = $strategy;
        parent::__construct($name);
    }

    /**
     * @param $serializedObject
     * @param array $serializedData
     * @return mixed
     */
    public function serialize($serializedObject, array $serializedData)
    {
        return $this->strategy->injectResult($this->getValue($serializedObject), $serializedData);
    }

    /**
     * @param $restoredObject
     * @param array $serializedData
     */
    public function restore($restoredObject, array $serializedData)
    {
        $this->setValue($restoredObject, $this->strategy->selectRequiredData($serializedData));
    }
}