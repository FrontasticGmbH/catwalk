<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Gateway\PreviewGateway;

class PreviewService
{
    /**
     * @var PreviewGateway
     */
    private $previewGateway;

    public function __construct(PreviewGateway $previewGateway)
    {
        $this->previewGateway = $previewGateway;
    }

    public function get(string $previewId): Preview
    {
        return $this->previewGateway->get($previewId);
    }

    public function store(Preview $preview): Preview
    {
        // This allows us to just compare the pageId in the
        // frontend when checking if the page should be
        // re-rendered. By changing it every time on each preview
        // update this will always cause a re-render in such cases.
        if (!$preview->page->pageId) {
            $preview->page->pageId = md5(microtime());
        }

        return $this->previewGateway->store($preview);
    }

    public function remove(Preview $preview): void
    {
        $this->previewGateway->remove($preview);
    }
}
