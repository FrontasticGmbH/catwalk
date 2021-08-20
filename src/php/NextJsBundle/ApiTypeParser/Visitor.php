<?php

namespace Frontastic\Catwalk\NextJsBundle\ApiTypeParser;

use PhpParser;
use PhpParser\Node;

class Visitor extends PhpParser\NodeVisitorAbstract
{
    private $insideClass = false;

    /**
     * @var TypeScriptInterface[]
     */
    private $output = [];

    /**
     * @var TypeScriptInterface
     */
    private $currentInterface;

    public function enterNode(Node $node)
    {
        if ($node instanceof PhpParser\Node\Stmt\Class_) {
            $class = $node;
            $this->output[] = $this->currentInterface = new TypescriptInterface($class->name);
        }

        if ($this->insideClass) {
            if ($node instanceof PhpParser\Node\Stmt\Property) {
                $property = $node;
                if ($property->isPublic()) {
                    $type = $this->parsePhpDocForProperty($property->getDocComment());
                    $this->currentInterface->properties[] = new Property($property->props[0]->name, $type);
                }
            }
        }
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof PhpParser\Node\Stmt\Class_) {
            $this->insideClass = false;
        }
    }

    /**
     * @param \PhpParser\Comment|null $phpDoc
     */
    private function parsePhpDocForProperty($phpDoc)
    {
        $result = "any";

        if ($phpDoc !== null) {
            if (preg_match('/@var[ \t]+([a-z0-9]+)/i', $phpDoc->getText(), $matches)) {
                $t = trim(strtolower($matches[1]));

                if ($t === "int") {
                    $result = "number";
                }
                elseif ($t === "string") {
                    $result = "string";
                }
            }
        }

        return $result;
    }

    public function getOutput()
    {
        return implode("\n\n", array_map(function ($i) { return (string)$i;}, $this->output));
    }
}
