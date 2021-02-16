<?php
namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Common\CoreBundle\Domain\ApiDataObject;

class HookResponseDeserializer
{
    public function deserialize(array $struct)
    {
        return $this->convert($struct, true);
    }

    private function convert($argument, $rootNode = false)
    {
        if ($rootNode === false && !isset($argument['_type'])) {
            foreach ($argument as $key => $param) {
                if (is_array(($param))) {
                    $argument[$key] = $this->convert($param);
                }
            }
            return $argument;
        }

        if (!isset($argument['_type'])) {
            throw new \Exception('Hook API return value without a _type entry for the root level object');
        }
        $class = $argument['_type'];
        if (!is_a($class, ApiDataObject::class, true)) {
            throw new \Exception('Unknown type for deserialization: ' . $class);
        }
        unset($argument['_type']);


        // This could be done by generating mappers which get the type information from annotations for 7.4 type hints
        $reflection = new \ReflectionClass($class);

        $props = $reflection->getProperties();
        foreach ($props as $prop) {
            $name = $prop->getName();
            if (isset($argument[$name]) && is_array($argument[$name])) {
                $argument[$name] = $this->convert($argument[$name]);
            }
            $type = '';
            if ($prop->hasType()) {
                $type = $prop->getType()->getName();
            } else {
                $matches = [];
                if (preg_match('(@var \??\\?(?P<type>[a-z]+))i', $prop->getDocComment(), $matches)) {
                    $type = $matches['type'];
                }
            }
            if ($type) {
                switch ($type) {
                    case 'DateTimeImmutable':
                        $argument[$name] = \DateTimeImmutable::createFromFormat(
                            DATE_ATOM,
                            $argument[$name]
                        );
                        break;
                    case 'string':
                    case 'integer':
                    case 'int':
                    case 'bool':
                    case 'boolean':
                        break;
                    default:
                        throw new \Exception('Unmappable type: ' . $type);
                }
            }
        }

        return new $class($argument, true);
    }
}
