<?php

namespace Frontastic\Catwalk;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Environment;
use Symfony\Component\Config\Loader\LoaderInterface;
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
            new \Frontastic\Common\AccountApiBundle\FrontasticCommonAccountApiBundle(),
            new \Frontastic\Common\ProductApiBundle\FrontasticCommonProductApiBundle(),
            new \Frontastic\Common\ProductSearchApiBundle\FrontasticCommonProductSearchApiBundle(),
            new \Frontastic\Common\ProjectApiBundle\FrontasticCommonProjectApiBundle(),
            new \Frontastic\Common\ContentApiBundle\FrontasticCommonContentApiBundle(),
            new \Frontastic\Common\WishlistApiBundle\FrontasticCommonWishlistApiBundle(),
            new \Frontastic\Catwalk\TrackingBundle\FrontasticCatwalkTrackingBundle(),
            new \Frontastic\Common\CartApiBundle\FrontasticCommonCartApiBundle(),
            new \Frontastic\Common\SapCommerceCloudBundle\FrontasticCommonSapCommerceCloudBundle(),
            new \Frontastic\Common\ShopifyBundle\FrontasticCommonShopifyBundle(),
            new \Frontastic\Common\ShopwareBundle\FrontasticCommonShopwareBundle(),
            new \Frontastic\Common\SprykerBundle\FrontasticCommonSprykerBundle(),
            new \Frontastic\Common\FindologicBundle\FrontasticCommonFindologicBundle(),
            new \Frontastic\Catwalk\FrontendBundle\FrontasticCatwalkFrontendBundle(),
            new \Frontastic\Catwalk\ApiCoreBundle\FrontasticCatwalkApiCoreBundle(),
            new \Frontastic\Catwalk\DevVmBundle\FrontasticCatwalkDevVmBundle(),
        );

        if (getenv('is_frontastic_nextjs') === '1') {
            $bundles[] = new \Frontastic\Catwalk\NextJsBundle\FrontasticCatwalkNextJsBundle();
        }

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
     * Register symfony configuration from base dir.
     *
     * @TODO Use Symfony Flex mechanism instead
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $environment = $this->getEnvironment();

        $catwalkBaseConfigDir = __DIR__ . '/../../config';

        $executedInBaseCatwalk = (realpath($catwalkBaseConfigDir) === realpath(self::$catwalkBaseDir . '/config'));

        // Always load basic catwalk settings for environment (imports global) or global
        if (file_exists($catwalkBaseConfigDir . '/config_' . $environment . '.yml')) {
            $loader->load($catwalkBaseConfigDir . '/config_' . $environment . '.yml');
        } else {
            $loader->load($catwalkBaseConfigDir . '/config.yml');
        }

        if (!$executedInBaseCatwalk) {
            // Load additional project settings, if exist (for environment or global)
            $projectConfigDir = static::getBaseDir() . '/config';
            if (file_exists($projectConfigDir . '/config_' . $environment . '.yml')) {
                $loader->load($projectConfigDir . '/config_' . $environment . '.yml');
            } elseif (file_exists($projectConfigDir . '/config.yml')) {
                $loader->load($projectConfigDir . '/config.yml');
            }
        }
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
            [
                dirname(__DIR__) . '/environment',
                dirname(__DIR__) . '/environment.local',
            ],
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

        $container->setParameter('frontastic.environment', Environment::mapFromFramework($this->getEnvironment()));
        $container->setParameter('frontastic.paas_catwalk_dir', __DIR__ . '/../..');
        $container->setParameter('frontastic.gedmo_extension_source_dir', $this->getGedmoExtensionsSourceDir());

        return $container;
    }

    private function getGedmoExtensionsSourceDir(): string
    {
        // Until recently the vendor dir from paas/catwalk was used for the dependencies of catwalk. Now all
        // dependencies are installed to the projects vendor dir. This ensures that we use the gedmo extension from the
        // projects vendor dir if it exists there and fall back to the vendor dir in paas/catwalk to support legacy
        // catwalks without touching them.
        $candidates = [
            $this->getProjectDir() . '/vendor/gedmo/doctrine-extensions/lib',
            __DIR__ . '/../../vendor/gedmo/doctrine-extensions/lib',
        ];

        foreach ($candidates as $candidate) {
            if (is_dir($candidate)) {
                return $candidate;
            }
        }

        throw new \RuntimeException('The source of the gedmo doctrine extension was not found');
    }
}

// @FIXME: Remove references to \AppKernel from Frontastic\Common
// phpcs:disable
\Doctrine\Common\Annotations\AnnotationReader::addGlobalIgnoredName('type');
class_alias(AppKernel::class, '\\AppKernel');
// phpcs:enable
