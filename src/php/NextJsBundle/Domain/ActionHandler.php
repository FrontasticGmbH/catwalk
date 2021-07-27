<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

interface ActionHandler
{
    public function handleAction(Request $request, ActionContext $actionContext): Response;
}
