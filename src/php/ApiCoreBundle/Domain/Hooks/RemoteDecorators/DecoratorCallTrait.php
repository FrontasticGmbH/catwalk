<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\RemoteDecorators;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\ApiCoreBundle\Exception\InvalidHookObjectTypeException;

trait DecoratorCallTrait
{
    private HooksService $hooksService;

    public function __construct(HooksService $hooksService)
    {
        $this->hooksService = $hooksService;
    }

    /**
     * @param string $hook
     * @param array $parameters
     * @return Mixed
     * @throws InvalidHookObjectTypeException
     */
    protected function callExpectList(string $hook, array $parameters)
    {
        //$paramsTypes = [];


        // If we want to keep this it needs fixing as we also pass strings here
        //foreach ($parameters as $parameter) {
        //    $paramsTypes[] = get_class($parameter);
        //}

        $result = $this->hooksService->callExpectList($hook, $parameters);

        //foreach ($result as $key => $item) {
        //    if (!is_a($item, $paramsTypes[$key], true)) {
        //        throw new InvalidHookObjectTypeException;
        //    }
        //}

        return $result;
    }

    protected function callExpectObject(string $hook, array $parameters)
    {
        return $this->hooksService->callExpectObject($hook, $parameters);
    }

    protected function callExpectMultipleObjects(string $hook, array $parameters)
    {
        return $this->hooksService->callExpectMultipleObjects($hook, $parameters);
    }
}
