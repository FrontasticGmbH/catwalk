<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

interface ActionHandler
{
    public function handleAction(Request $request, ActionContext $actionContext): Response;
}
