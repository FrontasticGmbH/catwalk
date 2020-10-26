<?php

namespace ContainerDzhb2j7;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/*
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 *
 * @final
 */
class catwalkFrontastic_Catwalk_AppKernelProdContainer extends Container
{
    private $buildParameters;
    private $containerDir;
    private $targetDir;
    private $parameters = [];
    private $getService;

    public function __construct(array $buildParameters = [], $containerDir = __DIR__)
    {
        $this->getService = \Closure::fromCallable([$this, 'getService']);
        $this->buildParameters = $buildParameters;
        $this->containerDir = $containerDir;
        $this->targetDir = \dirname($containerDir);
        $this->parameters = $this->getDefaultParameters();

        $this->services = $this->privates = [];
        $this->syntheticIds = [
            'kernel' => true,
        ];
        $this->methodMap = [
            'Frontastic\\Catwalk\\ApiCoreBundle\\Controller\\ParameterConverter\\ContextConverter' => 'getContextConverterService',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ContextService' => 'getContextServiceService',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\Context\\LocaleResolver' => 'getLocaleResolverService',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\CustomerService' => 'getCustomerServiceService',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ProjectService' => 'getProjectServiceService',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Monolog\\FrontasticLogProcessor' => 'getFrontasticLogProcessorService',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Monolog\\JsonFormatter' => 'getJsonFormatterService',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\RenderService\\ResponseDecorator' => 'getResponseDecoratorService',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\CacheDirectives' => 'getCacheDirectivesService',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ContentSecurityPolicy' => 'getContentSecurityPolicyService',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\EmbedContext' => 'getEmbedContextService',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\Http2LinkListener' => 'getHttp2LinkListenerService',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ProjectBasicAuthListener' => 'getProjectBasicAuthListenerService',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RebuildRoutesListener' => 'getRebuildRoutesListenerService',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ReferrerPolicy' => 'getReferrerPolicyService',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RequestIdListener' => 'getRequestIdListenerService',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RequestIdResponseHeaderListener' => 'getRequestIdResponseHeaderListenerService',
            'Frontastic\\Catwalk\\FrontendBundle\\Twig\\NodeExtension' => 'getNodeExtensionService',
            'Frontastic\\Common\\ReplicatorBundle\\Domain\\Project' => 'getProjectService',
            'doctrine' => 'getDoctrineService',
            'event_dispatcher' => 'getEventDispatcherService',
            'http_kernel' => 'getHttpKernelService',
            'logger' => 'getLoggerService',
            'request_stack' => 'getRequestStackService',
            'router' => 'getRouterService',
            'security.authorization_checker' => 'getSecurity_AuthorizationCheckerService',
            'security.token_storage' => 'getSecurity_TokenStorageService',
        ];
        $this->fileMap = [
            'Aptoma\\Twig\\Extension\\MarkdownExtension' => 'getMarkdownExtensionService.php',
            'Contentful\\RichText\\Renderer' => 'getRendererService.php',
            'Domnikl\\Statsd\\Client' => 'getClientService.php',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\AppRepositoryService' => 'getAppRepositoryServiceService.php',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\AppService' => 'getAppServiceService.php',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\CommerceTools\\ClientFactory' => 'getClientFactoryService.php',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\Context' => 'getContextService.php',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ContextInContainerDeprecationProvider' => 'getContextInContainerDeprecationProviderService.php',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ProductApiFactoryDecorator' => 'getProductApiFactoryDecoratorService.php',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ProductSearchApiFactoryDecorator' => 'getProductSearchApiFactoryDecoratorService.php',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\TasticService' => 'getTasticServiceService.php',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Gateway\\AppGateway' => 'getAppGatewayService.php',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Gateway\\AppRepositoryGateway' => 'getAppRepositoryGatewayService.php',
            'Frontastic\\Catwalk\\ApiCoreBundle\\Gateway\\TasticGateway' => 'getTasticGatewayService.php',
            'Frontastic\\Catwalk\\DevVmBundle\\Command\\SyncCommand' => 'getSyncCommandService.php',
            'Frontastic\\Catwalk\\DevVmBundle\\Controller\\SyncController' => 'getSyncControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Command\\AnnounceReleaseCommand' => 'getAnnounceReleaseCommandService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Command\\ClearCommand' => 'getClearCommandService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Command\\ClearOrphanedCachesCommand' => 'getClearOrphanedCachesCommandService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Command\\CreateBundleCommand' => 'getCreateBundleCommandService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Command\\CronCommand' => 'getCronCommandService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Command\\DumpCategoriesCommand' => 'getDumpCategoriesCommandService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Command\\DumpProductsCommand' => 'getDumpProductsCommandService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Command\\GenerateSitemapsCommand' => 'getGenerateSitemapsCommandService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Command\\RebuildRoutesCommand' => 'getRebuildRoutesCommandService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Command\\SendNewOrderMailsCommand' => 'getSendNewOrderMailsCommandService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\AccountController' => 'getAccountControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\CategoryController' => 'getCategoryControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\CheckoutController' => 'getCheckoutControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\ContentController' => 'getContentControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\ErrorController' => 'getErrorControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\FacetController' => 'getFacetControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\NodeController' => 'getNodeControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\PatternLibraryController' => 'getPatternLibraryControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\PreviewController' => 'getPreviewControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\ProductController' => 'getProductControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\ProxyController' => 'getProxyControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\SearchController' => 'getSearchControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Controller\\TasticController' => 'getTasticControllerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\EnabledFacetService' => 'getEnabledFacetServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\FacetService' => 'getFacetServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\FeatureFlagService' => 'getFeatureFlagServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\MasterService' => 'getMasterServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\NodeService' => 'getNodeServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\PageImpressionRecorder' => 'getPageImpressionRecorderService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\PageService' => 'getPageServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\PreviewService' => 'getPreviewServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\ProjectConfigurationService' => 'getProjectConfigurationServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\RedirectService' => 'getRedirectServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\RenderService' => 'getRenderServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\RouteService' => 'getRouteServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\SchemaService' => 'getSchemaServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\SitemapService' => 'getSitemapServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\StreamHandler\\AccountAddresses' => 'getAccountAddressesService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\StreamHandler\\AccountOrders' => 'getAccountOrdersService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\StreamHandler\\AccountWishlists' => 'getAccountWishlistsService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\StreamHandler\\Content' => 'getContentService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\StreamHandler\\ContentList' => 'getContentListService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\StreamHandler\\Order' => 'getOrderService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\StreamHandler\\Product' => 'getProductService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\StreamHandler\\ProductList' => 'getProductListService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\StreamService' => 'getStreamServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\TasticFieldHandler\\AccountWishlistsFieldHandler' => 'getAccountWishlistsFieldHandlerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\TasticFieldHandler\\BreadcrumbFieldHandler' => 'getBreadcrumbFieldHandlerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\TasticFieldHandler\\TreeFieldHandler' => 'getTreeFieldHandlerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\TasticFieldService' => 'getTasticFieldServiceService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Domain\\ViewDataProvider' => 'getViewDataProviderService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ErrorHandler' => 'getErrorHandlerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ErrorHandler\\ErrorNodeRenderer' => 'getErrorNodeRendererService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\MissingHomepageRouteListener' => 'getMissingHomepageRouteListenerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RedirectListener' => 'getRedirectListenerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Gateway\\FacetGateway' => 'getFacetGatewayService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Gateway\\LayoutGateway' => 'getLayoutGatewayService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Gateway\\MasterPageMatcherRulesGateway' => 'getMasterPageMatcherRulesGatewayService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Gateway\\NodeGateway' => 'getNodeGatewayService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Gateway\\PageGateway' => 'getPageGatewayService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Gateway\\PreviewGateway' => 'getPreviewGatewayService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Gateway\\ProjectConfigurationGateway' => 'getProjectConfigurationGatewayService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Gateway\\RedirectGateway' => 'getRedirectGatewayService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Gateway\\SchemaGateway' => 'getSchemaGatewayService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Routing\\Loader' => 'getLoaderService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Routing\\ObjectRouter\\ContentRouter' => 'getContentRouterService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Routing\\ObjectRouter\\ProductRouter' => 'getProductRouterService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Routing\\UrlGenerator' => 'getUrlGeneratorService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\RulerZ\\Operator\\ArrayContains' => 'getArrayContainsService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\RulerZ\\Operator\\CategoriesContain' => 'getCategoriesContainService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\RulerZ\\Operator\\CategoryPathContains' => 'getCategoryPathContainsService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Security\\AccountProvider' => 'getAccountProviderService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Security\\Authenticator' => 'getAuthenticatorService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Security\\LogoutSuccessHandler' => 'getLogoutSuccessHandlerService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Twig\\AssetExtension' => 'getAssetExtensionService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Twig\\ContextExtension' => 'getContextExtensionService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Twig\\FrontasticNodeViewFallbackTemplateGuesser' => 'getFrontasticNodeViewFallbackTemplateGuesserService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Twig\\JsonExtension' => 'getJsonExtensionService.php',
            'Frontastic\\Catwalk\\FrontendBundle\\Twig\\RenderExtension' => 'getRenderExtensionService.php',
            'Frontastic\\Common\\AccountApiBundle\\Command\\CreateAccountCommand' => 'getCreateAccountCommandService.php',
            'Frontastic\\Common\\AccountApiBundle\\Domain\\AccountApiFactory' => 'getAccountApiFactoryService.php',
            'Frontastic\\Common\\AccountApiBundle\\Domain\\AccountApi\\Commercetools\\Mapper' => 'getMapperService.php',
            'Frontastic\\Common\\AccountApiBundle\\Domain\\AccountService' => 'getAccountServiceService.php',
            'Frontastic\\Common\\CartApiBundle\\Domain\\CartApiFactory' => 'getCartApiFactoryService.php',
            'Frontastic\\Common\\CartApiBundle\\Domain\\CartApi\\Commercetools\\Mapper' => 'getMapper2Service.php',
            'Frontastic\\Common\\CartApiBundle\\Domain\\OrderIdGenerator\\Random' => 'getRandomService.php',
            'Frontastic\\Common\\ContentApiBundle\\Domain\\DefaultContentApiFactory' => 'getDefaultContentApiFactoryService.php',
            'Frontastic\\Common\\CoreBundle\\Domain\\Mailer' => 'getMailerService.php',
            'Frontastic\\Common\\CoreBundle\\Domain\\RequestProvider' => 'getRequestProviderService.php',
            'Frontastic\\Common\\CoreBundle\\EventListener\\JsonExceptionListener' => 'getJsonExceptionListenerService.php',
            'Frontastic\\Common\\CoreBundle\\EventListener\\JsonViewListener' => 'getJsonViewListenerService.php',
            'Frontastic\\Common\\FindologicBundle\\Domain\\FindologicClientFactory' => 'getFindologicClientFactoryService.php',
            'Frontastic\\Common\\FindologicBundle\\Domain\\ProductSearchApi\\Mapper' => 'getMapper3Service.php',
            'Frontastic\\Common\\FindologicBundle\\Domain\\ProductSearchApi\\QueryValidator' => 'getQueryValidatorService.php',
            'Frontastic\\Common\\HttpClient' => 'getHttpClientService.php',
            'Frontastic\\Common\\HttpClient\\Factory' => 'getFactoryService.php',
            'Frontastic\\Common\\HttpClient\\Options' => 'getOptionsService.php',
            'Frontastic\\Common\\HttpClient\\Signing' => 'getSigningService.php',
            'Frontastic\\Common\\JsonSerializer' => 'getJsonSerializerService.php',
            'Frontastic\\Common\\ProductApiBundle\\Domain\\DefaultProductApiFactory' => 'getDefaultProductApiFactoryService.php',
            'Frontastic\\Common\\ProductApiBundle\\Domain\\ProductApi\\Commercetools\\ClientFactory' => 'getClientFactory2Service.php',
            'Frontastic\\Common\\ProductApiBundle\\Domain\\ProductApi\\Commercetools\\Locale\\DefaultCommercetoolsLocaleCreatorFactory' => 'getDefaultCommercetoolsLocaleCreatorFactoryService.php',
            'Frontastic\\Common\\ProductApiBundle\\Domain\\ProductApi\\Commercetools\\Mapper' => 'getMapper4Service.php',
            'Frontastic\\Common\\ProductApiBundle\\Domain\\ProductApi\\EmptyEnabledFacetService' => 'getEmptyEnabledFacetServiceService.php',
            'Frontastic\\Common\\ProjectApiBundle\\Domain\\DefaultProjectApiFactory' => 'getDefaultProjectApiFactoryService.php',
            'Frontastic\\Common\\ReplicatorBundle\\Domain\\Customer' => 'getCustomerService.php',
            'Frontastic\\Common\\ReplicatorBundle\\Domain\\EndpointService' => 'getEndpointServiceService.php',
            'Frontastic\\Common\\ReplicatorBundle\\Domain\\RequestVerifier' => 'getRequestVerifierService.php',
            'Frontastic\\Common\\ReplicatorBundle\\Domain\\SequenceProvider' => 'getSequenceProviderService.php',
            'Frontastic\\Common\\SapCommerceCloudBundle\\Domain\\Locale\\DefaultSapLocaleCreatorFactory' => 'getDefaultSapLocaleCreatorFactoryService.php',
            'Frontastic\\Common\\SapCommerceCloudBundle\\Domain\\SapClientFactory' => 'getSapClientFactoryService.php',
            'Frontastic\\Common\\ShopifyBundle\\Domain\\Mapper\\ShopifyAccountMapper' => 'getShopifyAccountMapperService.php',
            'Frontastic\\Common\\ShopifyBundle\\Domain\\Mapper\\ShopifyProductMapper' => 'getShopifyProductMapperService.php',
            'Frontastic\\Common\\ShopifyBundle\\Domain\\ShopifyClientFactory' => 'getShopifyClientFactoryService.php',
            'Frontastic\\Common\\ShopwareBundle\\Domain\\ClientFactory' => 'getClientFactory3Service.php',
            'Frontastic\\Common\\ShopwareBundle\\Domain\\DataMapper\\DataMapperResolver' => 'getDataMapperResolverService.php',
            'Frontastic\\Common\\ShopwareBundle\\Domain\\Locale\\LocaleCreatorFactory' => 'getLocaleCreatorFactoryService.php',
            'Frontastic\\Common\\ShopwareBundle\\Domain\\ProjectConfigApi\\ShopwareProjectConfigApiFactory' => 'getShopwareProjectConfigApiFactoryService.php',
            'Frontastic\\Common\\SprykerBundle\\Domain\\Account\\AccountHelper' => 'getAccountHelperService.php',
            'Frontastic\\Common\\SprykerBundle\\Domain\\Account\\SessionService' => 'getSessionServiceService.php',
            'Frontastic\\Common\\SprykerBundle\\Domain\\Account\\TokenDecoder' => 'getTokenDecoderService.php',
            'Frontastic\\Common\\SprykerBundle\\Domain\\Locale\\LocaleCreatorFactory' => 'getLocaleCreatorFactory2Service.php',
            'Frontastic\\Common\\SprykerBundle\\Domain\\MapperResolver' => 'getMapperResolverService.php',
            'Frontastic\\Common\\SprykerBundle\\Domain\\SprykerClientFactory' => 'getSprykerClientFactoryService.php',
            'Frontastic\\Common\\SprykerBundle\\Domain\\SprykerUrlAppender' => 'getSprykerUrlAppenderService.php',
            'Frontastic\\Common\\WishlistApiBundle\\Domain\\WishlistApiFactory' => 'getWishlistApiFactoryService.php',
            'Symfony\\Bundle\\FrameworkBundle\\Controller\\RedirectController' => 'getRedirectControllerService.php',
            'Symfony\\Bundle\\FrameworkBundle\\Controller\\TemplateController' => 'getTemplateControllerService.php',
            'cache.app' => 'getCache_AppService.php',
            'cache.app_clearer' => 'getCache_AppClearerService.php',
            'cache.global_clearer' => 'getCache_GlobalClearerService.php',
            'cache.system' => 'getCache_SystemService.php',
            'cache.system_clearer' => 'getCache_SystemClearerService.php',
            'cache_clearer' => 'getCacheClearerService.php',
            'cache_warmer' => 'getCacheWarmerService.php',
            'console.command.public_alias.doctrine_cache.contains_command' => 'getConsole_Command_PublicAlias_DoctrineCache_ContainsCommandService.php',
            'console.command.public_alias.doctrine_cache.delete_command' => 'getConsole_Command_PublicAlias_DoctrineCache_DeleteCommandService.php',
            'console.command.public_alias.doctrine_cache.flush_command' => 'getConsole_Command_PublicAlias_DoctrineCache_FlushCommandService.php',
            'console.command.public_alias.doctrine_cache.stats_command' => 'getConsole_Command_PublicAlias_DoctrineCache_StatsCommandService.php',
            'console.command_loader' => 'getConsole_CommandLoaderService.php',
            'container.env_var_processors_locator' => 'getContainer_EnvVarProcessorsLocatorService.php',
            'doctrine.dbal.default_connection' => 'getDoctrine_Dbal_DefaultConnectionService.php',
            'doctrine.orm.default_entity_manager' => 'getDoctrine_Orm_DefaultEntityManagerService.php',
            'error_controller' => 'getErrorController2Service.php',
            'filesystem' => 'getFilesystemService.php',
            'form.factory' => 'getForm_FactoryService.php',
            'form.type.file' => 'getForm_Type_FileService.php',
            'frontastic.catwalk.account_api' => 'getFrontastic_Catwalk_AccountApiService.php',
            'frontastic.catwalk.api_core_bundle.domain.app_data_target' => 'getFrontastic_Catwalk_ApiCoreBundle_Domain_AppDataTargetService.php',
            'frontastic.catwalk.api_core_bundle.domain.tastic_target' => 'getFrontastic_Catwalk_ApiCoreBundle_Domain_TasticTargetService.php',
            'frontastic.catwalk.cart_api' => 'getFrontastic_Catwalk_CartApiService.php',
            'frontastic.catwalk.content_api' => 'getFrontastic_Catwalk_ContentApiService.php',
            'frontastic.catwalk.frontend_bundle.domain.facet.replication_target' => 'getFrontastic_Catwalk_FrontendBundle_Domain_Facet_ReplicationTargetService.php',
            'frontastic.catwalk.frontend_bundle.domain.node.replication_target' => 'getFrontastic_Catwalk_FrontendBundle_Domain_Node_ReplicationTargetService.php',
            'frontastic.catwalk.frontend_bundle.domain.page.replication_target' => 'getFrontastic_Catwalk_FrontendBundle_Domain_Page_ReplicationTargetService.php',
            'frontastic.catwalk.frontend_bundle.domain.project_configuration.replication_target' => 'getFrontastic_Catwalk_FrontendBundle_Domain_ProjectConfiguration_ReplicationTargetService.php',
            'frontastic.catwalk.frontend_bundle.domain.redirect.replication_target' => 'getFrontastic_Catwalk_FrontendBundle_Domain_Redirect_ReplicationTargetService.php',
            'frontastic.catwalk.frontend_bundle.domain.schema.replication_target' => 'getFrontastic_Catwalk_FrontendBundle_Domain_Schema_ReplicationTargetService.php',
            'frontastic.catwalk.product_api' => 'getFrontastic_Catwalk_ProductApiService.php',
            'frontastic.catwalk.product_search_api' => 'getFrontastic_Catwalk_ProductSearchApiService.php',
            'frontastic.catwalk.wishlist_api' => 'getFrontastic_Catwalk_WishlistApiService.php',
            'frontastic.user.guard_handler' => 'getFrontastic_User_GuardHandlerService.php',
            'qafoo_labs_noframework.controller_utils' => 'getQafooLabsNoframework_ControllerUtilsService.php',
            'routing.loader' => 'getRouting_LoaderService.php',
            'rulerz.command.cache_clear' => 'getRulerz_Command_CacheClearService.php',
            'security.authentication_utils' => 'getSecurity_AuthenticationUtilsService.php',
            'security.csrf.token_manager' => 'getSecurity_Csrf_TokenManagerService.php',
            'security.password_encoder' => 'getSecurity_PasswordEncoderService.php',
            'serializer' => 'getSerializerService.php',
            'services_resetter' => 'getServicesResetterService.php',
            'session' => 'getSessionService.php',
            'swiftmailer.mailer.default' => 'getSwiftmailer_Mailer_DefaultService.php',
            'swiftmailer.mailer.default.transport.real' => 'getSwiftmailer_Mailer_Default_Transport_RealService.php',
            'swiftmailer.transport' => 'getSwiftmailer_TransportService.php',
            'templating' => 'getTemplatingService.php',
            'templating.loader' => 'getTemplating_LoaderService.php',
            'translator' => 'getTranslatorService.php',
            'twig' => 'getTwigService.php',
            'twig.controller.exception' => 'getTwig_Controller_ExceptionService.php',
            'twig.controller.preview_error' => 'getTwig_Controller_PreviewErrorService.php',
            'validator' => 'getValidatorService.php',
        ];
        $this->aliases = [
            'Frontastic\\Common\\ContentApiBundle\\Domain\\ContentApiFactory' => 'Frontastic\\Common\\ContentApiBundle\\Domain\\DefaultContentApiFactory',
            'Frontastic\\Common\\HttpClient\\Stream' => 'Frontastic\\Common\\HttpClient',
            'Frontastic\\Common\\ProductApiBundle\\Domain\\ProductApi' => 'frontastic.catwalk.product_api',
            'Frontastic\\Common\\ProductApiBundle\\Domain\\ProductApiFactory' => 'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ProductApiFactoryDecorator',
            'Frontastic\\Common\\ProductApiBundle\\Domain\\ProductApi\\Commercetools\\Locale\\CommercetoolsLocaleCreatorFactory' => 'Frontastic\\Common\\ProductApiBundle\\Domain\\ProductApi\\Commercetools\\Locale\\DefaultCommercetoolsLocaleCreatorFactory',
            'Frontastic\\Common\\ProductApiBundle\\Domain\\ProductApi\\EnabledFacetService' => 'Frontastic\\Catwalk\\FrontendBundle\\Domain\\EnabledFacetService',
            'Frontastic\\Common\\ProductSearchApiBundle\\Domain\\ProductSearchApi' => 'frontastic.catwalk.product_search_api',
            'Frontastic\\Common\\ProductSearchApiBundle\\Domain\\ProductSearchApiFactory' => 'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ProductSearchApiFactoryDecorator',
            'Frontastic\\Common\\ProjectApiBundle\\Domain\\ProjectApiFactory' => 'Frontastic\\Common\\ProjectApiBundle\\Domain\\DefaultProjectApiFactory',
            'Frontastic\\Common\\SapCommerceCloudBundle\\Domain\\Locale\\SapLocaleCreatorFactory' => 'Frontastic\\Common\\SapCommerceCloudBundle\\Domain\\Locale\\DefaultSapLocaleCreatorFactory',
            'database_connection' => 'doctrine.dbal.default_connection',
            'doctrine.orm.entity_manager' => 'doctrine.orm.default_entity_manager',
            'frontastic.order-id-generator' => 'Frontastic\\Common\\CartApiBundle\\Domain\\OrderIdGenerator\\Random',
            'mailer' => 'swiftmailer.mailer.default',
        ];
    }

    public function compile(): void
    {
        throw new LogicException('You cannot compile a dumped container that was already compiled.');
    }

    public function isCompiled(): bool
    {
        return true;
    }

    public function getRemovedIds(): array
    {
        return require $this->containerDir.\DIRECTORY_SEPARATOR.'removed-ids.php';
    }

    protected function load($file, $lazyLoad = true)
    {
        return require $this->containerDir.\DIRECTORY_SEPARATOR.$file;
    }

    /*
     * Gets the public 'Frontastic\Catwalk\ApiCoreBundle\Controller\ParameterConverter\ContextConverter' shared service.
     *
     * @return \Frontastic\Catwalk\ApiCoreBundle\Controller\ParameterConverter\ContextConverter
     */
    protected function getContextConverterService()
    {
        return $this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Controller\\ParameterConverter\\ContextConverter'] = new \Frontastic\Catwalk\ApiCoreBundle\Controller\ParameterConverter\ContextConverter(($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ContextService'] ?? $this->getContextServiceService()));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService' shared autowired service.
     *
     * @return \Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService
     */
    protected function getContextServiceService()
    {
        return $this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ContextService'] = new \Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService(($this->services['router'] ?? $this->getRouterService()), ($this->services['request_stack'] ?? ($this->services['request_stack'] = new \Symfony\Component\HttpFoundation\RequestStack())), ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\CustomerService'] ?? ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\CustomerService'] = new \Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService((\dirname(__DIR__, 5).'/'.$this->getEnv('string:default:frontastic_default_project_file:frontastic_project_file'))))), ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ProjectService'] ?? $this->getProjectServiceService()), ($this->services['security.token_storage'] ?? $this->getSecurity_TokenStorageService()), ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\Context\\LocaleResolver'] ?? ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\Context\\LocaleResolver'] = new \Frontastic\Catwalk\ApiCoreBundle\Domain\Context\LocaleResolver())), new RewindableGenerator(function () {
            yield 0 => ($this->services['Frontastic\\Catwalk\\FrontendBundle\\Domain\\SchemaService'] ?? $this->load('getSchemaServiceService.php'));
            yield 1 => ($this->services['Frontastic\\Catwalk\\FrontendBundle\\Domain\\ProjectConfigurationService'] ?? $this->load('getProjectConfigurationServiceService.php'));
            yield 2 => ($this->services['Frontastic\\Catwalk\\FrontendBundle\\Domain\\FeatureFlagService'] ?? $this->load('getFeatureFlagServiceService.php'));
        }, 3));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\ApiCoreBundle\Domain\Context\LocaleResolver' shared service.
     *
     * @return \Frontastic\Catwalk\ApiCoreBundle\Domain\Context\LocaleResolver
     */
    protected function getLocaleResolverService()
    {
        return $this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\Context\\LocaleResolver'] = new \Frontastic\Catwalk\ApiCoreBundle\Domain\Context\LocaleResolver();
    }

    /*
     * Gets the public 'Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService' shared service.
     *
     * @return \Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService
     */
    protected function getCustomerServiceService()
    {
        return $this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\CustomerService'] = new \Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService((\dirname(__DIR__, 5).'/'.$this->getEnv('string:default:frontastic_default_project_file:frontastic_project_file')));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\ApiCoreBundle\Domain\ProjectService' shared autowired service.
     *
     * @return \Frontastic\Catwalk\ApiCoreBundle\Domain\ProjectService
     */
    protected function getProjectServiceService()
    {
        return $this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ProjectService'] = new \Frontastic\Catwalk\ApiCoreBundle\Domain\ProjectService(($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\CustomerService'] ?? ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\CustomerService'] = new \Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService((\dirname(__DIR__, 5).'/'.$this->getEnv('string:default:frontastic_default_project_file:frontastic_project_file'))))));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\ApiCoreBundle\Monolog\FrontasticLogProcessor' shared autowired service.
     *
     * @return \Frontastic\Catwalk\ApiCoreBundle\Monolog\FrontasticLogProcessor
     */
    protected function getFrontasticLogProcessorService()
    {
        return $this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Monolog\\FrontasticLogProcessor'] = new \Frontastic\Catwalk\ApiCoreBundle\Monolog\FrontasticLogProcessor(($this->services['request_stack'] ?? ($this->services['request_stack'] = new \Symfony\Component\HttpFoundation\RequestStack())));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\ApiCoreBundle\Monolog\JsonFormatter' shared service.
     *
     * @return \Frontastic\Catwalk\ApiCoreBundle\Monolog\JsonFormatter
     */
    protected function getJsonFormatterService()
    {
        return $this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Monolog\\JsonFormatter'] = new \Frontastic\Catwalk\ApiCoreBundle\Monolog\JsonFormatter(($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\CustomerService'] ?? ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\CustomerService'] = new \Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService((\dirname(__DIR__, 5).'/'.$this->getEnv('string:default:frontastic_default_project_file:frontastic_project_file'))))));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\FrontendBundle\Domain\RenderService\ResponseDecorator' shared service.
     *
     * @return \Frontastic\Catwalk\FrontendBundle\Domain\RenderService\ResponseDecorator
     */
    protected function getResponseDecoratorService()
    {
        return $this->services['Frontastic\\Catwalk\\FrontendBundle\\Domain\\RenderService\\ResponseDecorator'] = new \Frontastic\Catwalk\FrontendBundle\Domain\RenderService\ResponseDecorator();
    }

    /*
     * Gets the public 'Frontastic\Catwalk\FrontendBundle\EventListener\CacheDirectives' shared service.
     *
     * @return \Frontastic\Catwalk\FrontendBundle\EventListener\CacheDirectives
     */
    protected function getCacheDirectivesService()
    {
        return $this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\CacheDirectives'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\CacheDirectives();
    }

    /*
     * Gets the public 'Frontastic\Catwalk\FrontendBundle\EventListener\ContentSecurityPolicy' shared service.
     *
     * @return \Frontastic\Catwalk\FrontendBundle\EventListener\ContentSecurityPolicy
     */
    protected function getContentSecurityPolicyService()
    {
        return $this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ContentSecurityPolicy'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\ContentSecurityPolicy(($this->services['Frontastic\\Common\\ReplicatorBundle\\Domain\\Project'] ?? $this->getProjectService()));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\FrontendBundle\EventListener\EmbedContext' shared service.
     *
     * @return \Frontastic\Catwalk\FrontendBundle\EventListener\EmbedContext
     */
    protected function getEmbedContextService()
    {
        return $this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\EmbedContext'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\EmbedContext(($this->services['Frontastic\\Catwalk\\FrontendBundle\\Twig\\NodeExtension'] ?? $this->getNodeExtensionService()));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\FrontendBundle\EventListener\Http2LinkListener' shared service.
     *
     * @return \Frontastic\Catwalk\FrontendBundle\EventListener\Http2LinkListener
     */
    protected function getHttp2LinkListenerService()
    {
        return $this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\Http2LinkListener'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\Http2LinkListener(\dirname(__DIR__, 5));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\FrontendBundle\EventListener\ProjectBasicAuthListener' shared service.
     *
     * @return \Frontastic\Catwalk\FrontendBundle\EventListener\ProjectBasicAuthListener
     */
    protected function getProjectBasicAuthListenerService()
    {
        return $this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ProjectBasicAuthListener'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\ProjectBasicAuthListener(($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ContextService'] ?? $this->getContextServiceService()));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\FrontendBundle\EventListener\RebuildRoutesListener' shared service.
     *
     * @return \Frontastic\Catwalk\FrontendBundle\EventListener\RebuildRoutesListener
     */
    protected function getRebuildRoutesListenerService()
    {
        return $this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RebuildRoutesListener'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\RebuildRoutesListener($this, ($this->services['logger'] ?? $this->getLoggerService()));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\FrontendBundle\EventListener\ReferrerPolicy' shared service.
     *
     * @return \Frontastic\Catwalk\FrontendBundle\EventListener\ReferrerPolicy
     */
    protected function getReferrerPolicyService()
    {
        return $this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ReferrerPolicy'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\ReferrerPolicy(($this->services['Frontastic\\Common\\ReplicatorBundle\\Domain\\Project'] ?? $this->getProjectService()));
    }

    /*
     * Gets the public 'Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener' shared autowired service.
     *
     * @return \Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener
     */
    protected function getRequestIdListenerService()
    {
        return $this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RequestIdListener'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener();
    }

    /*
     * Gets the public 'Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdResponseHeaderListener' shared autowired service.
     *
     * @return \Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdResponseHeaderListener
     */
    protected function getRequestIdResponseHeaderListenerService()
    {
        return $this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RequestIdResponseHeaderListener'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdResponseHeaderListener();
    }

    /*
     * Gets the public 'Frontastic\Catwalk\FrontendBundle\Twig\NodeExtension' shared service.
     *
     * @return \Frontastic\Catwalk\FrontendBundle\Twig\NodeExtension
     */
    protected function getNodeExtensionService()
    {
        return $this->services['Frontastic\\Catwalk\\FrontendBundle\\Twig\\NodeExtension'] = new \Frontastic\Catwalk\FrontendBundle\Twig\NodeExtension($this, ($this->privates['cache.app.simple'] ?? $this->load('getCache_App_SimpleService.php')));
    }

    /*
     * Gets the public 'Frontastic\Common\ReplicatorBundle\Domain\Project' shared service.
     *
     * @return \Frontastic\Common\ReplicatorBundle\Domain\Project
     */
    protected function getProjectService()
    {
        return $this->services['Frontastic\\Common\\ReplicatorBundle\\Domain\\Project'] = ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ProjectService'] ?? $this->getProjectServiceService())->getProject();
    }

    /*
     * Gets the public 'doctrine' shared service.
     *
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrineService()
    {
        return $this->services['doctrine'] = new \Doctrine\Bundle\DoctrineBundle\Registry($this, $this->parameters['doctrine.connections'], $this->parameters['doctrine.entity_managers'], 'default', 'default');
    }

    /*
     * Gets the public 'event_dispatcher' shared service.
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected function getEventDispatcherService()
    {
        $this->services['event_dispatcher'] = $instance = new \Symfony\Component\EventDispatcher\EventDispatcher();

        $instance->addListener('kernel.view', [0 => function () {
            return ($this->privates['qafoo_labs_noframework.view_listener'] ?? $this->load('getQafooLabsNoframework_ViewListenerService.php'));
        }, 1 => 'onKernelView'], 10);
        $instance->addListener('kernel.view', [0 => function () {
            return ($this->privates['qafoo_labs_noframework.redirect_listener'] ?? $this->load('getQafooLabsNoframework_RedirectListenerService.php'));
        }, 1 => 'onKernelView'], 20);
        $instance->addListener('kernel.controller', [0 => function () {
            return ($this->privates['qafoo_labs_noframework.param_converter_listener'] ?? $this->getQafooLabsNoframework_ParamConverterListenerService());
        }, 1 => 'onKernelController'], 0);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->privates['qafoo_labs_noframework.turbolinks_listener'] ?? ($this->privates['qafoo_labs_noframework.turbolinks_listener'] = new \QafooLabs\Bundle\NoFrameworkBundle\EventListener\TurbolinksListener()));
        }, 1 => 'onKernelResponse'], 0);
        $instance->addListener('kernel.exception', [0 => function () {
            return ($this->services['Frontastic\\Common\\CoreBundle\\EventListener\\JsonExceptionListener'] ?? ($this->services['Frontastic\\Common\\CoreBundle\\EventListener\\JsonExceptionListener'] = new \Frontastic\Common\CoreBundle\EventListener\JsonExceptionListener(false)));
        }, 1 => 'onKernelException'], 0);
        $instance->addListener('kernel.view', [0 => function () {
            return ($this->services['Frontastic\\Common\\CoreBundle\\EventListener\\JsonViewListener'] ?? $this->load('getJsonViewListenerService.php'));
        }, 1 => 'onKernelView'], 50);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\CacheDirectives'] ?? ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\CacheDirectives'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\CacheDirectives()));
        }, 1 => 'onKernelResponse'], 0);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ContentSecurityPolicy'] ?? $this->getContentSecurityPolicyService());
        }, 1 => 'onKernelResponse'], 0);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\EmbedContext'] ?? $this->getEmbedContextService());
        }, 1 => 'onKernelResponse'], 0);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\Http2LinkListener'] ?? ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\Http2LinkListener'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\Http2LinkListener(\dirname(__DIR__, 5))));
        }, 1 => 'onKernelRequest'], 0);
        $instance->addListener('kernel.exception', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\MissingHomepageRouteListener'] ?? $this->load('getMissingHomepageRouteListenerService.php'));
        }, 1 => 'onKernelException'], 0);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ProjectBasicAuthListener'] ?? $this->getProjectBasicAuthListenerService());
        }, 1 => 'onKernelRequest'], 0);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RebuildRoutesListener'] ?? $this->getRebuildRoutesListenerService());
        }, 1 => 'onKernelRequest'], 0);
        $instance->addListener('kernel.exception', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RedirectListener'] ?? $this->load('getRedirectListenerService.php'));
        }, 1 => 'onKernelException'], 10);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ReferrerPolicy'] ?? $this->getReferrerPolicyService());
        }, 1 => 'onKernelResponse'], 0);
        $instance->addListener('kernel.terminate', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\Domain\\PageImpressionRecorder'] ?? $this->load('getPageImpressionRecorderService.php'));
        }, 1 => 'onKernelTerminate'], 0);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\Domain\\RenderService\\ResponseDecorator'] ?? ($this->services['Frontastic\\Catwalk\\FrontendBundle\\Domain\\RenderService\\ResponseDecorator'] = new \Frontastic\Catwalk\FrontendBundle\Domain\RenderService\ResponseDecorator()));
        }, 1 => 'onKernelResponse'], 0);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->privates['response_listener'] ?? ($this->privates['response_listener'] = new \Symfony\Component\HttpKernel\EventListener\ResponseListener('UTF-8')));
        }, 1 => 'onKernelResponse'], 0);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->privates['streamed_response_listener'] ?? ($this->privates['streamed_response_listener'] = new \Symfony\Component\HttpKernel\EventListener\StreamedResponseListener()));
        }, 1 => 'onKernelResponse'], -1024);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->privates['locale_listener'] ?? $this->getLocaleListenerService());
        }, 1 => 'setDefaultLocale'], 100);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->privates['locale_listener'] ?? $this->getLocaleListenerService());
        }, 1 => 'onKernelRequest'], 16);
        $instance->addListener('kernel.finish_request', [0 => function () {
            return ($this->privates['locale_listener'] ?? $this->getLocaleListenerService());
        }, 1 => 'onKernelFinishRequest'], 0);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->privates['validate_request_listener'] ?? ($this->privates['validate_request_listener'] = new \Symfony\Component\HttpKernel\EventListener\ValidateRequestListener()));
        }, 1 => 'onKernelRequest'], 256);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->privates['.legacy_resolve_controller_name_subscriber'] ?? $this->get_LegacyResolveControllerNameSubscriberService());
        }, 1 => 'resolveControllerName'], 24);
        $instance->addListener('console.error', [0 => function () {
            return ($this->privates['console.error_listener'] ?? $this->load('getConsole_ErrorListenerService.php'));
        }, 1 => 'onConsoleError'], -128);
        $instance->addListener('console.terminate', [0 => function () {
            return ($this->privates['console.error_listener'] ?? $this->load('getConsole_ErrorListenerService.php'));
        }, 1 => 'onConsoleTerminate'], -128);
        $instance->addListener('console.error', [0 => function () {
            return ($this->privates['console.suggest_missing_package_subscriber'] ?? ($this->privates['console.suggest_missing_package_subscriber'] = new \Symfony\Bundle\FrameworkBundle\EventListener\SuggestMissingPackageSubscriber()));
        }, 1 => 'onConsoleError'], 0);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->privates['session_listener'] ?? $this->getSessionListenerService());
        }, 1 => 'onKernelRequest'], 128);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->privates['session_listener'] ?? $this->getSessionListenerService());
        }, 1 => 'onKernelResponse'], -1000);
        $instance->addListener('kernel.finish_request', [0 => function () {
            return ($this->privates['session_listener'] ?? $this->getSessionListenerService());
        }, 1 => 'onFinishRequest'], 0);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->privates['fragment.listener'] ?? $this->getFragment_ListenerService());
        }, 1 => 'onKernelRequest'], 48);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->privates['debug.debug_handlers_listener'] ?? $this->getDebug_DebugHandlersListenerService());
        }, 1 => 'configure'], 2048);
        $instance->addListener('console.command', [0 => function () {
            return ($this->privates['debug.debug_handlers_listener'] ?? $this->getDebug_DebugHandlersListenerService());
        }, 1 => 'configure'], 2048);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->privates['router_listener'] ?? $this->getRouterListenerService());
        }, 1 => 'onKernelRequest'], 32);
        $instance->addListener('kernel.finish_request', [0 => function () {
            return ($this->privates['router_listener'] ?? $this->getRouterListenerService());
        }, 1 => 'onKernelFinishRequest'], 0);
        $instance->addListener('kernel.exception', [0 => function () {
            return ($this->privates['router_listener'] ?? $this->getRouterListenerService());
        }, 1 => 'onKernelException'], -64);
        $instance->addListener('kernel.exception', [0 => function () {
            return ($this->privates['twig.exception_listener'] ?? $this->load('getTwig_ExceptionListenerService.php'));
        }, 1 => 'logKernelException'], 0);
        $instance->addListener('kernel.exception', [0 => function () {
            return ($this->privates['twig.exception_listener'] ?? $this->load('getTwig_ExceptionListenerService.php'));
        }, 1 => 'onKernelException'], -128);
        $instance->addListener('Symfony\\Component\\Mailer\\Event\\MessageEvent', [0 => function () {
            return ($this->privates['twig.mailer.message_listener'] ?? $this->load('getTwig_Mailer_MessageListenerService.php'));
        }, 1 => 'onMessage'], 0);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->privates['security.firewall'] ?? $this->getSecurity_FirewallService());
        }, 1 => 'configureLogoutUrlGenerator'], 8);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->privates['security.firewall'] ?? $this->getSecurity_FirewallService());
        }, 1 => 'onKernelRequest'], 8);
        $instance->addListener('kernel.finish_request', [0 => function () {
            return ($this->privates['security.firewall'] ?? $this->getSecurity_FirewallService());
        }, 1 => 'onKernelFinishRequest'], 0);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->privates['security.rememberme.response_listener'] ?? ($this->privates['security.rememberme.response_listener'] = new \Symfony\Component\Security\Http\RememberMe\ResponseListener()));
        }, 1 => 'onKernelResponse'], 0);
        $instance->addListener('kernel.exception', [0 => function () {
            return ($this->privates['swiftmailer.email_sender.listener'] ?? $this->load('getSwiftmailer_EmailSender_ListenerService.php'));
        }, 1 => 'onException'], 0);
        $instance->addListener('kernel.terminate', [0 => function () {
            return ($this->privates['swiftmailer.email_sender.listener'] ?? $this->load('getSwiftmailer_EmailSender_ListenerService.php'));
        }, 1 => 'onTerminate'], 0);
        $instance->addListener('console.error', [0 => function () {
            return ($this->privates['swiftmailer.email_sender.listener'] ?? $this->load('getSwiftmailer_EmailSender_ListenerService.php'));
        }, 1 => 'onException'], 0);
        $instance->addListener('console.terminate', [0 => function () {
            return ($this->privates['swiftmailer.email_sender.listener'] ?? $this->load('getSwiftmailer_EmailSender_ListenerService.php'));
        }, 1 => 'onTerminate'], 0);
        $instance->addListener('kernel.controller', [0 => function () {
            return ($this->privates['sensio_framework_extra.controller.listener'] ?? $this->getSensioFrameworkExtra_Controller_ListenerService());
        }, 1 => 'onKernelController'], 0);
        $instance->addListener('kernel.controller', [0 => function () {
            return ($this->privates['sensio_framework_extra.converter.listener'] ?? $this->getSensioFrameworkExtra_Converter_ListenerService());
        }, 1 => 'onKernelController'], 0);
        $instance->addListener('kernel.controller', [0 => function () {
            return ($this->privates['sensio_framework_extra.cache.listener'] ?? ($this->privates['sensio_framework_extra.cache.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\HttpCacheListener()));
        }, 1 => 'onKernelController'], 0);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->privates['sensio_framework_extra.cache.listener'] ?? ($this->privates['sensio_framework_extra.cache.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\HttpCacheListener()));
        }, 1 => 'onKernelResponse'], 0);
        $instance->addListener('kernel.controller_arguments', [0 => function () {
            return ($this->privates['sensio_framework_extra.security.listener'] ?? $this->getSensioFrameworkExtra_Security_ListenerService());
        }, 1 => 'onKernelControllerArguments'], 0);
        $instance->addListener('kernel.controller_arguments', [0 => function () {
            return ($this->privates['framework_extra_bundle.event.is_granted'] ?? $this->getFrameworkExtraBundle_Event_IsGrantedService());
        }, 1 => 'onKernelControllerArguments'], 0);
        $instance->addListener('kernel.view', [0 => function () {
            return ($this->privates['sensio_framework_extra.psr7.listener.response'] ?? $this->load('getSensioFrameworkExtra_Psr7_Listener_ResponseService.php'));
        }, 1 => 'onKernelView'], 0);
        $instance->addListener('Symfony\\Component\\Messenger\\Event\\WorkerMessageHandledEvent', [0 => function () {
            return ($this->privates['doctrine.orm.messenger.event_subscriber.doctrine_clear_entity_manager'] ?? $this->load('getDoctrine_Orm_Messenger_EventSubscriber_DoctrineClearEntityManagerService.php'));
        }, 1 => 'onWorkerMessageHandled'], 0);
        $instance->addListener('Symfony\\Component\\Messenger\\Event\\WorkerMessageFailedEvent', [0 => function () {
            return ($this->privates['doctrine.orm.messenger.event_subscriber.doctrine_clear_entity_manager'] ?? $this->load('getDoctrine_Orm_Messenger_EventSubscriber_DoctrineClearEntityManagerService.php'));
        }, 1 => 'onWorkerMessageFailed'], 0);
        $instance->addListener('kernel.exception', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\ErrorHandler'] ?? $this->load('getErrorHandlerService.php'));
        }, 1 => 'getResponseForErrorEvent'], -100);
        $instance->addListener('kernel.request', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RequestIdListener'] ?? ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RequestIdListener'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener()));
        }, 1 => 'onKernelRequest'], 999999);
        $instance->addListener('kernel.response', [0 => function () {
            return ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RequestIdResponseHeaderListener'] ?? ($this->services['Frontastic\\Catwalk\\FrontendBundle\\EventListener\\RequestIdResponseHeaderListener'] = new \Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdResponseHeaderListener()));
        }, 1 => 'onKernelResponse'], 0);

        return $instance;
    }

    /*
     * Gets the public 'http_kernel' shared service.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernel
     */
    protected function getHttpKernelService()
    {
        return $this->services['http_kernel'] = new \Symfony\Component\HttpKernel\HttpKernel(($this->services['event_dispatcher'] ?? $this->getEventDispatcherService()), new \Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver($this, ($this->privates['monolog.logger.request'] ?? $this->getMonolog_Logger_RequestService()), ($this->privates['.legacy_controller_name_converter'] ?? ($this->privates['.legacy_controller_name_converter'] = new \Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser(($this->services['kernel'] ?? $this->get('kernel', 1)), false)))), ($this->services['request_stack'] ?? ($this->services['request_stack'] = new \Symfony\Component\HttpFoundation\RequestStack())), new \Symfony\Component\HttpKernel\Controller\ArgumentResolver(($this->privates['argument_metadata_factory'] ?? ($this->privates['argument_metadata_factory'] = new \Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory())), new RewindableGenerator(function () {
            yield 0 => ($this->privates['argument_resolver.request_attribute'] ?? ($this->privates['argument_resolver.request_attribute'] = new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestAttributeValueResolver()));
            yield 1 => ($this->privates['argument_resolver.request'] ?? ($this->privates['argument_resolver.request'] = new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver()));
            yield 2 => ($this->privates['argument_resolver.session'] ?? ($this->privates['argument_resolver.session'] = new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\SessionValueResolver()));
            yield 3 => ($this->privates['security.user_value_resolver'] ?? $this->load('getSecurity_UserValueResolverService.php'));
            yield 4 => ($this->privates['argument_resolver.service'] ?? $this->load('getArgumentResolver_ServiceService.php'));
            yield 5 => ($this->privates['argument_resolver.default'] ?? ($this->privates['argument_resolver.default'] = new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\DefaultValueResolver()));
            yield 6 => ($this->privates['argument_resolver.variadic'] ?? ($this->privates['argument_resolver.variadic'] = new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\VariadicValueResolver()));
        }, 7)));
    }

    /*
     * Gets the public 'logger' shared service.
     *
     * @return \Symfony\Bridge\Monolog\Logger
     */
    protected function getLoggerService()
    {
        $this->services['logger'] = $instance = new \Symfony\Bridge\Monolog\Logger('app');

        $instance->pushProcessor(($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Monolog\\FrontasticLogProcessor'] ?? $this->getFrontasticLogProcessorService()));
        $instance->useMicrosecondTimestamps(true);
        $instance->pushHandler(($this->privates['monolog.handler.filter_for_errors'] ?? $this->getMonolog_Handler_FilterForErrorsService()));

        return $instance;
    }

    /*
     * Gets the public 'request_stack' shared service.
     *
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function getRequestStackService()
    {
        return $this->services['request_stack'] = new \Symfony\Component\HttpFoundation\RequestStack();
    }

    /*
     * Gets the public 'router' shared service.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected function getRouterService()
    {
        $a = new \Symfony\Bridge\Monolog\Logger('router');
        $a->pushProcessor(($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Monolog\\FrontasticLogProcessor'] ?? $this->getFrontasticLogProcessorService()));
        $a->pushHandler(($this->privates['monolog.handler.filter_for_errors'] ?? $this->getMonolog_Handler_FilterForErrorsService()));

        $this->services['router'] = $instance = new \Symfony\Bundle\FrameworkBundle\Routing\Router((new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
            'routing.loader' => ['services', 'routing.loader', 'getRouting_LoaderService.php', true],
        ], [
            'routing.loader' => 'Symfony\\Component\\Config\\Loader\\LoaderInterface',
        ]))->withContext('router.default', $this), (\dirname(__DIR__, 5).'/config/routing.yml'), ['cache_dir' => $this->targetDir.'', 'debug' => false, 'generator_class' => 'Symfony\\Component\\Routing\\Generator\\CompiledUrlGenerator', 'generator_dumper_class' => 'Symfony\\Component\\Routing\\Generator\\Dumper\\CompiledUrlGeneratorDumper', 'matcher_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableCompiledUrlMatcher', 'matcher_dumper_class' => 'Symfony\\Component\\Routing\\Matcher\\Dumper\\CompiledUrlMatcherDumper', 'strict_requirements' => NULL], ($this->privates['router.request_context'] ?? $this->getRouter_RequestContextService()), ($this->privates['parameter_bag'] ?? ($this->privates['parameter_bag'] = new \Symfony\Component\DependencyInjection\ParameterBag\ContainerBag($this))), $a, $this->getEnv('locale'));

        $instance->setConfigCacheFactory(new \Symfony\Component\Config\ResourceCheckerConfigCacheFactory());

        return $instance;
    }

    /*
     * Gets the public 'security.authorization_checker' shared service.
     *
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    protected function getSecurity_AuthorizationCheckerService()
    {
        return $this->services['security.authorization_checker'] = new \Symfony\Component\Security\Core\Authorization\AuthorizationChecker(($this->services['security.token_storage'] ?? $this->getSecurity_TokenStorageService()), ($this->privates['security.authentication.manager'] ?? $this->getSecurity_Authentication_ManagerService()), ($this->privates['security.access.decision_manager'] ?? $this->getSecurity_Access_DecisionManagerService()), false);
    }

    /*
     * Gets the public 'security.token_storage' shared service.
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage
     */
    protected function getSecurity_TokenStorageService()
    {
        return $this->services['security.token_storage'] = new \Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage(($this->privates['security.untracked_token_storage'] ?? ($this->privates['security.untracked_token_storage'] = new \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage())), new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
            'session' => ['services', 'session', 'getSessionService.php', true],
        ], [
            'session' => '?',
        ]));
    }

    /*
     * Gets the private '.legacy_resolve_controller_name_subscriber' shared service.
     *
     * @return \Symfony\Bundle\FrameworkBundle\EventListener\ResolveControllerNameSubscriber
     */
    protected function get_LegacyResolveControllerNameSubscriberService()
    {
        return $this->privates['.legacy_resolve_controller_name_subscriber'] = new \Symfony\Bundle\FrameworkBundle\EventListener\ResolveControllerNameSubscriber(($this->privates['.legacy_controller_name_converter'] ?? ($this->privates['.legacy_controller_name_converter'] = new \Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser(($this->services['kernel'] ?? $this->get('kernel', 1)), false))), false);
    }

    /*
     * Gets the private 'annotations.cached_reader' shared service.
     *
     * @return \Doctrine\Common\Annotations\CachedReader
     */
    protected function getAnnotations_CachedReaderService()
    {
        return $this->privates['annotations.cached_reader'] = new \Doctrine\Common\Annotations\CachedReader(($this->privates['annotations.reader'] ?? $this->getAnnotations_ReaderService()), $this->load('getAnnotations_CacheService.php'), false);
    }

    /*
     * Gets the private 'annotations.reader' shared service.
     *
     * @return \Doctrine\Common\Annotations\AnnotationReader
     */
    protected function getAnnotations_ReaderService()
    {
        $this->privates['annotations.reader'] = $instance = new \Doctrine\Common\Annotations\AnnotationReader();

        $a = new \Doctrine\Common\Annotations\AnnotationRegistry();
        $a->registerUniqueLoader('class_exists');

        $instance->addGlobalIgnoredName('required', $a);

        return $instance;
    }

    /*
     * Gets the private 'debug.debug_handlers_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\DebugHandlersListener
     */
    protected function getDebug_DebugHandlersListenerService()
    {
        return $this->privates['debug.debug_handlers_listener'] = new \Symfony\Component\HttpKernel\EventListener\DebugHandlersListener(NULL, NULL, NULL, 0, false, ($this->privates['debug.file_link_formatter'] ?? ($this->privates['debug.file_link_formatter'] = new \Symfony\Component\HttpKernel\Debug\FileLinkFormatter(NULL))), false);
    }

    /*
     * Gets the private 'fragment.listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\FragmentListener
     */
    protected function getFragment_ListenerService()
    {
        return $this->privates['fragment.listener'] = new \Symfony\Component\HttpKernel\EventListener\FragmentListener(($this->privates['uri_signer'] ?? ($this->privates['uri_signer'] = new \Symfony\Component\HttpKernel\UriSigner($this->getEnv('secret')))), '/_fragment');
    }

    /*
     * Gets the private 'framework_extra_bundle.argument_name_convertor' shared service.
     *
     * @return \Sensio\Bundle\FrameworkExtraBundle\Request\ArgumentNameConverter
     */
    protected function getFrameworkExtraBundle_ArgumentNameConvertorService()
    {
        return $this->privates['framework_extra_bundle.argument_name_convertor'] = new \Sensio\Bundle\FrameworkExtraBundle\Request\ArgumentNameConverter(($this->privates['argument_metadata_factory'] ?? ($this->privates['argument_metadata_factory'] = new \Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory())));
    }

    /*
     * Gets the private 'framework_extra_bundle.event.is_granted' shared service.
     *
     * @return \Sensio\Bundle\FrameworkExtraBundle\EventListener\IsGrantedListener
     */
    protected function getFrameworkExtraBundle_Event_IsGrantedService()
    {
        return $this->privates['framework_extra_bundle.event.is_granted'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\IsGrantedListener(($this->privates['framework_extra_bundle.argument_name_convertor'] ?? $this->getFrameworkExtraBundle_ArgumentNameConvertorService()), ($this->services['security.authorization_checker'] ?? $this->getSecurity_AuthorizationCheckerService()));
    }

    /*
     * Gets the private 'locale_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\LocaleListener
     */
    protected function getLocaleListenerService()
    {
        return $this->privates['locale_listener'] = new \Symfony\Component\HttpKernel\EventListener\LocaleListener(($this->services['request_stack'] ?? ($this->services['request_stack'] = new \Symfony\Component\HttpFoundation\RequestStack())), $this->getEnv('locale'), ($this->services['router'] ?? $this->getRouterService()));
    }

    /*
     * Gets the private 'monolog.handler.filter_for_errors' shared service.
     *
     * @return \Monolog\Handler\FingersCrossedHandler
     */
    protected function getMonolog_Handler_FilterForErrorsService()
    {
        $a = new \Monolog\Handler\StreamHandler('/var/log/frontastic/json.log', 100, true, NULL, false);

        $b = new \Monolog\Processor\PsrLogMessageProcessor();

        $a->pushProcessor($b);
        $a->setFormatter(($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Monolog\\JsonFormatter'] ?? $this->getJsonFormatterService()));
        $c = new \Monolog\Handler\StreamHandler((\dirname(__DIR__, 4).'/log/frontastic/catwalk/prod/frontend.log'), 100, true, NULL, false);
        $c->pushProcessor($b);

        return $this->privates['monolog.handler.filter_for_errors'] = new \Monolog\Handler\FingersCrossedHandler(new \Monolog\Handler\WhatFailureGroupHandler([0 => $a, 1 => $c], true), 400, 0, true, true, 200);
    }

    /*
     * Gets the private 'monolog.logger.request' shared service.
     *
     * @return \Symfony\Bridge\Monolog\Logger
     */
    protected function getMonolog_Logger_RequestService()
    {
        $this->privates['monolog.logger.request'] = $instance = new \Symfony\Bridge\Monolog\Logger('request');

        $instance->pushProcessor(($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Monolog\\FrontasticLogProcessor'] ?? $this->getFrontasticLogProcessorService()));
        $instance->pushHandler(($this->privates['monolog.handler.filter_for_errors'] ?? $this->getMonolog_Handler_FilterForErrorsService()));

        return $instance;
    }

    /*
     * Gets the private 'parameter_bag' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ParameterBag\ContainerBag
     */
    protected function getParameterBagService()
    {
        return $this->privates['parameter_bag'] = new \Symfony\Component\DependencyInjection\ParameterBag\ContainerBag($this);
    }

    /*
     * Gets the private 'qafoo_labs_noframework.param_converter_listener' shared service.
     *
     * @return \QafooLabs\Bundle\NoFrameworkBundle\EventListener\ParamConverterListener
     */
    protected function getQafooLabsNoframework_ParamConverterListenerService()
    {
        return $this->privates['qafoo_labs_noframework.param_converter_listener'] = new \QafooLabs\Bundle\NoFrameworkBundle\EventListener\ParamConverterListener(new \QafooLabs\Bundle\NoFrameworkBundle\ParamConverter\SymfonyServiceProvider($this));
    }

    /*
     * Gets the private 'router.request_context' shared service.
     *
     * @return \Symfony\Component\Routing\RequestContext
     */
    protected function getRouter_RequestContextService()
    {
        return $this->privates['router.request_context'] = new \Symfony\Component\Routing\RequestContext('', 'GET', 'localhost', 'http', 80, 443);
    }

    /*
     * Gets the private 'router_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\RouterListener
     */
    protected function getRouterListenerService()
    {
        return $this->privates['router_listener'] = new \Symfony\Component\HttpKernel\EventListener\RouterListener(($this->services['router'] ?? $this->getRouterService()), ($this->services['request_stack'] ?? ($this->services['request_stack'] = new \Symfony\Component\HttpFoundation\RequestStack())), ($this->privates['router.request_context'] ?? $this->getRouter_RequestContextService()), ($this->privates['monolog.logger.request'] ?? $this->getMonolog_Logger_RequestService()), \dirname(__DIR__, 5), false);
    }

    /*
     * Gets the private 'security.access.decision_manager' shared service.
     *
     * @return \Symfony\Component\Security\Core\Authorization\AccessDecisionManager
     */
    protected function getSecurity_Access_DecisionManagerService()
    {
        return $this->privates['security.access.decision_manager'] = new \Symfony\Component\Security\Core\Authorization\AccessDecisionManager(new RewindableGenerator(function () {
            yield 0 => ($this->privates['security.access.authenticated_voter'] ?? $this->load('getSecurity_Access_AuthenticatedVoterService.php'));
            yield 1 => ($this->privates['security.access.simple_role_voter'] ?? ($this->privates['security.access.simple_role_voter'] = new \Symfony\Component\Security\Core\Authorization\Voter\RoleVoter()));
            yield 2 => ($this->privates['security.access.expression_voter'] ?? $this->load('getSecurity_Access_ExpressionVoterService.php'));
        }, 3), 'affirmative', false, true);
    }

    /*
     * Gets the private 'security.authentication.manager' shared service.
     *
     * @return \Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager
     */
    protected function getSecurity_Authentication_ManagerService()
    {
        $this->privates['security.authentication.manager'] = $instance = new \Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager(new RewindableGenerator(function () {
            yield 0 => ($this->privates['security.authentication.provider.guard.main'] ?? $this->load('getSecurity_Authentication_Provider_Guard_MainService.php'));
            yield 1 => ($this->privates['security.authentication.provider.anonymous.main'] ?? ($this->privates['security.authentication.provider.anonymous.main'] = new \Symfony\Component\Security\Core\Authentication\Provider\AnonymousAuthenticationProvider($this->getParameter('container.build_hash'))));
        }, 2), true);

        $instance->setEventDispatcher(($this->services['event_dispatcher'] ?? $this->getEventDispatcherService()));

        return $instance;
    }

    /*
     * Gets the private 'security.firewall' shared service.
     *
     * @return \Symfony\Bundle\SecurityBundle\EventListener\FirewallListener
     */
    protected function getSecurity_FirewallService()
    {
        return $this->privates['security.firewall'] = new \Symfony\Bundle\SecurityBundle\EventListener\FirewallListener(new \Symfony\Bundle\SecurityBundle\Security\FirewallMap(new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
            'security.firewall.map.context.assests' => ['privates', 'security.firewall.map.context.assests', 'getSecurity_Firewall_Map_Context_AssestsService.php', true],
            'security.firewall.map.context.main' => ['privates', 'security.firewall.map.context.main', 'getSecurity_Firewall_Map_Context_MainService.php', true],
            'security.firewall.map.context.replicator' => ['privates', 'security.firewall.map.context.replicator', 'getSecurity_Firewall_Map_Context_ReplicatorService.php', true],
        ], [
            'security.firewall.map.context.assests' => '?',
            'security.firewall.map.context.main' => '?',
            'security.firewall.map.context.replicator' => '?',
        ]), new RewindableGenerator(function () {
            yield 'security.firewall.map.context.assests' => ($this->privates['.security.request_matcher.o9tQaXl'] ?? ($this->privates['.security.request_matcher.o9tQaXl'] = new \Symfony\Component\HttpFoundation\RequestMatcher('^/(_(profiler|wdt)|assets)/')));
            yield 'security.firewall.map.context.replicator' => ($this->privates['.security.request_matcher.Rh3vu55'] ?? ($this->privates['.security.request_matcher.Rh3vu55'] = new \Symfony\Component\HttpFoundation\RequestMatcher('^/api/endpoint')));
            yield 'security.firewall.map.context.main' => ($this->privates['.security.request_matcher.3UEFixr'] ?? ($this->privates['.security.request_matcher.3UEFixr'] = new \Symfony\Component\HttpFoundation\RequestMatcher('^/')));
        }, 3)), ($this->services['event_dispatcher'] ?? $this->getEventDispatcherService()), ($this->privates['security.logout_url_generator'] ?? $this->getSecurity_LogoutUrlGeneratorService()));
    }

    /*
     * Gets the private 'security.logout_url_generator' shared service.
     *
     * @return \Symfony\Component\Security\Http\Logout\LogoutUrlGenerator
     */
    protected function getSecurity_LogoutUrlGeneratorService()
    {
        $this->privates['security.logout_url_generator'] = $instance = new \Symfony\Component\Security\Http\Logout\LogoutUrlGenerator(($this->services['request_stack'] ?? ($this->services['request_stack'] = new \Symfony\Component\HttpFoundation\RequestStack())), ($this->services['router'] ?? $this->getRouterService()), ($this->services['security.token_storage'] ?? $this->getSecurity_TokenStorageService()));

        $instance->registerListener('main', '/api/account/logout', 'logout', '_csrf_token', NULL, NULL);

        return $instance;
    }

    /*
     * Gets the private 'sensio_framework_extra.controller.listener' shared service.
     *
     * @return \Sensio\Bundle\FrameworkExtraBundle\EventListener\ControllerListener
     */
    protected function getSensioFrameworkExtra_Controller_ListenerService()
    {
        return $this->privates['sensio_framework_extra.controller.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\ControllerListener(($this->privates['annotations.cached_reader'] ?? $this->getAnnotations_CachedReaderService()));
    }

    /*
     * Gets the private 'sensio_framework_extra.converter.listener' shared service.
     *
     * @return \Sensio\Bundle\FrameworkExtraBundle\EventListener\ParamConverterListener
     */
    protected function getSensioFrameworkExtra_Converter_ListenerService()
    {
        $a = new \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager();
        $a->add(new \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter(($this->services['doctrine'] ?? $this->getDoctrineService()), new \Symfony\Component\ExpressionLanguage\ExpressionLanguage()), 0, 'doctrine.orm');
        $a->add(new \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DateTimeParamConverter(), 0, 'datetime');
        $a->add(($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Controller\\ParameterConverter\\ContextConverter'] ?? $this->getContextConverterService()), 100, 'Frontastic\\Backstage\\ApiBundle\\Controller\\ParameterConverter\\ContextConverter');

        return $this->privates['sensio_framework_extra.converter.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\ParamConverterListener($a, true);
    }

    /*
     * Gets the private 'sensio_framework_extra.security.listener' shared service.
     *
     * @return \Sensio\Bundle\FrameworkExtraBundle\EventListener\SecurityListener
     */
    protected function getSensioFrameworkExtra_Security_ListenerService()
    {
        return $this->privates['sensio_framework_extra.security.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\SecurityListener(($this->privates['framework_extra_bundle.argument_name_convertor'] ?? $this->getFrameworkExtraBundle_ArgumentNameConvertorService()), new \Sensio\Bundle\FrameworkExtraBundle\Security\ExpressionLanguage(), ($this->privates['security.authentication.trust_resolver'] ?? ($this->privates['security.authentication.trust_resolver'] = new \Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver(NULL, NULL))), ($this->privates['security.role_hierarchy'] ?? ($this->privates['security.role_hierarchy'] = new \Symfony\Component\Security\Core\Role\RoleHierarchy([]))), ($this->services['security.token_storage'] ?? $this->getSecurity_TokenStorageService()), ($this->services['security.authorization_checker'] ?? $this->getSecurity_AuthorizationCheckerService()), ($this->services['logger'] ?? $this->getLoggerService()));
    }

    /*
     * Gets the private 'session_listener' shared service.
     *
     * @return \Symfony\Component\HttpKernel\EventListener\SessionListener
     */
    protected function getSessionListenerService()
    {
        return $this->privates['session_listener'] = new \Symfony\Component\HttpKernel\EventListener\SessionListener(new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
            'initialized_session' => ['services', 'session', NULL, true],
            'session' => ['services', 'session', 'getSessionService.php', true],
        ], [
            'initialized_session' => '?',
            'session' => '?',
        ]));
    }

    public function getParameter($name)
    {
        $name = (string) $name;
        if (isset($this->buildParameters[$name])) {
            return $this->buildParameters[$name];
        }

        if (!(isset($this->parameters[$name]) || isset($this->loadedDynamicParameters[$name]) || array_key_exists($name, $this->parameters))) {
            throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
        }
        if (isset($this->loadedDynamicParameters[$name])) {
            return $this->loadedDynamicParameters[$name] ? $this->dynamicParameters[$name] : $this->getDynamicParameter($name);
        }

        return $this->parameters[$name];
    }

    public function hasParameter($name): bool
    {
        $name = (string) $name;
        if (isset($this->buildParameters[$name])) {
            return true;
        }

        return isset($this->parameters[$name]) || isset($this->loadedDynamicParameters[$name]) || array_key_exists($name, $this->parameters);
    }

    public function setParameter($name, $value): void
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }

    public function getParameterBag(): ParameterBagInterface
    {
        if (null === $this->parameterBag) {
            $parameters = $this->parameters;
            foreach ($this->loadedDynamicParameters as $name => $loaded) {
                $parameters[$name] = $loaded ? $this->dynamicParameters[$name] : $this->getDynamicParameter($name);
            }
            foreach ($this->buildParameters as $name => $value) {
                $parameters[$name] = $value;
            }
            $this->parameterBag = new FrozenParameterBag($parameters);
        }

        return $this->parameterBag;
    }

    private $loadedDynamicParameters = [
        'kernel.cache_dir' => false,
        'kernel.secret' => false,
        'kernel.default_locale' => false,
        'session.save_path' => false,
        'validator.mapping.cache.file' => false,
        'serializer.mapping.cache.file' => false,
        'swiftmailer.spool.default.memory.path' => false,
        'doctrine.orm.proxy_dir' => false,
        'rulerz.cache_directory' => false,
        'frontastic.project_file' => false,
    ];
    private $dynamicParameters = [];

    private function getDynamicParameter(string $name)
    {
        switch ($name) {
            case 'kernel.cache_dir': $value = $this->targetDir.''; break;
            case 'kernel.secret': $value = $this->getEnv('secret'); break;
            case 'kernel.default_locale': $value = $this->getEnv('locale'); break;
            case 'session.save_path': $value = ($this->targetDir.''.'/sessions'); break;
            case 'validator.mapping.cache.file': $value = ($this->targetDir.''.'/validation.php'); break;
            case 'serializer.mapping.cache.file': $value = ($this->targetDir.''.'/serialization.php'); break;
            case 'swiftmailer.spool.default.memory.path': $value = ($this->targetDir.''.'/swiftmailer/spool/default'); break;
            case 'doctrine.orm.proxy_dir': $value = ($this->targetDir.''.'/doctrine/orm/Proxies'); break;
            case 'rulerz.cache_directory': $value = ($this->targetDir.''.'/rulerz'); break;
            case 'frontastic.project_file': $value = (\dirname(__DIR__, 5).'/'.$this->getEnv('string:default:frontastic_default_project_file:frontastic_project_file')); break;
            default: throw new InvalidArgumentException(sprintf('The dynamic parameter "%s" must be defined.', $name));
        }
        $this->loadedDynamicParameters[$name] = true;

        return $this->dynamicParameters[$name] = $value;
    }

    protected function getDefaultParameters(): array
    {
        return [
            'kernel.root_dir' => \dirname(__DIR__, 5),
            'kernel.project_dir' => \dirname(__DIR__, 5),
            'kernel.environment' => 'prod',
            'kernel.debug' => false,
            'kernel.name' => 'catwalk',
            'kernel.logs_dir' => (\dirname(__DIR__, 4).'/log/frontastic/catwalk/prod'),
            'kernel.bundles' => [
                'FrameworkBundle' => 'Symfony\\Bundle\\FrameworkBundle\\FrameworkBundle',
                'TwigBundle' => 'Symfony\\Bundle\\TwigBundle\\TwigBundle',
                'MonologBundle' => 'Symfony\\Bundle\\MonologBundle\\MonologBundle',
                'SecurityBundle' => 'Symfony\\Bundle\\SecurityBundle\\SecurityBundle',
                'SwiftmailerBundle' => 'Symfony\\Bundle\\SwiftmailerBundle\\SwiftmailerBundle',
                'SensioFrameworkExtraBundle' => 'Sensio\\Bundle\\FrameworkExtraBundle\\SensioFrameworkExtraBundle',
                'DoctrineBundle' => 'Doctrine\\Bundle\\DoctrineBundle\\DoctrineBundle',
                'QafooLabsNoFrameworkBundle' => 'QafooLabs\\Bundle\\NoFrameworkBundle\\QafooLabsNoFrameworkBundle',
                'KPhoenRulerZBundle' => 'KPhoen\\RulerZBundle\\KPhoenRulerZBundle',
                'FrontasticCommonCoreBundle' => 'Frontastic\\Common\\CoreBundle\\FrontasticCommonCoreBundle',
                'FrontasticCommonReplicatorBundle' => 'Frontastic\\Common\\ReplicatorBundle\\FrontasticCommonReplicatorBundle',
                'FrontasticCommonAccountApiBundle' => 'Frontastic\\Common\\AccountApiBundle\\FrontasticCommonAccountApiBundle',
                'FrontasticCommonProductApiBundle' => 'Frontastic\\Common\\ProductApiBundle\\FrontasticCommonProductApiBundle',
                'FrontasticCommonProductSearchApiBundle' => 'Frontastic\\Common\\ProductSearchApiBundle\\FrontasticCommonProductSearchApiBundle',
                'FrontasticCommonProjectApiBundle' => 'Frontastic\\Common\\ProjectApiBundle\\FrontasticCommonProjectApiBundle',
                'FrontasticCommonContentApiBundle' => 'Frontastic\\Common\\ContentApiBundle\\FrontasticCommonContentApiBundle',
                'FrontasticCommonWishlistApiBundle' => 'Frontastic\\Common\\WishlistApiBundle\\FrontasticCommonWishlistApiBundle',
                'FrontasticCommonCartApiBundle' => 'Frontastic\\Common\\CartApiBundle\\FrontasticCommonCartApiBundle',
                'FrontasticCommonSapCommerceCloudBundle' => 'Frontastic\\Common\\SapCommerceCloudBundle\\FrontasticCommonSapCommerceCloudBundle',
                'FrontasticCommonShopifyBundle' => 'Frontastic\\Common\\ShopifyBundle\\FrontasticCommonShopifyBundle',
                'FrontasticCommonShopwareBundle' => 'Frontastic\\Common\\ShopwareBundle\\FrontasticCommonShopwareBundle',
                'FrontasticCommonSprykerBundle' => 'Frontastic\\Common\\SprykerBundle\\FrontasticCommonSprykerBundle',
                'FrontasticCommonFindologicBundle' => 'Frontastic\\Common\\FindologicBundle\\FrontasticCommonFindologicBundle',
                'FrontasticCatwalkFrontendBundle' => 'Frontastic\\Catwalk\\FrontendBundle\\FrontasticCatwalkFrontendBundle',
                'FrontasticCatwalkApiCoreBundle' => 'Frontastic\\Catwalk\\ApiCoreBundle\\FrontasticCatwalkApiCoreBundle',
                'FrontasticCatwalkDevVmBundle' => 'Frontastic\\Catwalk\\DevVmBundle\\FrontasticCatwalkDevVmBundle',
            ],
            'kernel.bundles_metadata' => [
                'FrameworkBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/vendor/symfony/symfony/src/Symfony/Bundle/FrameworkBundle'),
                    'namespace' => 'Symfony\\Bundle\\FrameworkBundle',
                ],
                'TwigBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/vendor/symfony/symfony/src/Symfony/Bundle/TwigBundle'),
                    'namespace' => 'Symfony\\Bundle\\TwigBundle',
                ],
                'MonologBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/vendor/symfony/monolog-bundle'),
                    'namespace' => 'Symfony\\Bundle\\MonologBundle',
                ],
                'SecurityBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/vendor/symfony/symfony/src/Symfony/Bundle/SecurityBundle'),
                    'namespace' => 'Symfony\\Bundle\\SecurityBundle',
                ],
                'SwiftmailerBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/vendor/symfony/swiftmailer-bundle'),
                    'namespace' => 'Symfony\\Bundle\\SwiftmailerBundle',
                ],
                'SensioFrameworkExtraBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/vendor/sensio/framework-extra-bundle/src'),
                    'namespace' => 'Sensio\\Bundle\\FrameworkExtraBundle',
                ],
                'DoctrineBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/vendor/doctrine/doctrine-bundle'),
                    'namespace' => 'Doctrine\\Bundle\\DoctrineBundle',
                ],
                'QafooLabsNoFrameworkBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/vendor/qafoolabs/no-framework-bundle/src/QafooLabs/Bundle/NoFrameworkBundle'),
                    'namespace' => 'QafooLabs\\Bundle\\NoFrameworkBundle',
                ],
                'KPhoenRulerZBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/vendor/kphoen/rulerz-bundle'),
                    'namespace' => 'KPhoen\\RulerZBundle',
                ],
                'FrontasticCommonCoreBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/CoreBundle'),
                    'namespace' => 'Frontastic\\Common\\CoreBundle',
                ],
                'FrontasticCommonReplicatorBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/ReplicatorBundle'),
                    'namespace' => 'Frontastic\\Common\\ReplicatorBundle',
                ],
                'FrontasticCommonAccountApiBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/AccountApiBundle'),
                    'namespace' => 'Frontastic\\Common\\AccountApiBundle',
                ],
                'FrontasticCommonProductApiBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/ProductApiBundle'),
                    'namespace' => 'Frontastic\\Common\\ProductApiBundle',
                ],
                'FrontasticCommonProductSearchApiBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/ProductSearchApiBundle'),
                    'namespace' => 'Frontastic\\Common\\ProductSearchApiBundle',
                ],
                'FrontasticCommonProjectApiBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/ProjectApiBundle'),
                    'namespace' => 'Frontastic\\Common\\ProjectApiBundle',
                ],
                'FrontasticCommonContentApiBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/ContentApiBundle'),
                    'namespace' => 'Frontastic\\Common\\ContentApiBundle',
                ],
                'FrontasticCommonWishlistApiBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/WishlistApiBundle'),
                    'namespace' => 'Frontastic\\Common\\WishlistApiBundle',
                ],
                'FrontasticCommonCartApiBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/CartApiBundle'),
                    'namespace' => 'Frontastic\\Common\\CartApiBundle',
                ],
                'FrontasticCommonSapCommerceCloudBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/SapCommerceCloudBundle'),
                    'namespace' => 'Frontastic\\Common\\SapCommerceCloudBundle',
                ],
                'FrontasticCommonShopifyBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/ShopifyBundle'),
                    'namespace' => 'Frontastic\\Common\\ShopifyBundle',
                ],
                'FrontasticCommonShopwareBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/ShopwareBundle'),
                    'namespace' => 'Frontastic\\Common\\ShopwareBundle',
                ],
                'FrontasticCommonSprykerBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/SprykerBundle'),
                    'namespace' => 'Frontastic\\Common\\SprykerBundle',
                ],
                'FrontasticCommonFindologicBundle' => [
                    'path' => (\dirname(__DIR__, 6).'/libraries/common/src/php/FindologicBundle'),
                    'namespace' => 'Frontastic\\Common\\FindologicBundle',
                ],
                'FrontasticCatwalkFrontendBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/src/php/FrontendBundle'),
                    'namespace' => 'Frontastic\\Catwalk\\FrontendBundle',
                ],
                'FrontasticCatwalkApiCoreBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/src/php/ApiCoreBundle'),
                    'namespace' => 'Frontastic\\Catwalk\\ApiCoreBundle',
                ],
                'FrontasticCatwalkDevVmBundle' => [
                    'path' => (\dirname(__DIR__, 5).'/src/php/DevVmBundle'),
                    'namespace' => 'Frontastic\\Catwalk\\DevVmBundle',
                ],
            ],
            'kernel.charset' => 'UTF-8',
            'kernel.container_class' => 'catwalkFrontastic_Catwalk_AppKernelProdContainer',
            'env(smtp_host)' => 'localhost',
            'env(smtp_port)' => '1025',
            'env(smtp_user)' => '',
            'env(smtp_password)' => '',
            'env(smtp_encryption)' => 'tls',
            'env(smtp_sender)' => 'support@frontastic.io',
            'env' => 'prod',
            'locale' => 'de',
            'secret' => 'secret',
            'mailer.transport' => 'sendmail',
            'debug' => false,
            'monolog_action_level' => 'error',
            'cache_dir' => 'var/cache/frontastic',
            'log_dir' => 'var/log/frontastic',
            'database_host' => 'localhost',
            'database_port' => '',
            'database_name' => 'catwalk',
            'database_user' => 'root',
            'database_password' => 'root',
            'smtp_host' => 'localhost',
            'smtp_port' => '1025',
            'tideways_key' => '',
            'customer' => 'demo',
            'project' => 'catwalk',
            'frontastic.environment' => 'production',
            'frontastic.paas_catwalk_dir' => (\dirname(__DIR__, 5).'/src/php/../..'),
            'frontastic.gedmo_extension_source_dir' => (\dirname(__DIR__, 5).'/vendor/gedmo/doctrine-extensions/lib'),
            'event_dispatcher.event_aliases' => [
                'Symfony\\Component\\Console\\Event\\ConsoleCommandEvent' => 'console.command',
                'Symfony\\Component\\Console\\Event\\ConsoleErrorEvent' => 'console.error',
                'Symfony\\Component\\Console\\Event\\ConsoleTerminateEvent' => 'console.terminate',
                'Symfony\\Component\\Form\\Event\\PreSubmitEvent' => 'form.pre_submit',
                'Symfony\\Component\\Form\\Event\\SubmitEvent' => 'form.submit',
                'Symfony\\Component\\Form\\Event\\PostSubmitEvent' => 'form.post_submit',
                'Symfony\\Component\\Form\\Event\\PreSetDataEvent' => 'form.pre_set_data',
                'Symfony\\Component\\Form\\Event\\PostSetDataEvent' => 'form.post_set_data',
                'Symfony\\Component\\HttpKernel\\Event\\ControllerArgumentsEvent' => 'kernel.controller_arguments',
                'Symfony\\Component\\HttpKernel\\Event\\ControllerEvent' => 'kernel.controller',
                'Symfony\\Component\\HttpKernel\\Event\\ResponseEvent' => 'kernel.response',
                'Symfony\\Component\\HttpKernel\\Event\\FinishRequestEvent' => 'kernel.finish_request',
                'Symfony\\Component\\HttpKernel\\Event\\RequestEvent' => 'kernel.request',
                'Symfony\\Component\\HttpKernel\\Event\\ViewEvent' => 'kernel.view',
                'Symfony\\Component\\HttpKernel\\Event\\ExceptionEvent' => 'kernel.exception',
                'Symfony\\Component\\HttpKernel\\Event\\TerminateEvent' => 'kernel.terminate',
                'Symfony\\Component\\Workflow\\Event\\GuardEvent' => 'workflow.guard',
                'Symfony\\Component\\Workflow\\Event\\LeaveEvent' => 'workflow.leave',
                'Symfony\\Component\\Workflow\\Event\\TransitionEvent' => 'workflow.transition',
                'Symfony\\Component\\Workflow\\Event\\EnterEvent' => 'workflow.enter',
                'Symfony\\Component\\Workflow\\Event\\EnteredEvent' => 'workflow.entered',
                'Symfony\\Component\\Workflow\\Event\\CompletedEvent' => 'workflow.completed',
                'Symfony\\Component\\Workflow\\Event\\AnnounceEvent' => 'workflow.announce',
                'Symfony\\Component\\Security\\Core\\Event\\AuthenticationSuccessEvent' => 'security.authentication.success',
                'Symfony\\Component\\Security\\Core\\Event\\AuthenticationFailureEvent' => 'security.authentication.failure',
                'Symfony\\Component\\Security\\Http\\Event\\InteractiveLoginEvent' => 'security.interactive_login',
                'Symfony\\Component\\Security\\Http\\Event\\SwitchUserEvent' => 'security.switch_user',
            ],
            'fragment.renderer.hinclude.global_template' => NULL,
            'fragment.path' => '/_fragment',
            'kernel.http_method_override' => true,
            'kernel.trusted_hosts' => [

            ],
            'kernel.error_controller' => 'error_controller',
            'templating.helper.code.file_link_format' => NULL,
            'debug.file_link_format' => NULL,
            'session.metadata.storage_key' => '_sf2_meta',
            'session.storage.options' => [
                'cache_limiter' => '0',
                'name' => 'FCSESSID0815',
                'cookie_httponly' => true,
                'gc_probability' => 1,
            ],
            'session.metadata.update_threshold' => 0,
            'form.type_extension.csrf.enabled' => true,
            'form.type_extension.csrf.field_name' => '_token',
            'asset.request_context.base_path' => '',
            'asset.request_context.secure' => false,
            'templating.loader.cache.path' => NULL,
            'templating.engines' => [
                0 => 'twig',
            ],
            'validator.translation_domain' => 'validators',
            'data_collector.templates' => [

            ],
            'debug.error_handler.throw_at' => 0,
            'router.request_context.host' => 'localhost',
            'router.request_context.scheme' => 'http',
            'router.request_context.base_url' => '',
            'router.resource' => (\dirname(__DIR__, 5).'/config/routing.yml'),
            'router.cache_class_prefix' => 'catwalkFrontastic_Catwalk_AppKernelProdContainer',
            'request_listener.http_port' => 80,
            'request_listener.https_port' => 443,
            'twig.exception_listener.controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\ErrorController::errorAction',
            'twig.form.resources' => [
                0 => 'form_div_layout.html.twig',
            ],
            'twig.default_path' => (\dirname(__DIR__, 5).'/templates'),
            'monolog.use_microseconds' => true,
            'monolog.swift_mailer.handlers' => [

            ],
            'monolog.handlers_to_channels' => [
                'monolog.handler.filter_for_errors' => NULL,
            ],
            'security.authentication.trust_resolver.anonymous_class' => NULL,
            'security.authentication.trust_resolver.rememberme_class' => NULL,
            'security.role_hierarchy.roles' => [

            ],
            'security.access.denied_url' => NULL,
            'security.authentication.manager.erase_credentials' => true,
            'security.authentication.session_strategy.strategy' => 'migrate',
            'security.access.always_authenticate_before_granting' => false,
            'security.authentication.hide_user_not_found' => true,
            'swiftmailer.mailer.default.transport.name' => 'dynamic',
            'swiftmailer.mailer.default.spool.enabled' => true,
            'swiftmailer.mailer.default.plugin.impersonate' => NULL,
            'swiftmailer.mailer.default.single_address' => NULL,
            'swiftmailer.mailer.default.delivery.enabled' => true,
            'swiftmailer.spool.enabled' => true,
            'swiftmailer.delivery.enabled' => true,
            'swiftmailer.single_address' => NULL,
            'swiftmailer.mailers' => [
                'default' => 'swiftmailer.mailer.default',
            ],
            'swiftmailer.default_mailer' => 'default',
            'doctrine_cache.apc.class' => 'Doctrine\\Common\\Cache\\ApcCache',
            'doctrine_cache.apcu.class' => 'Doctrine\\Common\\Cache\\ApcuCache',
            'doctrine_cache.array.class' => 'Doctrine\\Common\\Cache\\ArrayCache',
            'doctrine_cache.chain.class' => 'Doctrine\\Common\\Cache\\ChainCache',
            'doctrine_cache.couchbase.class' => 'Doctrine\\Common\\Cache\\CouchbaseCache',
            'doctrine_cache.couchbase.connection.class' => 'Couchbase',
            'doctrine_cache.couchbase.hostnames' => 'localhost:8091',
            'doctrine_cache.file_system.class' => 'Doctrine\\Common\\Cache\\FilesystemCache',
            'doctrine_cache.php_file.class' => 'Doctrine\\Common\\Cache\\PhpFileCache',
            'doctrine_cache.memcache.class' => 'Doctrine\\Common\\Cache\\MemcacheCache',
            'doctrine_cache.memcache.connection.class' => 'Memcache',
            'doctrine_cache.memcache.host' => 'localhost',
            'doctrine_cache.memcache.port' => 11211,
            'doctrine_cache.memcached.class' => 'Doctrine\\Common\\Cache\\MemcachedCache',
            'doctrine_cache.memcached.connection.class' => 'Memcached',
            'doctrine_cache.memcached.host' => 'localhost',
            'doctrine_cache.memcached.port' => 11211,
            'doctrine_cache.mongodb.class' => 'Doctrine\\Common\\Cache\\MongoDBCache',
            'doctrine_cache.mongodb.collection.class' => 'MongoCollection',
            'doctrine_cache.mongodb.connection.class' => 'MongoClient',
            'doctrine_cache.mongodb.server' => 'localhost:27017',
            'doctrine_cache.predis.client.class' => 'Predis\\Client',
            'doctrine_cache.predis.scheme' => 'tcp',
            'doctrine_cache.predis.host' => 'localhost',
            'doctrine_cache.predis.port' => 6379,
            'doctrine_cache.redis.class' => 'Doctrine\\Common\\Cache\\RedisCache',
            'doctrine_cache.redis.connection.class' => 'Redis',
            'doctrine_cache.redis.host' => 'localhost',
            'doctrine_cache.redis.port' => 6379,
            'doctrine_cache.riak.class' => 'Doctrine\\Common\\Cache\\RiakCache',
            'doctrine_cache.riak.bucket.class' => 'Riak\\Bucket',
            'doctrine_cache.riak.connection.class' => 'Riak\\Connection',
            'doctrine_cache.riak.bucket_property_list.class' => 'Riak\\BucketPropertyList',
            'doctrine_cache.riak.host' => 'localhost',
            'doctrine_cache.riak.port' => 8087,
            'doctrine_cache.sqlite3.class' => 'Doctrine\\Common\\Cache\\SQLite3Cache',
            'doctrine_cache.sqlite3.connection.class' => 'SQLite3',
            'doctrine_cache.void.class' => 'Doctrine\\Common\\Cache\\VoidCache',
            'doctrine_cache.wincache.class' => 'Doctrine\\Common\\Cache\\WinCacheCache',
            'doctrine_cache.xcache.class' => 'Doctrine\\Common\\Cache\\XcacheCache',
            'doctrine_cache.zenddata.class' => 'Doctrine\\Common\\Cache\\ZendDataCache',
            'doctrine_cache.security.acl.cache.class' => 'Doctrine\\Bundle\\DoctrineCacheBundle\\Acl\\Model\\AclCache',
            'doctrine.dbal.logger.chain.class' => 'Doctrine\\DBAL\\Logging\\LoggerChain',
            'doctrine.dbal.logger.profiling.class' => 'Doctrine\\DBAL\\Logging\\DebugStack',
            'doctrine.dbal.logger.class' => 'Symfony\\Bridge\\Doctrine\\Logger\\DbalLogger',
            'doctrine.dbal.configuration.class' => 'Doctrine\\DBAL\\Configuration',
            'doctrine.data_collector.class' => 'Doctrine\\Bundle\\DoctrineBundle\\DataCollector\\DoctrineDataCollector',
            'doctrine.dbal.connection.event_manager.class' => 'Symfony\\Bridge\\Doctrine\\ContainerAwareEventManager',
            'doctrine.dbal.connection_factory.class' => 'Doctrine\\Bundle\\DoctrineBundle\\ConnectionFactory',
            'doctrine.dbal.events.mysql_session_init.class' => 'Doctrine\\DBAL\\Event\\Listeners\\MysqlSessionInit',
            'doctrine.dbal.events.oracle_session_init.class' => 'Doctrine\\DBAL\\Event\\Listeners\\OracleSessionInit',
            'doctrine.class' => 'Doctrine\\Bundle\\DoctrineBundle\\Registry',
            'doctrine.entity_managers' => [
                'default' => 'doctrine.orm.default_entity_manager',
            ],
            'doctrine.default_entity_manager' => 'default',
            'doctrine.dbal.connection_factory.types' => [

            ],
            'doctrine.connections' => [
                'default' => 'doctrine.dbal.default_connection',
            ],
            'doctrine.default_connection' => 'default',
            'doctrine.orm.configuration.class' => 'Doctrine\\ORM\\Configuration',
            'doctrine.orm.entity_manager.class' => 'Doctrine\\ORM\\EntityManager',
            'doctrine.orm.manager_configurator.class' => 'Doctrine\\Bundle\\DoctrineBundle\\ManagerConfigurator',
            'doctrine.orm.cache.array.class' => 'Doctrine\\Common\\Cache\\ArrayCache',
            'doctrine.orm.cache.apc.class' => 'Doctrine\\Common\\Cache\\ApcCache',
            'doctrine.orm.cache.memcache.class' => 'Doctrine\\Common\\Cache\\MemcacheCache',
            'doctrine.orm.cache.memcache_host' => 'localhost',
            'doctrine.orm.cache.memcache_port' => 11211,
            'doctrine.orm.cache.memcache_instance.class' => 'Memcache',
            'doctrine.orm.cache.memcached.class' => 'Doctrine\\Common\\Cache\\MemcachedCache',
            'doctrine.orm.cache.memcached_host' => 'localhost',
            'doctrine.orm.cache.memcached_port' => 11211,
            'doctrine.orm.cache.memcached_instance.class' => 'Memcached',
            'doctrine.orm.cache.redis.class' => 'Doctrine\\Common\\Cache\\RedisCache',
            'doctrine.orm.cache.redis_host' => 'localhost',
            'doctrine.orm.cache.redis_port' => 6379,
            'doctrine.orm.cache.redis_instance.class' => 'Redis',
            'doctrine.orm.cache.xcache.class' => 'Doctrine\\Common\\Cache\\XcacheCache',
            'doctrine.orm.cache.wincache.class' => 'Doctrine\\Common\\Cache\\WinCacheCache',
            'doctrine.orm.cache.zenddata.class' => 'Doctrine\\Common\\Cache\\ZendDataCache',
            'doctrine.orm.metadata.driver_chain.class' => 'Doctrine\\Persistence\\Mapping\\Driver\\MappingDriverChain',
            'doctrine.orm.metadata.annotation.class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
            'doctrine.orm.metadata.xml.class' => 'Doctrine\\ORM\\Mapping\\Driver\\SimplifiedXmlDriver',
            'doctrine.orm.metadata.yml.class' => 'Doctrine\\ORM\\Mapping\\Driver\\SimplifiedYamlDriver',
            'doctrine.orm.metadata.php.class' => 'Doctrine\\ORM\\Mapping\\Driver\\PHPDriver',
            'doctrine.orm.metadata.staticphp.class' => 'Doctrine\\ORM\\Mapping\\Driver\\StaticPHPDriver',
            'doctrine.orm.proxy_cache_warmer.class' => 'Symfony\\Bridge\\Doctrine\\CacheWarmer\\ProxyCacheWarmer',
            'form.type_guesser.doctrine.class' => 'Symfony\\Bridge\\Doctrine\\Form\\DoctrineOrmTypeGuesser',
            'doctrine.orm.validator.unique.class' => 'Symfony\\Bridge\\Doctrine\\Validator\\Constraints\\UniqueEntityValidator',
            'doctrine.orm.validator_initializer.class' => 'Symfony\\Bridge\\Doctrine\\Validator\\DoctrineInitializer',
            'doctrine.orm.security.user.provider.class' => 'Symfony\\Bridge\\Doctrine\\Security\\User\\EntityUserProvider',
            'doctrine.orm.listeners.resolve_target_entity.class' => 'Doctrine\\ORM\\Tools\\ResolveTargetEntityListener',
            'doctrine.orm.listeners.attach_entity_listeners.class' => 'Doctrine\\ORM\\Tools\\AttachEntityListenersListener',
            'doctrine.orm.naming_strategy.default.class' => 'Doctrine\\ORM\\Mapping\\DefaultNamingStrategy',
            'doctrine.orm.naming_strategy.underscore.class' => 'Doctrine\\ORM\\Mapping\\UnderscoreNamingStrategy',
            'doctrine.orm.quote_strategy.default.class' => 'Doctrine\\ORM\\Mapping\\DefaultQuoteStrategy',
            'doctrine.orm.quote_strategy.ansi.class' => 'Doctrine\\ORM\\Mapping\\AnsiQuoteStrategy',
            'doctrine.orm.entity_listener_resolver.class' => 'Doctrine\\Bundle\\DoctrineBundle\\Mapping\\ContainerEntityListenerResolver',
            'doctrine.orm.second_level_cache.default_cache_factory.class' => 'Doctrine\\ORM\\Cache\\DefaultCacheFactory',
            'doctrine.orm.second_level_cache.default_region.class' => 'Doctrine\\ORM\\Cache\\Region\\DefaultRegion',
            'doctrine.orm.second_level_cache.filelock_region.class' => 'Doctrine\\ORM\\Cache\\Region\\FileLockRegion',
            'doctrine.orm.second_level_cache.logger_chain.class' => 'Doctrine\\ORM\\Cache\\Logging\\CacheLoggerChain',
            'doctrine.orm.second_level_cache.logger_statistics.class' => 'Doctrine\\ORM\\Cache\\Logging\\StatisticsCacheLogger',
            'doctrine.orm.second_level_cache.cache_configuration.class' => 'Doctrine\\ORM\\Cache\\CacheConfiguration',
            'doctrine.orm.second_level_cache.regions_configuration.class' => 'Doctrine\\ORM\\Cache\\RegionsConfiguration',
            'doctrine.orm.auto_generate_proxy_classes' => false,
            'doctrine.orm.proxy_namespace' => 'Proxies',
            'ssr_port' => 8000,
            'frontastic.nodejs.renderer' => 'http://localhost:8000',
            'frontastic_default_project_file' => 'config/project.yml',
            'frontastic.web_assets' => (\dirname(__DIR__, 5).'/public/assets'),
            'console.command.ids' => [
                0 => 'console.command.public_alias.doctrine_cache.contains_command',
                1 => 'console.command.public_alias.doctrine_cache.delete_command',
                2 => 'console.command.public_alias.doctrine_cache.flush_command',
                3 => 'console.command.public_alias.doctrine_cache.stats_command',
                4 => 'rulerz.command.cache_clear',
                5 => 'Frontastic\\Common\\AccountApiBundle\\Command\\CreateAccountCommand',
                6 => 'Frontastic\\Catwalk\\FrontendBundle\\Command\\AnnounceReleaseCommand',
                7 => 'Frontastic\\Catwalk\\FrontendBundle\\Command\\ClearCommand',
                8 => 'Frontastic\\Catwalk\\FrontendBundle\\Command\\ClearOrphanedCachesCommand',
                9 => 'Frontastic\\Catwalk\\FrontendBundle\\Command\\CreateBundleCommand',
                10 => 'Frontastic\\Catwalk\\FrontendBundle\\Command\\CronCommand',
                11 => 'Frontastic\\Catwalk\\FrontendBundle\\Command\\DumpCategoriesCommand',
                12 => 'Frontastic\\Catwalk\\FrontendBundle\\Command\\DumpProductsCommand',
                13 => 'Frontastic\\Catwalk\\FrontendBundle\\Command\\GenerateSitemapsCommand',
                14 => 'Frontastic\\Catwalk\\FrontendBundle\\Command\\RebuildRoutesCommand',
                15 => 'Frontastic\\Catwalk\\FrontendBundle\\Command\\SendNewOrderMailsCommand',
                16 => 'Frontastic\\Catwalk\\DevVmBundle\\Command\\SyncCommand',
            ],
        ];
    }
}
