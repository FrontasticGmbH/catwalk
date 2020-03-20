<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\RemoteDecorator\Formatter;

use Frontastic\Catwalk\ApiCoreBundle\Domain\RemoteDecorator\Formatter;
use Frontastic\Common\JsonSerializer;

class Json extends Formatter
{
    private $serializer;

    public function __construct(JsonSerializer $serializer = null)
    {
        $this->serializer = $serializer ?: new JsonSerializer();
    }

    /**
     * Encode object graph into a string
     *
     * @param mixed $value
     * @return string
     */
    public function encode($value): string
    {
        return json_encode($this->serializer->serialize($value));
    }

    /**
     * Decode string into object graph
     *
     * @param string $value
     * @return mixed
     */
    public function decode(string $value)
    {
        return $this->fixTypes(json_decode($value));
    }

    /**
     * Tries to convert stdClass objects into domain object, if type information is available
     *
     * @param mixed $value
     * @return mixed
     */
    private function fixTypes($value)
    {
        if (is_array($value)) {
            foreach ($value as $property => $propertyValue) {
                $value[$property] = $this->fixTypes($propertyValue);
            }
        }

        if (is_object($value)) {
            foreach ($value as $property => $propertyValue) {
                $value->$property = $this->fixTypes($propertyValue);
            }
        }

        if (is_object($value) && isset($value->_type) && class_exists($value->_type)) {
            $class = $value->_type;
            unset($value->_type);

            $domainObject = new $class();
            foreach ($value as $property => $propertyValue) {
                $domainObject->$property = $propertyValue;
            }

            $value = $domainObject;
        }

        return $value;
    }
}
