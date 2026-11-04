<?php

namespace Frontastic\Catwalk;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Environment;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AppKernel extends \Frontastic\Common\Kernel
{
    public static $catwalkBaseDir;

    public function registerBundles(): iterable
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Frontastic\Common\MvcBundle\FrontasticCommonMvcBundle(),
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
            new \Frontastic\Common\AlgoliaBundle\FrontasticCommonAlgoliaBundle(),
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
    public function registerContainerConfiguration(LoaderInterface $loader): void
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
    public function getProjectDir(): string
    {
        return static::getBaseDir();
    }

    /**
     * Symfony uses reflection and AppKernel class file otherwise.
     */
    public function getRootDir(): string
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

        if (\Symfony\Component\HttpKernel\Kernel::VERSION_ID < 70000) {
            // Nothing uses annotations anymore; Symfony 7 removed this option together with
            // the whole integration, so it must only be set on the 6.4 leg.
            $container->prependExtensionConfig('framework', ['annotations' => false]);
        }

        $container->prependExtensionConfig('doctrine', [
            'orm' => array_merge($this->getLazyLoadingConfiguration(), $this->getFieldReportingConfiguration()),
        ]);

        return $container;
    }

    /**
     * Entities are lazy loaded through PHP's native lazy objects when PHP 8.4
     * or newer runs an ORM that supports them, and through generated proxy
     * classes otherwise.
     *
     * DoctrineBundle refuses the native option on PHP versions before 8.4 and
     * on ORM versions before 3.4, and deprecates every proxy class setting
     * from PHP 8.4 on when the ORM supports native lazy objects, so exactly
     * one of the two variants is configured.
     *
     * @return array<string, bool>
     */
    private function getLazyLoadingConfiguration(): array
    {
        if (\PHP_VERSION_ID >= 80400 && method_exists(Configuration::class, 'enableNativeLazyObjects')) {
            return ['enable_native_lazy_objects' => true];
        }

        return ['auto_generate_proxy_classes' => $this->isDebug()];
    }

    /**
     * ORM 2 maps inherited fields to the class declaring them only with this
     * option enabled and deprecates the disabled state; ORM 3 always maps them
     * that way and DoctrineBundle deprecates setting the option there, so it
     * is only set for ORM 2. ORM 3 removed the annotation driver, which makes
     * its presence the version marker.
     *
     * @return array<string, bool>
     */
    private function getFieldReportingConfiguration(): array
    {
        if (class_exists(AnnotationDriver::class)) {
            return ['report_fields_where_declared' => true];
        }

        return [];
    }

    public static function isProduction()
    {
        return self::getEnvironmentFromConfiguration() === 'prod';
    }
}

// @FIXME: Remove references to \AppKernel from Frontastic\Common
// phpcs:disable
class_alias(AppKernel::class, '\\AppKernel');
// phpcs:enable
