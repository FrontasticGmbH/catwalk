# Frontastic Catwalk

## HTTP API Documentation

Download the [Swagger File](swagger.yml)

* [FrontendBundle\Controller\CartController](php/FrontendBundle/Controller/CartController.rest.md)

##  API Documentation

Here you find the API documentation for the relevant classes:

* ApiCoreBundle
  * Domain
    * [App](php/ApiCoreBundle/Domain/App.md)
    * App
      * [FeatureFlag](php/ApiCoreBundle/Domain/App/FeatureFlag.md)
      * [Storefinder](php/ApiCoreBundle/Domain/App/Storefinder.md)
      * [Teaser](php/ApiCoreBundle/Domain/App/Teaser.md)
    * [AppDataTarget](php/ApiCoreBundle/Domain/AppDataTarget.md)
    * [AppRepository](php/ApiCoreBundle/Domain/AppRepository.md)
    * [AppRepositoryService](php/ApiCoreBundle/Domain/AppRepositoryService.md)
    * [AppService](php/ApiCoreBundle/Domain/AppService.md)
    * [CachingProductApi](php/ApiCoreBundle/Domain/CachingProductApi.md)
    * [CachingProductSearchApi](php/ApiCoreBundle/Domain/CachingProductSearchApi.md)
    * CommerceTools
      * [ClientFactory](php/ApiCoreBundle/Domain/CommerceTools/ClientFactory.md)
    * [Context](php/ApiCoreBundle/Domain/Context.md)
    * Context
      * [LocaleResolver](php/ApiCoreBundle/Domain/Context/LocaleResolver.md)
    * [ContextDecorator](php/ApiCoreBundle/Domain/ContextDecorator.md)
    * [ContextInContainerDeprecationProvider](php/ApiCoreBundle/Domain/ContextInContainerDeprecationProvider.md)
    * [ContextService](php/ApiCoreBundle/Domain/ContextService.md)
    * [CustomerService](php/ApiCoreBundle/Domain/CustomerService.md)
    * [DataRepository](php/ApiCoreBundle/Domain/DataRepository.md)
    * [Environment](php/ApiCoreBundle/Domain/Environment.md)
    * [EnvironmentReplicationFilter](php/ApiCoreBundle/Domain/EnvironmentReplicationFilter.md)
    * Hooks
      * [HookResponseDeserializer](php/ApiCoreBundle/Domain/Hooks/HookResponseDeserializer.md)
      * [HooksApiClient](php/ApiCoreBundle/Domain/Hooks/HooksApiClient.md)
      * [HooksCall](php/ApiCoreBundle/Domain/Hooks/HooksCall.md)
      * [HooksCallBuilder](php/ApiCoreBundle/Domain/Hooks/HooksCallBuilder.md)
      * [HooksService](php/ApiCoreBundle/Domain/Hooks/HooksService.md)
    * [ProductApiFactoryDecorator](php/ApiCoreBundle/Domain/ProductApiFactoryDecorator.md)
    * [ProductApiWithoutInner](php/ApiCoreBundle/Domain/ProductApiWithoutInner.md)
    * [ProductSearchApiFactoryDecorator](php/ApiCoreBundle/Domain/ProductSearchApiFactoryDecorator.md)
    * [ProductSearchApiWithoutInner](php/ApiCoreBundle/Domain/ProductSearchApiWithoutInner.md)
    * [ProjectService](php/ApiCoreBundle/Domain/ProjectService.md)
    * [Tastic](php/ApiCoreBundle/Domain/Tastic.md)
    * [TasticService](php/ApiCoreBundle/Domain/TasticService.md)
* FrontendBundle
  * Domain
    * [Cell](php/FrontendBundle/Domain/Cell.md)
    * Cell
      * [Configuration](php/FrontendBundle/Domain/Cell/Configuration.md)
    * Commercetools
      * [RawDataService](php/FrontendBundle/Domain/Commercetools/RawDataService.md)
    * [Configuration](php/FrontendBundle/Domain/Configuration.md)
    * [EnabledFacetService](php/FrontendBundle/Domain/EnabledFacetService.md)
    * [Facet](php/FrontendBundle/Domain/Facet.md)
    * [FacetService](php/FrontendBundle/Domain/FacetService.md)
    * [FeatureFlagService](php/FrontendBundle/Domain/FeatureFlagService.md)
    * [Layout](php/FrontendBundle/Domain/Layout.md)
    * [MasterPageMatcherRules](php/FrontendBundle/Domain/MasterPageMatcherRules.md)
    * [MasterService](php/FrontendBundle/Domain/MasterService.md)
    * [Node](php/FrontendBundle/Domain/Node.md)
    * [NodeService](php/FrontendBundle/Domain/NodeService.md)
    * [Page](php/FrontendBundle/Domain/Page.md)
    * [PageImpressionRecorder](php/FrontendBundle/Domain/PageImpressionRecorder.md)
    * PageMatcher
      * [PageMatcherContext](php/FrontendBundle/Domain/PageMatcher/PageMatcherContext.md)
    * [PageService](php/FrontendBundle/Domain/PageService.md)
    * [Preview](php/FrontendBundle/Domain/Preview.md)
    * [PreviewService](php/FrontendBundle/Domain/PreviewService.md)
    * [ProjectConfiguration](php/FrontendBundle/Domain/ProjectConfiguration.md)
    * [ProjectConfigurationService](php/FrontendBundle/Domain/ProjectConfigurationService.md)
    * [Redirect](php/FrontendBundle/Domain/Redirect.md)
    * [RedirectService](php/FrontendBundle/Domain/RedirectService.md)
    * [Region](php/FrontendBundle/Domain/Region.md)
    * Region
      * [Configuration](php/FrontendBundle/Domain/Region/Configuration.md)
    * [RenderService](php/FrontendBundle/Domain/RenderService.md)
    * RenderService
      * [ResponseDecorator](php/FrontendBundle/Domain/RenderService/ResponseDecorator.md)
    * [Route](php/FrontendBundle/Domain/Route.md)
    * [RouteService](php/FrontendBundle/Domain/RouteService.md)
    * [Schema](php/FrontendBundle/Domain/Schema.md)
    * [SchemaService](php/FrontendBundle/Domain/SchemaService.md)
    * [SitemapExtension](php/FrontendBundle/Domain/SitemapExtension.md)
    * [SitemapService](php/FrontendBundle/Domain/SitemapService.md)
    * [Stream](php/FrontendBundle/Domain/Stream.md)
    * [StreamContext](php/FrontendBundle/Domain/StreamContext.md)
    * [StreamHandler](php/FrontendBundle/Domain/StreamHandler.md)
    * StreamHandler
      * [AccountAddresses](php/FrontendBundle/Domain/StreamHandler/AccountAddresses.md)
      * [AccountOrders](php/FrontendBundle/Domain/StreamHandler/AccountOrders.md)
      * [AccountWishlists](php/FrontendBundle/Domain/StreamHandler/AccountWishlists.md)
      * [Cart](php/FrontendBundle/Domain/StreamHandler/Cart.md)
      * [Content](php/FrontendBundle/Domain/StreamHandler/Content.md)
      * [ContentList](php/FrontendBundle/Domain/StreamHandler/ContentList.md)
      * [Order](php/FrontendBundle/Domain/StreamHandler/Order.md)
      * [Product](php/FrontendBundle/Domain/StreamHandler/Product.md)
      * [ProductList](php/FrontendBundle/Domain/StreamHandler/ProductList.md)
    * [StreamOptimizer](php/FrontendBundle/Domain/StreamOptimizer.md)
    * StreamOptimizer
      * [MinimalProduct](php/FrontendBundle/Domain/StreamOptimizer/MinimalProduct.md)
    * [StreamService](php/FrontendBundle/Domain/StreamService.md)
    * [Tastic](php/FrontendBundle/Domain/Tastic.md)
    * Tastic
      * [Configuration](php/FrontendBundle/Domain/Tastic/Configuration.md)
    * [TasticFieldHandler](php/FrontendBundle/Domain/TasticFieldHandler.md)
    * TasticFieldHandler
      * [AccountWishlistsFieldHandler](php/FrontendBundle/Domain/TasticFieldHandler/AccountWishlistsFieldHandler.md)
      * [BreadcrumbFieldHandler](php/FrontendBundle/Domain/TasticFieldHandler/BreadcrumbFieldHandler.md)
      * [TreeFieldHandler](php/FrontendBundle/Domain/TasticFieldHandler/TreeFieldHandler.md)
    * [TasticFieldHandlerAdapterV2](php/FrontendBundle/Domain/TasticFieldHandlerAdapterV2.md)
    * [TasticFieldHandlerAdapterV3](php/FrontendBundle/Domain/TasticFieldHandlerAdapterV3.md)
    * [TasticFieldHandlerV2](php/FrontendBundle/Domain/TasticFieldHandlerV2.md)
    * [TasticFieldHandlerV3](php/FrontendBundle/Domain/TasticFieldHandlerV3.md)
    * [TasticFieldService](php/FrontendBundle/Domain/TasticFieldService.md)
    * [ViewData](php/FrontendBundle/Domain/ViewData.md)
    * [ViewDataProvider](php/FrontendBundle/Domain/ViewDataProvider.md)
  * Routing
    * ObjectRouter
      * [ProductRouter](php/FrontendBundle/Routing/ObjectRouter/ProductRouter.md)


Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).