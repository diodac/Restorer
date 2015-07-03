<?php
/**
 * Created by PhpStorm.
 * User: partykad
 * Date: 2015-07-01
 * Time: 08:32
 */

namespace Diodac\Restorer;


use Diodac\Restorer\Property\Property;
use Diodac\Restorer\SerializeStrategy\Strategy;

class ObjectRestorer
{
    private $class;
    private $properties;

    /**
     * @param string $class
     * @param Property[] $properties
     */
    public function __construct($class, array $properties)
    {
        $this->class = $class;
        $this->properties = $properties;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return array|Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    public function restore(array $data)
    {
        $obj = (new \ReflectionClass($this->class))->newInstanceWithoutConstructor();

        $this->restoreProperties($obj, $data);

        return $obj;
    }

    private function restoreProperties($obj, array $data)
    {
        /** @var Property $property */
        foreach($this->properties as $property) {
            $property->restore($obj, $data);
        }
    }
}