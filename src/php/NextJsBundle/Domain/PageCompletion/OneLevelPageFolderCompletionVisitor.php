<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\PageFolderValue;

class OneLevelPageFolderCompletionVisitor extends PageFolderCompletionVisitor
{
    // this is the same as in PageFolderCompletionVisitor, but with the call to completeCustomNodeData() removed
    private function createNodeRepresentation(string $pageFolderId): PageFolderValue
    {
        $node = $this->nodeService->get($pageFolderId);

        $urls = $this->siteBuilderPageService->getPathsForSiteBuilderPage($pageFolderId);

        return new PageFolderValue([
            'pageFolderId' => $pageFolderId,
            'name' => $node->name,
            'configuration' => (object)$node->configuration,
            'hasLivePage' => $this->pageExists($pageFolderId),
            '_urls' => $urls,
            '_url' => LocalizedValuePicker::getValueForCurrentLocale($this->context, $urls)
        ]);
    }
}
