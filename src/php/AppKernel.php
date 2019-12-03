<?php

namespace Frontastic\Catwalk;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AppKernel extends \Frontastic\Common\Kernel
{
    public static $catwalkBaseDir;

    public function registerBundles()
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \QafooLabs\Bundle\NoFrameworkBundle\QafooLabsNoFrameworkBundle(),
            new \KPhoen\RulerZBundle\KPhoenRulerZBundle(),

            new \Frontastic\Common\CoreBundle\FrontasticCommonCoreBundle(),
            new \Frontastic\Common\ReplicatorBundle\FrontasticCommonReplicatorBundle(),
            new \Frontastic\Catwalk\FrontendBundle\FrontasticCatwalkFrontendBundle(),
            new \Frontastic\Catwalk\ApiCoreBundle\FrontasticCatwalkApiCoreBundle(),
            new \Frontastic\Common\AccountApiBundle\FrontasticCommonAccountApiBundle(),
            new \Frontastic\Common\ProductApiBundle\FrontasticCommonProductApiBundle(),
            new \Frontastic\Common\ProjectApiBundle\FrontasticCommonProjectApiBundle(),
            new \Frontastic\Common\ContentApiBundle\FrontasticCommonContentApiBundle(),
            new \Frontastic\Common\WishlistApiBundle\FrontasticCommonWishlistApiBundle(),
            new \Frontastic\Common\CartApiBundle\FrontasticCommonCartApiBundle(),
            new \Frontastic\Catwalk\DevVmBundle\FrontasticCatwalkDevVmBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new \Frontastic\Common\DevelopmentBundle\FrontasticCommonDevelopmentBundle();
        } else {
            if (!function_exists('\\debug')) {
                require __DIR__ . '/debug.php';
            }
        }

        $additionalBundlesFile = $this->getRootDir() . '/config/bundles.php';

        if (file_exists($additionalBundlesFile)) {
            $bundles = array_merge(
                $bundles,
                require $additionalBundlesFile
            );
        }

        return $bundles;
    }

    /**
     * Symfony uses reflection and AppKernel class file otherwise.
     */
    public function getProjectDir()
    {
        return static::getBaseDir();
    }

    /**
     * Symfony uses reflection and AppKernel class file otherwise.
     */
    public function getRootDir()
    {
        return static::getBaseDir();
    }

    public static function getAdditionalConfigFiles()
    {
        // Parent already loads config from base dir, load catwalk in addition
        return array_merge(
            [dirname(__DIR__) . '/environment'],
            parent::getAdditionalConfigFiles()
        );
    }

    public static function getBaseDir(): string
    {
        if (!isset(static::$catwalkBaseDir)) {
            throw new \RuntimeException('Catwalk misconfiguration: Base Dir not set.');
        }
        return static::$catwalkBaseDir;
    }

    protected function buildContainer(): ContainerBuilder
    {
        $container = parent::buildContainer();

        $container->setParameter('frontastic.paas_catwalk_dir', __DIR__ . '/../..');

        return $container;
    }
}

// @FIXME: Remove references to \AppKernel from Frontastic\Common
// phpcs:disable
class_alias(AppKernel::class, '\\AppKernel');
// phpcs:enable
