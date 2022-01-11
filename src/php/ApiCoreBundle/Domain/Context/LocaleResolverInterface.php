<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Component\HttpFoundation\Request;

interface LocaleResolverInterface
{
    public function determineLocale(Request $request, Project $project): string;
}
