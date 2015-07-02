<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 10:38
 */

namespace Diodac\Restorer\Property;


use Diodac\Restorer\InvalidConfigurationException;
use Diodac\Restorer\SerializeStrategy\Strategy;

class ValueObjectCollection extends Accessible
{
    private $strategies;
    private $typeKey;
    private $strategy;

    function __construct($name, array $strategies, Strategy $strategy, $typeKey = '__type')
    {
        $this->strategies = $strategies;
        $this->typeKey = $typeKey;
        $this->strategy = $strategy;
        parent::__construct($name);
    }

    public function restore($restoredObject, array $serializedData)
    {
        $restored = [];
        foreach($this->strategy->selectRequiredData($serializedData) as $k => $v) {
            if (isset($v[$this->typeKey])) {
                $restored[$k] = $this->restoreObject($v);
            } else {
                $restored[$k] = $v;
            }
        }
        $this->setValue($restoredObject, $restored);
    }

    private function restoreObject($data)
    {
        $type = $data[$this->typeKey];
        if (!isset($this->strategies[$type])) {
            throw new InvalidConfigurationException('Brak kreatora dla typu ' . $type);
        }

        return $this->strategies[$type][1]->construct($data);
    }

    public function serialize($serializedObject, array $serializedData)
    {
        $objects = $this->getValue($serializedObject);
        $classIndex = $this->getStrategyTypesIndexedByClass();
        $serialized = [];

        foreach($objects as $index => $vo) {
            $objClass = get_class($vo);
            if (!isset($classIndex[$objClass])) {
                throw new InvalidConfigurationException('Brak kreatora dla klasy ' . $objClass);
            }

            $serializator = $this->strategies[$classIndex[$objClass]][2];
            $serialized[$index] = $this->serializeObject($vo, $serializator);
            $serialized[$index][$this->typeKey] = $classIndex[$objClass];
        }
        return $this->strategy->injectResult($serialized, $serializedData);
    }

    private function getStrategyTypesIndexedByClass()
    {
        $index = [];
        foreach($this->strategies as $type => $strategy) {
            $index[$strategy[0]] = $type;
        }

        return $index;
    }

    private function serializeObject($object, ValueObjectSerializator $serializator)
    {
        return $serializator->serialize($object);
    }
}