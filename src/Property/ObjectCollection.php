<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 09:07
 */

namespace Diodac\Restorer\Property;

use Diodac\Restorer\InvalidConfigurationException;
use Diodac\Restorer\ObjectRestorer;

class ObjectCollection extends Accessible
{
    private $creators;
    private $typeKey;

    /**
     * @param $name
     * @param ObjectRestorer[] $creators
     * @param string $typeKey
     */
    function __construct($name, array $creators, $typeKey = '__type')
    {
        $this->creators = $creators;
        $this->typeKey = $typeKey;

        parent::__construct($name);
    }

    //FIXME: stare podejście
    public function serialize($object)
    {
        $objects = $this->getValue($object);
        $classIndex = $this->getCreatorTypesIndexedByClass();
        $serialized = [];

        foreach($objects as $index => $object) {
            $objClass = get_class($object);
            if (!isset($classIndex[$objClass])) {
                throw new InvalidConfigurationException('Brak kreatora dla klasy ' . $objClass);
            }
            /** @var ObjectRestorer $creator */
            $creator = $this->creators[$classIndex[$objClass]];
            $serialized[$index] = $this->serializeObjectAsArray($object, $creator->getProperties());
            $serialized[$index][$this->typeKey] = $classIndex[$objClass];
        }

        return $serialized;
    }

    private function serializeObjectAsArray($object, $properties)
    {
        return array_map(function(Property $property) use ($object) {
            return $property->store($object);
        }, $properties);
    }

    public function restore($object, $value)
    {
        $restored = [];
        foreach($value as $k => $v) {
            if (isset($v[$this->typeKey])) {
                $restored[$k] = $this->restoreObject($v);
            } else {
                $restored[$k] = $v;
            }
        }
        $this->setValue($object, $restored);
    }

    /**
     * @param $data
     * @return object
     * @throws InvalidConfigurationException
     */
    private function restoreObject($data)
    {
        $type = $data[$this->typeKey];
        if (!isset($this->creators[$type])) {
            throw new InvalidConfigurationException('Brak kreatora dla typu ' . $type);
        }
        unset($data[$type]);

        return $this->creators[$type]->create($data);
    }

    private function getCreatorTypesIndexedByClass()
    {
        $index = [];
        foreach($this->creators as $type => $creator) {
            $index[$creator->getClass()] = $type;
        }

        return $index;
    }
}