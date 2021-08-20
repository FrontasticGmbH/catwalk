<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

interface DynamicPageHandler
{
    /**
     * @todo When this returns we will need to fetch additional stream data, too!
     */
    public function handleDynamicPage(Request $request, DynamicPageContext $dynamicPageContext): DynamicPageResult;
}
