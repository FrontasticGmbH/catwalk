<?php
namespace Frontastic\Catwalk\TwigTasticBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    private $assetHash;

    public function getFunctions()
    {
        return [
            new TwigFunction('asset_hash', [$this, 'updateHash']),
        ];
    }

    public function updateHash(string $path)
    {
        if ($hash = $this->loadAssetHash()) {
            return preg_replace('(\.[0-9a-f]+\.(js|css)$)', ".{$hash}.\\1", $path);
        }
        return $path;
    }

    private function loadAssetHash()
    {
        if (false === is_null($this->assetHash)) {
            return $this->assetHash;
        }

        $file = $_SERVER['DOCUMENT_ROOT'] . '/.fake_preview_hash';

        if (false === file_exists($file)) {
            return ($this->assetHash = false);
        }
        return ($this->assetHash = trim(file_get_contents($file)));
    }
}
