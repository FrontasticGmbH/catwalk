<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AssetController extends AbstractController
{
    private $projectDir;

    public function __construct(KernelInterface $appKernel)
    {
        $this->projectDir = $appKernel->getProjectDir();

    }

    /**
     * Delvier "similar" assets
     *
     * We fall back to "similar" asset files using a PHP controller without any
     * caching to also serve assets during a deployment when the server
     * delivering the HTML and the server delivering the assets might be on
     * different versions – without any caching.
     */
    public function deliverAction(string $type, string $file)
    {
        $fileMatcher = '(' . preg_replace('(\\.[0-9a-f]{8}\\.)', '(\\.[0-9a-f]{8}\\.)', $file) . ')';
        $availableAssetFiles = glob($this->projectDir . '/public/assets/*/*');

        foreach ($availableAssetFiles as $assetFile) {
            if (preg_match($fileMatcher, $assetFile)) {
                switch ($type) {
                    case 'css':
                        $contentType = 'text/css';
                        break;
                    case 'js':
                        $contentType = 'text/javascript';
                        break;
                    default:
                        $contentType = mime_content_type($assetFile);
                        break;
                }

                return new BinaryFileResponse(
                    $assetFile,
                    200,
                    [
                        'Content-Type' => $contentType,
                        // This file is temporarily delivered under the wrong
                        // name, so we make sure it is not cached:
                        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                        'Pragma' => 'no-cache',
                        'Expires' => date('r'),
                    ]
                );
            }
        }

        throw new NotFoundHttpException($fileMatcher);
    }
}
