
## Version 1.60.2 (2023-05-04)


* feat: update firebase dependency in catwalk to v 6.4
* misc: Tagged release 1.60.1 for catwalk
* fix(FP-4270): Return RouteCollection instead of array
* feat: make jwt::decode to v6. 4
* feat: Update catwalk firebase dependencies
* fix(FP-4270): Don't rebuild routes on every request in coFE
* feat: update logger messages to have debug level
* feat: add env vars check for prod/staging and disable logs
* misc: Tagged release 1.60.0 for catwalk
* fix: use the correct react types package version
* feat: add filters for unnecessary logs in prod
* misc: Tagged release 1.59.4 for catwalk
* misc: Add back missing section in catwalk changelog
* misc: Tagged release 1.59.3 for catwalk
* fix: Remove sensitive data from the /frontastic/project endpoint
* merge studio-deployment-branch
* feat (FP-2687): Save page version as template from Editor
* misc: Tagged release 1.59.2 for catwalk
* feat: patch LocalizedValue to also accept locales with currencies
* feat: patch LocalizedValue to also accept locales with currencies
* misc: Tagged release 1.59.1 for catwalk
* misc: Tagged release 1.59.1 for catwalk
* misc: Tagged release 1.59.1 for catwalk
* misc: Tagged release 1.59.1 for catwalk
* misc: Tagged release 1.59.1 for catwalk
* feat: add checks for both locale with currency and without currency
* feat: add checks for both locale with currency and without currency
* feat: strip off currency from the locale if available and translate the page correctly
* misc: Tagged release 1.59.0 for catwalk
* feat(FP-3917): Fix tests
* feat(FP-3917): Accept Commercetools-Frontend-Extension-Version header on non-multitenant API Hub
* misc: Tagged release 1.58.0 for catwalk
* feat: cache initial page data for an hour as well
* feat: load gedmo doctrine-extensions from root vendor
* feat: cache the node IDs on navigation
* misc: Tagged release 1.57.0 for catwalk
* fix: correctly register postcss plugins
* misc: Tagged release 1.56.0 for catwalk
* feat: allow customers to set the session cookie lifetime
* feat: update tests
* Merge remote-tracking branch 'origin/master' into feat/fp-3610-reduce-backtrace-amount-logged-for-production-enviornment-PHP
* feat: update tests and method to format exception
* feat: update tests and method to format exception
* feat: update tests and method to format exception
* feat: add tests for JSON Formatter[MonoLog]
* feat: update formatExceptionTrace method
* misc: Tagged release 1.55.1 for catwalk
* Merge branch 'yarn-cleanup'
* Add missing entries to the changelog
* Merge branch 'master' into yarn-cleanup
* feat: use dep injection for json formatter -- env var
* feat: reduce php backtrace in prod
* misc: Tagged release 1.55.0 for catwalk
* fix: remove extra space om NextJsLocaleResolver
* feat: update context to support language only locales
* fix: add missing dependency in test
* Merge branch 'master' into feat/FP-2874-Cache_frontend_routes_in_mysql
* style(FP-3651): Make sure line length is <120 in TestControler
* feat: add request header checks for pageController
* feat(FP-3651): Return error in TestController when data source throws error with 200 status code
* misc: Tagged release 1.54.1 for catwalk
* style(FP-3640): Fix whitespace at end of line
* fix(FP-3640): Don't crash on empty tree structure
* feat: add pagecontroller tests for previewAction on CatWalk
* feat: add check for locale to preview page
* chore(FP-3536): updated saas/api-hub/ folder with latest changes on PageService
* misc: Tagged release 1.54.0 for catwalk
* feat(FP-3536): implemented in memory storage to reduce the number of SQL query pages
* feat(FP-3536): added index on page table;
* locale from header will be used when no query param was set
* requested changes + append "from path" to error message
* added new endpoint for dynamic pages
* fix(FP-3528): Fetch from DB if not in cache
* fix(FP-3528): Formatting fix
* fix(FP-3528): Port node cache fix to multi tenant API Hub
* fix(FP-3528): Set node cache to live only during the request
* misc: Tagged release 1.53.2 for catwalk
* misc: Tagged release 1.53.2 for catwalk
* fix: Set node cache TTL to 10 seconds
* Delete temp files on sitemap generation error
* fix: integration tests
* Merge branch 'master' into feat/FP-3245-Sitemap_Api
* feat(FP-3245): rename strucure service, add urls, and improve docs
* misc: Tagged release 1.53.1 for catwalk
* Merge branch 'master' into feat/FP-2874-Cache_frontend_routes_in_mysql
* misc: Tagged release 1.53.0 for catwalk
* feat(FP-3245): added tests for PageFolderService
* feat: ensure catwalk can be installed with PHP 8.1
* feat(FP-3245): added configuration to node tree data returned
* feat(FP-3245): implemented response class for tree endpoint
* fix(FP-3245): added missing class to controller test
* refactor(FP-3245): extracted node validation to a method
* feat(FP-3245): implemented API to get Page Folder Tree in a flat struture
* fix(FP-3261): Convert customDataSource icon to string before saving it into database To avoid SQL errors when null is replicated
* misc: Tagged release 1.52.0 for catwalk
* Merge branch 'master' into feat/FP-2348-Localized_slugs
* fix(route-caching): CS.
* fix(route-caching): Regenerate cache with p=0.1 when filectime in future.
* fix(route-caching): Test all current scenarios.
* fix(route-caching): Increase testability by allowing to mock random.
* fix(route-caching): Increase testability by allowing to mock time.
* fix(route-caching): Re-generate server-local route cache latest every 120 seconds.
* feat(FP-2348): included typescript types
* chore(FP-2348): added tag to have the object visible for apidocs
* chore(FP-2348): removed foreach duplication
* chore(FP-2348): moved entity to the right path
* chore(FP-2348): fix CS
* Merge branch 'master' into feat/FP-2348-Localized_slugs
* feat(FP-2348): implemented slug localization
* misc: Tagged release 1.51.1 for catwalk
* Fetch referenced node IDs inside groups as well
* feat(FP-2761): Checkstyle
* feat(FP-2761): Minor improvements
* feat(FP-2761): Cache schema per project, context from NodeService
* feat(FP-2761): Cache schema per project
* feat(FP-2761): Cache results of getByIds
* feat(FP-2761): Checkstyle fix
* feat(FP-2761): Add cache mock to tests
* feat(FP-2761): Fetch all referenced nodes in one single query and cache them
* misc(catwalk): Add comments to explain differences between api/version and api/endpoint/version endpoints
* fix(catwalk): Include /api/version endpoint in allowed routes
* misc: Tagged release 1.51.0 for catwalk
* fix: Allow fetching category by ID
* Merge branch 'master' into feat/FP-2761-Node_reference_performance
* fix: add missing mock properties
* feat: add php8 compatibility
* Merge branch 'master' into feat/FP-2761-Node_reference_performance
* misc: Tagged release 1.50.3 for catwalk
* Merge branch 'master' into feat/fp-2827-dataobject
* feat(FP-2761): cache node schema
* as a "normal" line break will be included in a string I had to append it with a dot
* hotfix: removed spaces and made sure that the strings are equal
* hotfix for build success: string seperated in two lines
* added coFE-Custom-Configuration as additional header accepted by CORS
* misc: now removed drop and leave only table creation
* misc: debug cli by remove table frontend routes before creation
* fix(FP-2874): set environment value on test
* Merge branch 'master' into feat/FP-2874-Cache_frontend_routes_in_mysql
* feat(FP-2874): include environment feature flag to cache route on database
* misc: Tagged release 1.50.2 for catwalk
* fix: When nextjsbundle is active only allow explicitly defined old routes
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic
* revert: Revert populating propertyExclude list of JsonSerializer
* Merge branch 'master' into feat/FP-2874-Cache_frontend_routes_in_mysql
* fix: Properly populate $propertyExcludeList of JsonSerializer in nextjs
* feat: alter table changelog to make possible handle bigger dates
* feat(FP-2874): modifedy changelog.change_number field type so can allocate dates
* refactor(FP-2874): rename migration file so date number can be handle by mysql
* feat(FP-2874): get routed by id to ensure always use the same entity
* misc: Tagged release 1.50.1 for catwalk
* exchange query type array to object
* feat(FP-2874): implemented test for store routes
* feat(FP-2874): implemented test for get routes method
* feat(FP-2874): implemented and used a gateway to store and read frontend routes instead of file cache
* feat(FP-2874): used an object to store the frontend routes
* refactor(FP-2874): changed table to store all frontend routes in a single row
* feat(FP-2874): created table and entity for frontend routes
* fix: increased max timeout for dynamic page handler and datasources
* Make error boundary injectable and use in demo
* misc: Tagged release 1.50.0 for catwalk
* Refresh context after password reset
* fix: increase cron timeout to 10 minutes
* Move ToastContainer to new NotificationContainer
* fix: linting
* feat(FP-2827): Replace kore dataobject with frontastic dataobject
* Merge branch 'master' into studio-deployment-branch
* misc: Tagged release 1.49.3 for catwalk
* fix: use stable version of data-object again
* misc: Tagged release 1.49.2 for catwalk
* fix: catch and log exceptions in tree field handler
* fix: replication of default page matcher rules did not work
* Merge branch 'master' into studio-deployment-branch
* fix: github oauth token (read only public repos)
* Merge branch 'master' into studio-deployment-branch
* fix: github force https
* Merge branch 'master' into studio-deployment-branch
* Revert "Revert "fix: Use forked DataObject with ignoreAdditionalProperties by default""
* misc: Tagged release 1.49.1 for catwalk
* Revert "fix: Use forked DataObject with ignoreAdditionalProperties by default"
* Revert "misc: Tagged release 1.49.1 for catwalk"
* misc: Tagged release 1.49.1 for catwalk
* fix: disable checking for live pages in trees
* fix: check rule type of default rules
* fix: add missing composer-runtime-api dependency
* feat(FP-2752): Add custom spans for streams
* test(FP-2752): Fix failing tests
* feat(FP-2752): Instrument action calls
* feat(FP-2752): Add custom spans for action and dynamic page handler calls
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic into feat/FP-2752-extension-tideways-instrumentation
* fix: Use forked DataObject with ignoreAdditionalProperties by default
* Merge branch 'master' into studio-deployment-branch
* feat(FP-2752): Create custom spans for each data source called
* feat(FP-2752): Add Tideways instrumentation to PageController
* feat(FP-2752): Create tideways wrapper classes for easier use
* feat(FP-2055): Redux fixes
* misc: Tagged release 1.49.0 for catwalk
* Merge branch 'studio-deployment-branch' into feat/redux-typescript
* Merge branch 'master' into LocalizeErrorMessage
* feat(FP-2055): Add more redux state type defs + refactor loaders
* fix: Allow translation of SEO keywords
* fix: Remove @type annotation from ServiceStatus.php
* feat(FP-2456): Apply solution to paas/catwalk NextJS
* Fix linting
* Add localized error message
* fix(FP-2552): Check if context exists inside jsonBody
* feat(FP-2552): Update extension types
* style(FP-2552): Style fixes
* feat(FP-2552): Return proper DataSourceResult in TestController
* feat(FP-2552): Create DataSourcePreviewPayloadElement, add previewPayload to DataSourceResult
* feat(FP-2552): Set isPreview property in TestController
* feat(FP-2552): Add dataSourcePreview to supported features
* feat(FP-2552): Add isPreview attribute to DataSourceContext
* feat(FP-2552): Add /frontastic/data-source path alias to TestController
* Merge branch 'master' into CreateSitemapsInChunks
* Check file extensions
* misc: Tagged release 1.48.0 for catwalk
* fix(FP-2521): Select translation before completing PageFolder data.
* fix(api-hub): remove check for datasourcepayload
* fix(api-hub): fix typehint
* chore(api-hub): fix coding style
* Merge branch 'master' into FP-1650-improve-error-message-for-broken-data-sources
* fix: add missing dependencies in tests
* fix: remove var_dumps from production code
* fix(api-hub): adjust test for new exception class
* Merge branch 'master' into FP-1650-improve-error-message-for-broken-data-sources
* feat(api-hub): make error message clearer if extension runner times out
* feat(FP-2400): hasLivePage property for tree nodes in classic
* fix(api-hub): handle guzzle (httpclient) connection errors
* feat(FP-2456): Proper fix for trees not being correctly handled
* feat(FP-2400): Apply solution to paas/catwalk NextJS
* Merge branch 'master' into FP-1650-improve-error-message-for-broken-data-sources
* Adds check for nextCursor !== null for error msg
* feat(FP-2297): Add 'queryProductsByMultipleCategories' to supported features array
* Create sitemaps in smaller chunks
* Merge branch 'master' into feat/fp-2400-live-page-check
* Merge branch 'master' into FP-1650-improve-error-message-for-broken-data-sources
* feat(api-hub): let datasource test route handle exceptions with context
* feat(api-hub): add context to datasource errors
* feat(api-hub): add new extension exception class
* feat(FP-2297): Return supported features along with catwalk and common lib versions in ApiController@versionAction
* misc: Tagged release 1.47.0 for catwalk
* fix: handle medium upload fail during fixture import | Added salesdemoct to allowed clear
* fix(FP-2400): Page service was transforming map into list
* feat(FP-2242): Fix streamHandlerSupplier being called on classic, resulting in a null pointer
* fix(api-hub): fix test controller to return actual result
* Merge branch 'master' into FP-1650-improve-error-message-for-broken-data-sources
* fix: Clear cache dir before integration tests in paas/catwalk and saas/api-hub/hub
* Merge branch 'master' into FP-1650-improve-error-message-for-broken-data-sources
* feat(FP-2242): Remove var_dump
* feat(FP-2242): Keep exact same behaviour on frontastic classic
* feat(FP-2242): catwalk tests for stream service
* Fix changelog format
* misc: Tagged release 1.46.1 for catwalk
* feat(FP-2242): Backport to catwalk
* feat (FP-2259 & FP-2218): Added clear database endpoint | Small fixes
* feat(FP-2242): Fix code formatting
* feat(FP-2242): Introduce StreamHandlerSupplier.php on Multi-tenant API Hub
* feat(FP-2242): Fix stream service DI problem for Classic
* feat(FP-2242): Ensure backwards compatibility with previous way of injecting stream handlers on catwalk
* feat(FP-2242): Backport to catwalk
* fix(api-hub): fix test-datasource route and controller
* misc: Tagged release 1.46.0 for catwalk
* Merge remote-tracking branch 'origin/master' into FP-2064-fetchApiHub-not-returning-method
* misc: Tagged release 1.45.0 for catwalk
* Merge remote-tracking branch 'origin/master' into FP-2064-fetchApiHub-not-returning-method
* chore(api-hub): add new types to catwalk
* Reverted changes to commit during release
* Merge branch 'master' of https://github.com/FrontasticGmbH/frontastic
* Updated CHANGELOG.md
* FP-2064: hopefully this will fix the coding style check in the build
* FP-2064: added getMethod to RequestServe createApiRequest() for returning method
* feat(FP-2186): Add ability to not include _type in JsonSerializer, use it in NextJsBundle
* misc: Tagged release 1.44.1 for catwalk
* feat: add UrlContext to Injection overridable components
* fix: injector replaced components removed are replaced when there are no overrides
* misc: Tagged release 1.44.0 for catwalk
* fix: Fix region identifier mapping in the correct place.
* fix: Regression of regions not indexed by region name anymore.
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic into feat/FP-2107-error-missing-pageMatchingPayload
* fix: ignore coding standar check for long line
* fix(FP-2107): Missing pageMatchingPayload in DynamicPageSuccessResult does not cause an error anymore
* feat: added Frontastic Locale and Path to headers config
* feat(FP-1728): Format
* feat(FP-1728): Apply changes on paas catwalk
* fix: replace demo method implementation with an error message
* misc: Tagged release 1.43.5 for catwalk
* fix(FP-2083): Updated catwalk path cache in an atomic operation.
* misc: Tagged release 1.43.4 for catwalk
* fix(api-hub): fix type error when no data source was selected yet
* fix: add query value to source path
* misc: Tagged release 1.43.3 for catwalk
* removed empty spaces
* removed comment
* style(catwalk): Fix styling error in urlContext.js that makes build fail
* Merge branch 'master' into feat/FP-1908-automate-changelog-updates
* fix: used correct format on Changelog for catwalk and common
* misc: Tagged release 1.43.2 for catwalk
* fix: Escape referer passed to Kameloon
* fixed error code
* updated catwalk with hash action
* Format changelog files
* fix(FP-1559): Properly handle null page folder references in PageFolderCompletionVisitor
* chore(api-hub): copy manual type files to source directory
* misc: Tagged release 1.43.1 for catwalk
* fix(FP-1934): Resilience of sitemap command for same-cursor loop.
* Merge branch 'master' into frontend-type-test
* feat: make proper type exports and rename conflicting types
* Merge branch 'master' into fix/apidocs-build-failing
* Merge remote-tracking branch 'origin/fix/apidocs-build-failing' into frontend-type-test
* feat: provide types for component values
* Fix types for build failure
* Merge branch 'master' into frontend-type-test
* Add logic to handle Map<string, string> type annotations
* feat: update extension types
* misc: Tagged release 1.43.0 for catwalk
* fix: test
* feat: use random translation instead of none
* fix(api-hub): Improve error message when action was not found
* misc: Tagged release 1.42.1 for catwalk
* fix(FP-1743): register ErrorHandler as service
* misc: Tagged release 1.42.0 for catwalk
* misc: Update nextjs_swagger.yml with correct preview params
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic into FP-1474-separate-query-params-using-headers
* misc: Tagged release 1.41.0 for catwalk
* feat(FP-1474): Update swagger with Frontastic-Path and Frontastic-Locale headers
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic into FP-1474-separate-query-params-using-headers
* feat: Added docs for missing preview endpoint.
* feat: allow field type dataSource in Classic as well
* feat: make success notification for remove from wishlist optional
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic into FP-1474-separate-query-params-using-headers
* fix: coding style
* feat(FP-1634): Checkstyle
* feat(FP-1634): Fix tests
* Merge branch 'master' into feat/FP-1634-ExtensionService-Timeout
* feat(FP-1634): Set timeout for ExtensionRunner calls
* feat(FP-1252): allow field type dataSource in schemas
* fix(FP-1474): Fix failing tests in PageControllerTest
* fix(FP-1474): Pass in query params to redirect service
* misc: Tagged release 1.40.0 for catwalk
* chore(ci): re-enable checking of extension types in CI
* fix(FP-1472): Return 404 on non existing page
* feat(FP-1474): Accept locale and path from query params for backwards compatibility
* feat(FP-1474): Implement query param separation using headers
* misc(FP-1399): Add unit tests for testing different isProduction values
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic into FP-1399/only-encrypt-session-in-production
* fix(FP-1678): Fix call to get project extension on ActionController
* feat(FP-1399): Only encrypt JWT session token in production
* misc: Tagged release 1.39.1 for catwalk
* feat(FP-811): Return 404 when product does not exist
* misc: Don't bootstrap symfony in paas/catwalk
* misc: Tagged release 1.39.0 for catwalk
* Merge branch 'master' of https://github.com/FrontasticGmbH/frontastic
* Merge branch 'studio-deployment-branch'
* Revert "fix (FP-1391): Added name attribute to cell configuration class"
* refactor(FP-1586): Encapsulate customerName inside a previewContext object
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic into FP-1586
* feat(FP-1609): ExtensionService - Test callAction
* feat(FP-1609): ExtensionService - Test callDynamicPageHandler
* feat(FP-1609): ExtensionService - Test callDataSource, callExtension and doCallAsync
* feat(FP-1609): ExtensionService - Test hasAction
* feat(FP-1609): ExtensionService - Test hasDynamicPageHandler
* feat(FP-1609): ExtensionService - Test hasExtension
* feat(FP-1609): ExtensionService - Test fetchProjectExtensions
* feat(FP-1609): Uncomment preloaded datasource value feature
* feat(FP-1609): Action controller test fix header
* feat(FP-1609): Header fix
* feat(FP-1609): Remove sync call
* feat(FP-1609): Remove unused JsonSerializer
* feat(FP-1609): Fix PHP formatting
* Fix action controller tests
* Separate sync and async call in different methods
* Merge branch 'studio-deployment-branch' into feat/layout-elements-name
* PHP Docs
* Rename HooksService to ExtensionService
* Refactor HooksService
* Using chained then on promises
* Async call to datasources hook
* fix (FP-1391): Added name attribute to cell configuration class
* misc: Tagged release 1.38.0 for catwalk
* Merge branch 'master' into feat/FP-1375-Preview_Route
* misc: Tagged release 1.37.2 for catwalk
* Added space to comment to satisfy eslint
* fix: added ts-ignore to autogenerated ComponentInjector overwriten files
* Fix ActionControllerTest and PageControllerTest
* Merge branch 'master' into feat/FP-1477-Action_Controller_Tests
* Remove unused import
* misc: Tagged release 1.37.1 for catwalk
* fix: add missing classes for 3/4 cells
* feat: resolve project config in next context
* fix: coding style
* fix: sort imports
* fix: remove unused import
* fix: coding style
* fix: qualify class name
* fix: sort imports
* fix: Add typehints
* feat(FP-1375): Change preview url from p to __preview only for NextJS
* misc: Tagged release 1.37.0 for catwalk
* misc: allowed ramsey/uuid library version ^4 as dependency
* feat(FP-1375): Change preview url from p to __preview in FrontendBundle
* feat(FP-1477): Stop passing functions in data providers
* feat: enhance nextjs swagger
* misc: Tagged release 1.36.0 for catwalk
* style(FP-1373): Fix style errors in CorsHandler
* fix(FP-1374): Check if headers are already sent before attempting to remove them
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic into FP-1373
* fix(FP-1586): Fix failing PageControllerTest caused incomplete context fixture
* feat(FP-1586): Read customer name from context in preview page
* fix(FP-1373): Remove CORS headers set by PHP to fix duplicate header errors
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic into FP-1373
* feat(FP-1477): Page controller tests
* feat(FP-1477): Fix dataprovider names
* fix(api-hub): replace content api with dummy for nextjs projects
* fix(api-hub): replace wishlist api with dummy for nextjs
* fix(api-hub): use dummy for cart api
* fix(api-hub): add dummy for AccountApi
* fix(api-hub): correct service config for product search api factory
* fix(api-hub): fix syntax and add parameter to factory config
* feat: wire dummy api classes in nextjs bundle
* feat(FP-1477): ActionControllerTest
* misc(FP-1399): Final implementation of JWT decryption testing
* style(FP-1399): Remove end of line whitespace
* refactor(FP-1399): Move nonce generation to separate function to allow easier unit test mocking
* misc(FP-1399): Add better unit tests for JWT decryption, encryption, work in progress
* misc(FP-1399): Add basic unit tests for JWT session decryption
* fix: overrides ordering
* style(FP-1373): Remove space from end of line in CorsHandler
* style(FP-1373): Fix line length style issues in CorsHandler
* misc(FP-1373): Add more unit tests for CorsHandler
* misc(FP-1373): Add basic unit tests for CorsHandler
* refactor(FP-1373): Use Request event headers to determine host instead of $_SERVER superglobal in CorsHandler
* refactor(FP-1373): Create CorsHandler event listener to be used instead of hardcoded solution in index.php
* style(FP-1399): Remove whitespaces from end of lines
* feat(FP-1399): Implement AES256 encryption for JWT session data
* misc: Tagged release 1.35.1 for catwalk
* fix: do not autowire localeresolver as it breaks customers code
* misc: Tagged release 1.35.0 for catwalk
* refactor(FP-1014): Use sprintf instead of context array when logging warning
* misc: Tagged release 1.34.3 for catwalk
* fix: add alias to LocaleResolverInterface for backwards-compatibility
* feat(FP-1218): Replace 'stream' with 'data source' in ViewData errors
* misc: Tagged release 1.34.2 for catwalk
* fix(FP-1014): Don't throw error when stream parameter is not array, log a warning instead
* fix(FP-767): Ignore errors in getOrder
* Remove requirement for python in kameleoon PHP SDK cron bash script
* Update Kameleoon SDK to version 2.0.8
* fix(FP-766): Keep backwards compatibility
* fix(FP-766): Remove path attribute from hyperlinks
* misc: Tagged release 1.34.1 for catwalk
* fix: exclude sensitive values on JsonSerializer
* fix(fp-1030): Sitemap generation using cursor instead of offset
* fix(fp-1030): Sitemap generation using cursor instead of offset
* misc: Tagged release 1.34.0 for catwalk
* chore(FP-1328): Better PHPdocs for new PageController response types
* feat(FP-1302): Static function instead of trait
* feat(FP-1302): Disable phpcs for _url property
* feat(FP-1302): Frontend reference component
* feat(FP-1302): PageFolderCompletionVisitorTest
* feat(FP-1302): Add the _url property to the PageFolderValue
* feat(FP-1302): Get url in the current locale for PageFolderCompletionVisitor
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic into hunor/redirects-in-nextjs
* misc: Tagged release 1.33.0 for catwalk
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic into hunor/redirects-in-nextjs
* feat(FP-1256): Unit test for createApiRequest
* feat(FP-1256): Remove cookies from ApiRequest
* feat(FP-1256): Add properties to request sent to starters.
* feat(FP-1328): Return RedirectResponse object when a dynamic page needs to redirect
* refactor(FP-1328): Return PagePreviewDataResponse class from PageController@previewAction
* feat(FP-1328): Return PageDataResponse object from PageController@indexAction
* fix(FP-1328): Only attempt redirect check if no site builder or dynamic page exists
* refactor(FP-1328): Naming changes, small refactoring - Rename FrontasticNextJsRedirectService to RedirectService - Use regular associative array instead of ParameterBag in RedirectService@getRedirectResponseForPath - Move RedirectResponse under the Frontend namespace
* misc: Tagged release 1.32.0 for catwalk
* feat(FP-1328): Create tests for FrontasticNextJsRedirectService
* feat(FP-1328): Add unsupported language checks in FrontasticNextJsRedirectService
* fix: Failure handling for data-source extensions without payload.
* chore(FP-1328): More formatting fixes in FrontasticNextJsRedirectService
* chore(FP-1328): Fix formatting in PageController and FrontasticNextJsRedirectService
* fix(FP-1328): Fix failing unit tests in PageControllerTest
* feat(FP-1328): Return redirect response from PageController@indexAction when needed
* feat(FP-1328): Add dependency injection to FrontasticNextJsRedirectService
* feat(FP-1328): Create FrontasticNextJsRedirectService
* feat(FP-1328): Create RedirectResponse data object
* fix(FP-1257): Mark Response->statusCode as int.
* fix(FP-1257): 1x "any" is enough for DynamicPageSuccessResult. ;)
* feat(FP-1257): A lot more documentation for API hub types.
* feat(FP-1257): Checked API hub types for requiredness & typing.
* misc: Tagged release 1.31.1 for catwalk
* fix: FP-1235 Removed the dataSourcePayload key
* fix: Typo to the JWT clear session handle for null
* misc: Tagged release 1.31.0 for catwalk
* fix(api-hub): make actions accessible again
* feat(api-hub): allow testing of DataSources by directly calling a HTTP endpoint
* fix: Updated copy
* feat(docs): Basic Swagger file for Frontastic Next.js APIs.
* misc: Tagged release 1.30.4 for catwalk
* Merge branch 'master' into feat/doc-next-exts
* misc: Tagged release 1.30.3 for catwalk
* feat: Use language locales to generate routes
* fix: Catwalk changelog
* misc: Tagged release 1.30.2 for catwalk
* refactor(api-hub): don't rename LocaleResolver class to retain backwards-compatibility
* fix(api-hub): introduce FT NextJs specific locale resolver
* misc: Tagged release 1.30.1 for catwalk
* feat: FP-1301 session-frontastic header BC with cookie for now
* misc: Tagged release 1.30.0 for catwalk
* fix(FP-1263): Do not log debug messages in production.
* misc: Tagged release 1.29.1 for catwalk
* fix: Reactivate accidentally removed autowiring of controllers.
* misc: Tagged release 1.29.0 for catwalk
* feat(FP-983): Better error reporting if action does not exist.
* fix: Typehint.
* feat(FP-983): Adjusted service definition for autowire.
* feat(FP-983): Fix test to have proper data for extended logic.
* feat(FP-983): Disable native PHP sessions for all Next.js routes.
* Merge branch 'master' into feat/FP-983
* misc: Tagged release 1.28.5 for catwalk
* misc: Tagged release 1.28.4 for catwalk
* misc: Tagged release 1.28.3 for catwalk
* misc: Tagged release 1.28.3 for catwalk
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic
* fix: missing account field
* misc: Tagged release 1.28.2 for catwalk
* fix: send DynamicPageContext to backend extension runner
* fix: handle register success action
* feat(FP-983): Command to play with FECL queries.
* feat(FP-983): Clenup ActionController initilization.
* feat(FP-983): Remove never used override for actions.
* fix: Make ActionContext a proper data object.
* feat(FP-983): Validate more input data in PageController.
* feat(FP-983): Return null of the dynamic page handler could not resolve.
* feat(FP-983): Allow "hooks" to return anything.
* feat(FP-983): Updated Debug dependency about deprecation.
* feat(FP-983): Ensure responses in Next.js are always treated as JSON.
* misc: Tagged release 1.28.1 for catwalk
* fix: Apply page folder data completion to tree structures, too.
* feat(FP-983): Deactivate a large bunch of event listeners for Next.js.
* feat(doc-next-exts): Fix location headers not set.
* misc: Tagged release 1.28.0 for catwalk
* feat: consider query parameters for variant selection when redirecting to new product URLs
* fix(api-hub): fix ActionContext initialization
* chore(api-hub): coding style
* refactor(api-hub): remove obsolete HooksCallBuilder
* feat(api-hub): pass right context to extension runner
* feat(api-hub): provide right context to extension actions
* misc: Tagged release 1.27.1 for catwalk
* fix: Setting session expiry to one month temporarily
* misc: Tagged release 1.27.0 for catwalk
* feat: Fall back to languages during finding translations
* misc: Tagged release 1.26.8 for catwalk
* fix: JWT cookie removal with clearCookie method
* misc: Tagged release 1.26.7 for catwalk
* feat: change remove cookie method for the session with same site to none
* feat: Endpoint to retrieve a frontend context for Next.js.
* misc: Tagged release 1.26.6 for catwalk
* feat: JWT cookie security policy enabled and sameSite cookie set to None
* misc: Tagged release 1.26.5 for catwalk
* feat: JWT cookie reset when null payload, we need to change this when the properties are fixed
* misc: Tagged release 1.26.4 for catwalk
* fix: JWT session exception handle, emptying the session and logging the error so is visible to the CLI
* fix: Function name in assertion.
* feat(FP-1159): Extend verification.
* feat(FP-1159): Started test case for PageController.
* Merge branch 'master' of https://github.com/FrontasticGmbH/frontastic
* misc: Tagged release 1.26.3 for catwalk
* fix(node-404): Gracefully handle non-existing page folder being referenced.
* fix(node-404): Data object to represent a tastic data error.
* misc: Tagged release 1.26.2 for catwalk
* fix: fixes from ant nextjs-types
* fix: set also as null the TypeScript type for LinkReferenceValue
* fix: add default value to fix types issue
* misc: Tagged release 1.26.1 for catwalk
* fix: accepted null as link value and removed target validation
* fix: verified targer value before create ReferenceValue
* Merge branch 'master' of https://github.com/FrontasticGmbH/frontastic
* misc: Tagged release 1.26.0 for catwalk
* fix: DataSourceContext did not receive Request information.
* fix: updated ComponentInjector regex to catch more formatting possibilities
* misc: Tagged release 1.25.0 for catwalk
* fix: json serializer removed from hookservice, jwt handle in php
* chore(api-hub): remove debug code
* fix: CS
* feat(api-hub): return error if projectId in payload doesn't match with endpoint
* misc: Tagged release 1.24.0 for catwalk
* misc: Tagged release 1.23.3 for catwalk
* fix: Removed wrong property from DataSourceReference.
* feat(FP-646): added correlation id as response header
* feat(FP-646): get trace id from php server array
* misc: Tagged release 1.23.2 for catwalk
* feat(FP-1083): Removed dataSourceType for DataSourceReference again.
* feat(FP-1083): Implemented basic translation of stream reference.
* fix: fix the ApiRequest for sessions
* fix(catwalk): backport logging fix for logstash from prym
* feat(FP-646): extracted correlation id logic to common library
* misc: Tagged release 1.23.1 for catwalk
* fix: implemented logout action to avoid 404 exception
* misc: Tagged release 1.23.0 for catwalk
* feat(FP-965): Actually match the dynamic page type from the extension framework.
* fix(FP-1070): Ignore group fields in completion of handled values.
* feat(api-hub): add type annotations so that classes are included in type generation
* chore(api-hub): delete code related to dynamic filters in NextJs API
* fix: Fix the session to use the sessionData object, remove array from JWT encoding
* fix: Checking the internal response not request for sessionData
* misc: Tagged release 1.22.2 for catwalk
* fix: setContent cast the
* misc: Tagged release 1.22.1 for catwalk
* fix: Do not force JSON encode as object in ActionController.
* misc: Tagged release 1.22.0 for catwalk
* misc: Tagged release 1.21.2 for catwalk
* misc: Tagged release 1.21.1 for catwalk
* misc: Tagged release 1.21.0 for catwalk
* fix: Link to sitemap docs.
* fix(FP-242): Fixed test fixture typo.
* fix(FP-242): Fixed Content-Type header for sitemaps.
* fix(FP-242): Cleanup outdated sitemap instances from DB after generating new.
* fix(FP-242): Remove filesystem sitemap once DB sitemap is generated.
* fix: Actually make our stateless requests stateless.
* fix(FP-242): Fix path led by / prevents sitemap file matching.
* fix(FP-242): Controller to serve sitemaps.
* fix(FP-242): Store sitemaps in database when feature flag is set.
* fix(FP-242): Storage method on SitemapService.
* fix(FP-242): Method to store a batch of sitemaps in a transaction.
* fix(FP-242): Updated SQL schema for sitemaps after model changes.
* fix(FP-242): Fixed ID generator strategy for auto_increment.
* fix(FP-242): Also store full file path for matching in the controller.
* fix(FP-242): Gateway to store sitemaps.
* fix(FP-242): Entity + configuration to store sitemap.
* fix: the Request sessionData types
* fix: the Request sessionData default value finally
* misc: Tagged release 1.21.3 for catwalk
* fix: NextJS Request sessionData optionally null
* misc: Tagged release 1.21.2 for catwalk
* fix: minor code style fix
* misc: Tagged release 1.21.1 for catwalk
* fix: empty session for JWT
* misc: Tagged release 1.21.0 for catwalk
* fix: fixing the stream service factory hooks array
* misc: Tagged release 1.20.3 for catwalk
* misc: Tagged release 1.20.2 for catwalk
* misc: Tagged release 1.20.1 for catwalk
* fix: phpstan has an issue with php-jwt
* feat: JWT Session decode encode and cookie management
* feat: JWT session initial commit. Some fixes on the action and hooks

## Version 1.60.1 (2023-05-04)

** Bug fixes **

- Fix bug that causes reduced performance for Commercetools Frontend customers with large number of Studio pages

## Version 1.60.0 (2023-04-28)

** Bug fixes **

- Use the correct version of the `@types/react` package (16 instead of 17) in the dependencies

## Version 1.59.4 (2023-04-24)

** No relevant changes **

## Version 1.59.3 (2023-04-21)

** Bug fixes **

- Security fix for Commercetools Frontend

## Version 1.59.2 (2023-03-31)

** Bug fixes **

- Strip the currency off the locale in Commercetools Frontend for page completion as well

## Version 1.59.1 (2023-03-22)

** New Features and Improvements **

- Strip the currency off the locale in Commercetools Frontend endpoints

## Version 1.59.0 (2023-03-20)

** New Features and Improvements **

- Accept Commercetools-Frontend header in Commercetools Frontend endpoints

## Version 1.58.0 (2023-03-13)

** New Features and Improvements **

- Cache Node IDs per route in redux in the frontend, so navigating to already visited pages is instantaneously
- Load the Gedmo doctrine extensions from the root directories vendor directory, if available

## Version 1.57.0 (2023-03-02)

** New Features and Improvements **

- Update webpack and related packages. This might need adjustments to custom webpack extensions
- Remove hardcoded list of customer names in clear endpoint

## Version 1.56.0 (2023-03-01)

** New Features and Improvements **

- Allow customers to set the session cookie lifetime using the `session_cookie_lifetime` variable in the `environment` file
- Limit exception backtraces to two frames in production

## Version 1.55.1 (2023-02-17)

** New Features and Improvements **

- Some JavaScript dependency updates

## Version 1.55.0 (2023-02-15)

** New Features and Improvements **

- Update context to support language only locales
- Add request header checks for pageController
- Return error in TestController when data source throws error with 200 status code
- Add possibility to store the route cache in the database

** Bug fixes **

- Remove extra space om NextJsLocaleResolver
- Add missing dependency in test
- Clean up temporary files on errors during sitemap generation

## Version 1.54.1 (2023-02-02)

* Fixed an issue that caused the API Hub to respond with an error when an empty tree field was present.

## Version 1.54.0 (2023-01-24)

** New Features and Improvements **

- Implemented index and session storage for Pages

** Bug fixes **

- Replace Node cache strategy with session storage 

## Version 1.53.2 (2023-01-16)

** New Features and Improvements **

- API for sitemap generation

** Bug fixes **

- Set node cache TTL to 10 seconds

## Version 1.53.2 (2023-01-16)

** New Features and Improvements **

- API for sitemap generation

** Bug fixes **

- Set node cache TTL to 10 seconds

## Version 1.53.1 (2022-12-14)

** New Features and Improvements **

- Bump version requirement of a dependency to ease the PHP 8.1 migration

## Version 1.53.0 (2022-12-13)

** New Features and Improvements **

- Add PHP 8.1 compatibility

## Version 1.52.0 (2022-11-30)

** New Features and Improvements **

- Improve node reference performance

- Implement Route caching

- Include Page Folder Breadcrumbs localized

## Version 1.51.1 (2022-11-11)

** Bug fixes **

- Re-enabled endpoint in coFE used for health checking deployments

## Version 1.51.0 (2022-11-08)

** Bug fixes **

- Category list pages work with more than 500 categories in commercetools. Before only the first 500 categories could be viewed. This fix requires and update of the frontastic/common library to version 2.37.0

## Version 1.50.3 (2022-11-04)

** Bug fixes **

- Added coFE-Custom-Configuration as additional header accepted by CORS

## Version 1.50.2 (2022-10-28)

** Bug fixes **

- When nextjsbundle is active only allow explicitly defined old routes

## Version 1.50.1 (2022-10-24)

* fix: increased max timeout for dynamic page handler and datasources

## Version 1.50.0 (2022-10-13)

** New Features and Improvements **

- Add a `NotificationContainer` around the application which can be overwritten and customized in projects

- Increase the timeout of cron jobs from 5 to 10 minutes

** Bug fixes **

- Refresh the context after successfully resetting the password

## Version 1.49.3 (2022-10-04)

** Bug fixes **

- Fix an issue with composer dependencies

## Version 1.49.2 (2022-10-04)

** Bug fixes **

- Fix an issue replicating default page matchers

- Catch and log exceptions in the tree field handler

## Version 1.49.1 (2022-09-27)

** New Features and Improvements **

- Add some custom Tideways spans

** Bug fixes **

- Fix a performance issue when fetching large page trees

- Fix a potential exception when fetching the current version

## Version 1.49.0 (2022-09-20)

** New Features and Improvements **

- Improve performance of the generation of sitemaps for product detail pages

- Allow the SEO keywords from the node schema to be translatable


** Bug fixes **

- Don't return a german error message on duplicate account errors by default

## Version 1.48.0 (2022-08-19)

** New Features and Improvements **

- Add new extension exception class 

- Return supported features along with catwalk and common lib versions

- Add 'queryProductsByMultipleCategories' to supported features


** Bug fixes **

- Select translation before completing PageFolder data. 

- Adjust test for new exception class

- Handle guzzle (httpclient) connection errors

## Version 1.47.0 (2022-08-04)

** New Features and Improvements **

- Improve performance by preventing extension-availability check in production

** Bug fixes **

- Handle medium upload fail during fixture import

## Version 1.46.1 (2022-07-14)

** Bug fixes **

- Allow customers to overwrite the UrlContext

## Version 1.46.0 (2022-07-07)

** New Features and Improvements **

- Remove type field from JSON serializer

## Version 1.45.0 (2022-06-30)

** New features and Improvements **

- The UrlContext can now be replaced with the ComponentInjector
- Add new types to catwalk

## Version 1.44.1 (2022-06-21)


** Bug fixes **

- Injector replaced components removed are replaced when there are no overrides

## Version 1.44.0 (2022-06-16)

** Bug fixes **

- Fix error when missing optional field pageMatchingPayload

** Improvements **

- Remove components from rendering which are not rendered on ANY device

- Added Frontastic Locale and Path to headers config


* fix(FP-2083): Updated catwalk path cache in an atomic operation.

## Version 1.43.4 (2022-05-19)

* fix(api-hub): fix type error when no data source was selected yet
* fix: add query value to source path
## Version 1.43.3 (2022-05-17)

* fix: removed empty spaces
* fix: removed comment
* chore: Fix styling error in urlContext.js that makes build fail

## Version 1.43.2 (2022-05-12)

* fix: Escape referer passed to Kameloon
* updated catwalk with hash action
* fix(FP-1559): Properly handle null page folder references in PageFolderCompletionVisitor
* chore(api-hub): copy manual type files to the source directory

## Version 1.43.1 (2022-05-05)

* fix(FP-1934): Resilience of sitemap command for same-cursor loop.

## Version 1.43.0 (2022-04-21)

* feat: use random translation instead of none
* fix(api-hub): Improve error message when action was not found

## Version 1.42.1 (2022-04-12)

* fix(FP-1743): register ErrorHandler as service

## Version 1.42.0 (2022-04-04)

* Accept `path` param as `Frontastic-Path` header in `/frontastic/page` endpoint
* Accept `locale` param as `Frontastic-Locale` header in `/frontastic/page` endpoint

## Version 1.41.0 (2022-04-04)

* Make success notification for remove from wishlist optional
* Set timeout for ExtensionRunner calls
* Allow field type dataSource in schemas

## Version 1.40.0 (2022-03-29)

* chore(ci): re-enable checking of extension types in CI
* fix(FP-1472): Return 404 on non existing page
* misc(FP-1399): Add unit tests for testing different isProduction values
* fix(FP-1678): Fix call to get project extension on ActionController
* feat(FP-1399): Only encrypt JWT session token in production

## Version 1.39.1 (2022-03-21)

* feat(FP-811): Return 404 when product does not exist
* misc: Don't bootstrap symfony in paas/catwalk

## Version 1.39.0 (2022-03-17)

* refactor(FP-1586): Encapsulate customerName inside a previewContext object
* feat(FP-1609): ExtensionService - Test callAction
* feat(FP-1609): ExtensionService - Test callDynamicPageHandler
* feat(FP-1609): ExtensionService - Test callDataSource, callExtension and doCallAsync
* feat(FP-1609): ExtensionService - Test hasAction
* feat(FP-1609): ExtensionService - Test hasDynamicPageHandler
* feat(FP-1609): ExtensionService - Test hasExtension
* feat(FP-1609): ExtensionService - Test fetchProjectExtensions
* feat(FP-1609): Uncomment preloaded datasource value feature
* feat(FP-1609): Action controller test fix header
* feat(FP-1609): Header fix
* feat(FP-1609): Remove sync call
* feat(FP-1609): Remove unused JsonSerializer
* feat(FP-1609): Fix PHP formatting
* fix (FP-1391): Added name attribute to cell configuration class

## Version 1.38.0 (2022-03-11)

* feat(FP-1375): Change preview url from p to __preview only for NextJS
* feat(FP-1375): Change preview url from p to __preview in FrontendBundle

## Version 1.37.2 (2022-03-09)

* chore: added space to comment to satisfy eslint
* fix: added ts-ignore to autogenerated ComponentInjector overwriten files

## Version 1.37.1 (2022-03-08)

* fix: add missing classes for 3/4 cells
* feat: resolve project config in next context
* fix: coding style
* fix: sort imports
* fix: remove unused import
* fix: coding style
* fix: qualify class name
* fix: sort imports
* fix: Add typehints

## Version 1.37.0 (2022-03-07)

* misc: allowed ramsey/uuid library version ^4 as dependency
* feat: enhance nextjs swagger

## Version 1.36.0 (2022-03-02)


* style(FP-1373): Fix style errors in CorsHandler
* fix(FP-1374): Check if headers are already sent before attempting to remove them
* fix(FP-1373): Remove CORS headers set by PHP to fix duplicate header errors
* fix(api-hub): replace content api with dummy for nextjs projects
* fix(api-hub): replace wishlist api with dummy for nextjs
* fix(api-hub): use dummy for cart api
* fix(api-hub): add dummy for AccountApi
* fix(api-hub): correct service config for product search api factory
* fix(api-hub): fix syntax and add parameter to factory config
* feat: wire dummy api classes in nextjs bundle
* fix: overrides ordering
* style(FP-1373): Remove space from end of line in CorsHandler
* style(FP-1373): Fix line length style issues in CorsHandler
* misc(FP-1373): Add more unit tests for CorsHandler
* misc(FP-1373): Add basic unit tests for CorsHandler
* refactor(FP-1373): Use Request event headers to determine host instead of $_SERVER superglobal in CorsHandler
* refactor(FP-1373): Create CorsHandler event listener to be used instead of hardcoded solution in index.php

## Version 1.35.1 (2022-02-17)

* fix: do not autowire localeresolver as it breaks customers code

## Version 1.35.0 (2022-02-17)

* feat(FP-1218): Replace 'stream' with 'data source' in ViewData errors
* fix(FP-1014): Don't throw error when stream parameter is not array, log a warning instead

## Version 1.34.3 (2022-02-15)

* fix: add alias to LocaleResolverInterface for backwards-compatibility

## Version 1.34.2 (2022-02-11)

* Update Kameleoon SDK to version 2.0.8

## Version 1.34.1 (2022-02-09)


* fix: exclude sensitive values on JsonSerializer

## Version 1.34.0 (2022-02-08)

* chore(FP-1328): Better PHPdocs for new PageController response types
* feat(FP-1302): Static function instead of trait
* feat(FP-1302): Disable phpcs for _url property
* feat(FP-1302): Frontend reference component
* feat(FP-1302): PageFolderCompletionVisitorTest
* feat(FP-1302): Add the _url property to the PageFolderValue
* feat(FP-1302): Get url in the current locale for PageFolderCompletionVisitor

## Version 1.33.0 (2022-02-03)

* feat(FP-1256): Unit test for createApiRequest
* feat(FP-1256): Remove cookies from ApiRequest
* feat(FP-1256): Add properties to request sent to starters.

## Version 1.32.0 (2022-01-27)

* fix: Failure handling for data-source extensions without payload.
* fix(FP-1257): Mark Response->statusCode as int.
* fix(FP-1257): 1x "any" is enough for DynamicPageSuccessResult. ;)
* feat(FP-1257): A lot more documentation for API hub types.
* feat(FP-1257): Checked API hub types for requiredness & typing.

## Version 1.31.1 (2022-01-21)


* fix: FP-1235 Removed the dataSourcePayload key
* fix: Typo to the JWT clear session handle for null

## Version 1.31.0 (2022-01-18)

* fix(api-hub): make actions accessible again
* feat(api-hub): allow testing of DataSources by directly calling a HTTP endpoint
* fix: Updated copy
* feat(docs): Basic Swagger file for Frontastic Next.js APIs.

## Version 1.30.4 (2022-01-11)

* chore: removed backwards compatible code for JWT cookie

## Version 1.30.3 (2022-01-11)

* feat: Use language locales to generate routes

## Version 1.30.2 (2022-01-11)


* refactor(api-hub): don't rename LocaleResolver class to retain backwards-compatibility
* fix(api-hub): introduce FT NextJs specific locale resolver

## Version 1.30.1 (2022-01-07)


feat: session-frontastic header BC with cookie for now

## Version 1.30.0 (2022-01-06)

* fix(FP-1263): Do not log debug messages in production.

## Version 1.29.1 (2022-01-06)

* fix: Reactivate accidentally removed autowiring of controllers.

## Version 1.29.0 (2022-01-05)

* feat(FP-983): Better error reporting if action does not exist.
* feat(FP-983): Adjusted service definition for autowire.
* feat(FP-983): Fix test to have proper data for extended logic.
* feat(FP-983): Disable native PHP sessions for all Next.js routes.

## Version 1.28.5 (2021-12-22)

* chore: refresh context once user is registered

## Version 1.28.4 (2021-12-21)

* fix: missing account field handling in registration

## Version 1.28.3 (2021-12-21)

* fix: missing account field

## Version 1.28.3 (2021-12-21)

* fix: missing account field

## Version 1.28.2 (2021-12-21)

* fix: handle register success action

## Version 1.28.1 (2021-12-14)

* fix: Apply page folder data completion to tree structures, too.

## Version 1.28.0 (2021-12-09)

* feat: consider query parameters for variant selection when redirecting to new product URLs
* fix(api-hub): fix ActionContext initialization
* refactor(api-hub): remove obsolete HooksCallBuilder
* feat(api-hub): pass right context to extension runner
* feat(api-hub): provide right context to extension actions

## Version 1.27.1 (2021-12-06)


*  JWT Setting session expiry to one month temporarily

## Version 1.27.0 (2021-12-03)

* feat: Fall back to languages during finding translations

## Version 1.26.8 (2021-11-26)


* JWT cookie removal with clearCookie method

## Version 1.26.7 (2021-11-25)


* JWT handle, Change to method and parameters on response for clear cookie
* Endpoint to retrieve a frontend context for Next.js.

## Version 1.26.6 (2021-11-24)


* JWT cookie security policy enabled and sameSite cookie set to None

## Version 1.26.5 (2021-11-24)


* JWT cookie reset when sending null payload

## Version 1.26.4 (2021-11-22)


* fix: JWT session exception handle, emptying the session and logging the error so is visible to the CLI
* Dynamic page routing fix for no available pages in a folder

## Version 1.26.3 (2021-11-11)

* fix: Gracefully handle non-existing page folder being referenced.

## Version 1.26.2 (2021-11-11)

* fix: fixes from ant nextjs-types
* fix: set also as null the TypeScript type for LinkReferenceValue
* fix: add default value to fix types issue

## Version 1.26.1 (2021-11-11)

* fix: accepted null as link value and removed target validation
* fix: verified targer value before create ReferenceValue

## Version 1.26.0 (2021-11-10)


* fix: DataSourceContext did not receive Request information.

## Version 1.25.0 (2021-11-09)


* fix: json serializer removed from hookservice, jwt handle in php
* chore(api-hub): remove debug code
* fix: CS

## Version 1.24.0 (2021-10-26)

* chore: updating the common dependency for catwalk
* !feat(FP-646): implemented traceability strategy adding a correlation-id to all requests and responses

## Version 1.23.3 (2021-10-26)


* fix: Removed wrong property from DataSourceReference.

## Version 1.23.2 (2021-10-22)


* Improved data source representation for next.
* Fixed logstash format edge cases.

## Version 1.23.1 (2021-10-21)

* fix: implemented logout action to avoid 404 exception

## Version 1.23.0 (2021-10-19)

* Actually match the dynamic page type from the extension framework.
* Ignore group fields in completion of handled values.
* Fix the session to use the sessionData object, remove array from JWT encoding
* Checking the internal response not request for sessionData

## Version 1.22.2 (2021-10-13)


* Fix the response content of index action

## Version 1.22.1 (2021-10-13)

* fix: Do not force JSON encode as object in ActionController.

## Version 1.22.0 (2021-10-13)

* fix: Scaling can lead to serving outdated sitemaps (details: https://docs.frontastic.cloud/changelog/changes-to-sitemaps)
* fix: Actually make our stateless requests stateless (details: https://docs.frontastic.cloud/docs/truly-stateless-controllers)
* fix: Request sessionData types

## Version 1.21.2 (2021-10-13)

* fix: Scaling can lead to serving outdated sitemaps (details: https://docs.frontastic.cloud/changelog/changes-to-sitemaps)
* fix: Actually make our stateless requests stateless (details: https://docs.frontastic.cloud/docs/truly-stateless-controllers)
* fix: Request sessionData types

## Version 1.21.1 (2021-10-13)

* fix: Scaling can lead to serving outdated sitemaps (details: https://docs.frontastic.cloud/changelog/changes-to-sitemaps)
* fix: Actually make our stateless requests stateless (details: https://docs.frontastic.cloud/docs/truly-stateless-controllers)
* fix: Request sessionData types

## Version 1.21.0 (2021-10-13)

* fix: Scaling can lead to serving outdated sitemaps (details: https://docs.frontastic.cloud/changelog/changes-to-sitemaps)
* fix: Actually make our stateless requests stateless (details: https://docs.frontastic.cloud/docs/truly-stateless-controllers)
* fix: Request sessionData types

## Version 1.21.3 (2021-10-12)


* Fix sessionData type

## Version 1.21.2 (2021-10-12)


Code style fix

## Version 1.21.1 (2021-10-12)


* Fix empty session for JWT

## Version 1.21.0 (2021-10-12)


* JWT Session storage
* Sandbox fix

## Version 1.20.3 (2021-10-11)


* Update common version for catwalk

## Version 1.20.2 (2021-10-11)


* Catwalk common version update

## Version 1.20.1 (2021-10-11)


* fix release version

## Version “1.20.0” (2021-10-11)


* fix: Fixing the hook service return type

## Version 1.19.10 (2021-09-28)

* fix(FP-1022): replaced missing custom field by projectSpecificData

## Version 1.19.9 (2021-09-27)

* fix: Pass context to node data visitor

## Version 1.19.8 (2021-09-27)

* feat: Render previews endpoint for Frontastic Next.js

## Version 1.19.7 (2021-09-23)

* feat: Initial draft for dynamic page handling in Next.js extensions.

## Version 1.19.6 (2021-09-23)

* fix(api-hub): make expectation of structure from extension more forgiving

## Version 1.19.5 (2021-09-23)

* fix(api-hub): also pass non-response objects to support stream handlers

## Version 1.19.4 (2021-09-23)

* fix(api-hub): pass response objects as specified

## Version 1.19.3 (2021-09-22)


* feat(api-hub): improve error handling, refactor return types
* fix(api-hub): simplify hooks api result handling
* fix(api-hub): fix type errors for hooksservice
* fix(nextjs): clear up types for passing data between hooks and controller
* fix(catwalk): add nextjs api classes to react mapper
* feat(apidocs): add description comment from classes to TS interfaces
* fix(catwalk): get summary of properties from type description
* fix(catwalk): make configurations inherit base configuration in nextjs types
* fix(catwalk): remove references from nextjs bundle to other bundles
* Delivier assets during a roll out
* feat(FP-968): Added basic filter API data objects.

## Version 1.19.2 (2021-09-10)

* fix: Only run 64 not 1000 curl processes in parallel for kameleoon reporting

## Version 1.19.1 (2021-09-08)

* fix: ComponentInjector overrides regex not recognising all string formats

## Version 1.19.0 (2021-09-08)

* feat: Also complete node configuration for Next.js.
* fix: Type information on Request::query.

## Version 1.18.1 (2021-09-07)

* fix: remove deviceType desktop flash on phone due to RegExp bug on UserAgent string
* chore: Delivier assets during a roll out

## Version 1.18.0 (2021-09-06)

* fix: set header type on nextjs Action Controller response
* feat(FP-953): Add name to PageFolder representations.

## Version 1.17.1 (2021-09-06)

* fix(FP-926): Error page data is not filtered by JsonSerializer.

## Version 1.17.0 (2021-09-03)

* fix: Do not remove tastic prop for translatable tastics
* feat(fp-935-polish): Typed structures for reference & tree + proper tree handling.
* feat(FP-935): Inline handled tastic field values.
* feat(fp-935): Completed translation visitor.
* feat(fp-935): Implemented most simple language selection.
* feat(fp-935): Create visitors through a factory to get the Context.
* feat(fp-935): Actually update tastic configuration after completion.
* feat(fp-935): FIXUP
* feat(fp-935): Renamed NodeUrlVisitor to PageFolderUrlVisitor.
* feat(fp-935): Inject the configuration visitors.
* feat(fp-935): First draft of completing URLs into tastic config.

## Version 1.16.2 (2021-08-30)

* fix: fixed broken test on stream handler
* fix: merged master parameters to stream parameters on handler

## Version 1.16.1 (2021-08-30)

* Adapted: The configuration file now has to be a JSON
* [SDK fix]: secure = false and samesite = None is an invalid default cookie configuration
* Adapted passing of credentials and check if we should run an experiment

## Version 1.16.0 (2021-08-26)

* chore: bumped version required of frontastic/common to 2.17.0
* feat(FP-932): created scaffolding to perform Algolia search

## Version 1.15.3 (2021-08-24)

* fix: wrap response in array only when there is actions
* fix: Custom Data Source removed environments, undeleted filter, cleanup on Gateway
* fix: CustomDataSource and Facet refactor to use the UserBundle MetaData object

## Version 1.15.2 (2021-08-19)

* fix: server build failure if @frontastic/theme-boost doesn't exist in node_modules

## Version 1.15.1 (2021-08-17)

* fix: incorrect webpack config schema
* fix: Added missing CssMinimizerPlugin import
* fix: Added TerserPlugin for JS minification in server production build
* fix: Added TerserPlugin for JS minification in browser production build

## Version 1.15.0 (2021-08-13)


* feat: Implement possibility to override actions to old controllers.
* feat: Controller for action extensions.
* fix: Do not die when hooks runner is down.

## Version 1.14.2 (2021-08-10)

* fix: correctly log exceptions which are no objecs
* Fixed incorrect info message
* Moved directory existence checking above stat analysis
* Added types and description for link packages, minor refactor
* fix: Only try linking when package is actually installed

## Version 1.14.1 (2021-08-10)

* fix: correctly log exceptions which are no objecs
* Fixed incorrect info message
* Moved directory existence checking above stat analysis
* Added types and description for link packages, minor refactor
* fix: Only try linking when package is actually installed

## Version 1.14.0 (2021-08-03)

* Re-added server file creation if not yet exists
* Added checking for nodemon, checks for existence of server file
* Fix linting errors in ComponentInjector generated files
* Removed remaining debugging, added component override to demo_english for CI testing
* Added component cache to complete ComponentInjector rework
* fix: Remove unused and incorrect typed propery.
* feat(custom-data-source): Fix PHPStan issues.
* feat(custom-data-source): CS.
* feat(custom-data-source): Fixed null value mapping and mapping test.
* feat(custom-data-source): Guard NextJsBundle by feature-flag.
* feat(custom-data-source): Example data-source registration now happens in bundle.
* feat(custom-data-source): Basics for bundle dependency injection.
* feat(custom-data-source): Fixed bundle class name.
* feat(custom-data-source): Fix: Also map children of non-mapped objects.
* feat(custom-data-source): Fix Node->path is a string, not an array.
* feat(custom-data-source): Deliver data source context to JS extensions.
* feat(custom-data-source): POC wiring of new data source handler.
* feat(custom-data-source): Implemented mapper from Frontastic React data structures.
* feat(custom-data-source): Verify stream parameter extraction works.
* feat(custom-data-source): Implement StreamHandlerV2 for new streams API.

## Version 1.13.0 (2021-07-29)

* feat: Moving our concept of decorators to frontastic 1.5
* feat(FP-859-nextjs-apis): Removed OpenAPI specs from this PR to make it dedicate.
* feat(FP-859-nextjs-apis): Chore: Syntax & CS fix.
* feat(FP-859-nextjs-apis): Fine-tuning after final session with Kore.
* feat(FP-859-nextjs-apis): Chore: Remove orphan docs.
* feat(FP-859-nextjs-apis): Fix: file name.
* feat(FP-859-nextjs-apis): Resolve minor adjustments noted on Github.
* feat(FP-859-nextjs-apis): Cell -> LayoutElement.
* feat(FP-859-nextjs-apis): Region -> Section.
* feat(FP-859-nextjs-apis): $cells -> $layout elements (naming guide).
* feat(FP-859-nextjs-apis): Removed $data property from Project.
* feat(FP-859-nextjs-apis): Removed $sequence from data objects.
* feat(FP-859-nextjs-apis): Removed $dataSourceParameters from DataSourceHandler.
* feat(FP-859-nextjs-apis): Allow redirecting from DynamicPageHandler.
* feat(FP-864-client-apis): Basic specification of client HTTP API.
* feat(FP-859-nextjs-apis): Fixed missing @replaces.
* feat(FP-859-nextjs-apis): Clarified docs and used standardized annotations.
* feat(FP-859-nextjs-apis): Initial draft for dynamic page handling API.
* feat(FP-859-nextjs-apis): Initial draft for action (aka Controller) API.
* feat(FP-859-nextjs-apis): Initial draft for DataSourceHandler API.

## Version 1.12.8 (2021-07-20)

* fix: Move NextJsBundle to correct component.

## Version 1.12.7 (2021-07-16)

* fix: Link to tastify() docs & better tastic name in deprecation message.
* fix(FP-497,FP-851): Introduce __suppressNotTastifiedNotice property.

## Version 1.12.6 (2021-07-13)

chore: Fixed new phpstan return type notices

## Version 1.12.5 (2021-07-08)

* fix: issue with SSR because polyfill for date missing on window in react development scheduler implementation

## Version 1.12.4 (2021-07-06)

* chore: fix not on master removed the collection json
* fix: translation typo

## Version 1.12.3 (2021-06-24)


* preview fix
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic
* misc: Tagged release 1.12.2 for catwalk

## Version 1.12.2 (2021-06-18)

* fix: set cookie samesite values as null
* fix: cookie security setting for dev environment as "none" is not working there

## Version 1.12.1 (2021-06-16)

* fix: restored string replacement on product url generation

## Version 1.12.0 (2021-06-15)

* feat: Do not track bot actions
* chore(singleServer): updates to config based on testing

## Version 1.11.0 (2021-06-14)

* fix(FP-736): set session samesite as non Only available from Symfony 4.3
* fix: Pass correct arguments
* fix: output proper HTML5 appData div tag
* fix: add required parameter to action
* feat: Allow tracking to multiple goals in kameleoon

## Version 1.10.15 (2021-06-08)

* fix(FP-729): extracted update cart logic and use it on checkout action

## Version 1.10.14 (2021-06-03)

* fix: used column name to search hooks
* feat(FP-345): added test for well and bad formed routes
* feat(FP-345): added test for MasterLoader::loader
* chore(singleServer): removed port parameter
* chore(singleServer): fixed port error
* chore(FP-345): improved Master route field docs

## Version 1.10.13 (2021-05-27)

* chore(catwalk): updated formatting so its less readable to fix eslint errors...
* chore(catwalk): updated formatting for readability
* fix: Remove newlines from SQL as our logging can't handle them
* chore(catwalk): fix eslint errors
* chore(catwalk): pre-render async component height by device type

## Version 1.10.12 (2021-05-26)


* introduced states and provinces

## Version 1.10.11 (2021-05-20)

* chore(catwalk): removed TODOs on public files
* added missing comma
* added style propertly to SSR return

## Version 1.10.10 (2021-05-18)

* chore(catwalk): modify server_start to error if nodemon is not installed
* fix(FP-709): moved cart validation to Commercetools integration
* fix: We now delete based on sess_time, so create an index on that…
* fix: Disable session garbage collection

## Version 1.10.9 (2021-05-13)

* fix(FP-703): implemented redirection on category routering

## Version 1.10.8 (2021-05-07)

* fix: Fix the ContentController to use Context in viewAction

## Version 1.10.7 (2021-05-07)


* refresh store after account information is changed
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic

## Version 1.10.6 (2021-05-04)

* fix: update user context after address, user details, password changes
* chore(catwalk): force mime dependency resolvutions to v1

## Version 1.10.5 (2021-04-27)

* fix: used correct product service on Product Category Controller

## Version 1.10.4 (2021-04-22)

* chore(FP-91) Revert CartApiController is used by AdyenController too, refactored AdyenController
* chore(boost): fixed eslint errors
* chore(FP-91) Remove abstract controller due to get logger parameter bag
* chore(FP-91) fix environment secret on controllers and register ApiCoreBundle Controllers arguments
* chore(FP-91) refactor controllers to use API interfaces instead of factory, fix dependencies cleanup, fix parameterbag secret
* chore(FP-91) refactor single use variables, fix dependencies cleanup, review comments
* chore(FP-91) fix dependencies cleanup review #1
* chore(FP-91) add the dependencies on the preview controller
* chore(FP-91) cleanup uncessary includes of AbstractController

## Version 1.10.3 (2021-04-06)

* fix: removed extra logs in catwalk CHANGELOG

## Version 1.10.2 (2021-04-06)

* fix: Missing DataObject base class for MasterPageMatcherRules.
* fix(FP-565): CS.
* fix(FP-565): Test case to ensure all replication targets filter.
* fix(FP-565): Wrap endpoint for page-matcher in EnvironmentReplicationFilter.
* fix(FP-565): Make EnvironmentReplicationFilter work for MasterServiceTest.
* fix(FP-565): Regression test for MasterService replication.
* fix: Missing DataObject base class for MasterPageMatcherRules.

## Version 1.10.1 (2021-03-25)

* fix: Do not include tailwind in CSS module processing

## Version 1.10.0 (2021-03-23)

* feat: Allow to specify status code for redirects
* chore(webpack): override the 'To create a production build, run npm build' message on yarn run

## Version 1.9.21 (2021-03-18)

* fix!: properly arrange import order for encrypted vaults
* chore(webpack): explicity set useYarn to true in createCompiler
* chore(catwalk): made wiping the writePayloadToFile overridable by providing a JSON_LOG_PATH

## Version 1.9.20 (2021-03-15)

* fix: typecast depth param used in NodeService:getTree()

## Version 1.9.19 (2021-03-11)

* chore(catwalk): conformed shell variable name
* chore(catwalk): removed node signal handling, undefined AbortController
* feat: Also build and commit server source map

## Version 1.9.18 (2021-03-11)

* fix: wrapped custom fields action in an array

## Version 1.9.17 (2021-03-09)

* fix: Ignore annotations for Symfony

## Version 1.9.16 (2021-03-09)

* chore(FP-447): improved documentation and log messages
* docs: Also documented HTTP account API
* fix: Remove host from swagger file
* fix: Removed host from URLs
* fix: improve SSR start scripts
* docs: HTTP API docs for wishlist API
* docs: Documented cart HTTP API
* fix(FP-447): throw and handle Cart not active exception
* checkout moved pay button to right column in last checkout step

## Version 1.9.15 (2021-03-04)

* feat: Allow TypeScript in node_modules
* fix: Session expiration in GC cases failed

## Version 1.9.14 (2021-03-03)

* fix(FP-421): logged error at ErrorHandler level

## Version 1.9.13 (2021-03-02)

* added error message in checkuot if products are out of stock

## Version 1.9.12 (2021-03-01)

* fix(FP-395): implemented build query for next page and calculate last (#623)
* fix: remove wirecard test
* fix: Code resilience
* fix: Fix E_NOTICE when writing uninitialized sessions

## Version 1.9.11 (2021-02-25)

* Added checkout translations
* Renamed next:payment to order now
* fix: Work with babel 7.13.* by also processing .mjs files
* New session handler that produces fewer db writes

## Version 1.9.10 (2021-02-24)

* fix: Work with babel 7.13.* by also processing .mjs files

## Version 1.9.9 (2021-02-23)

* fix: adds noop Element for Image element during SSR

## Version 1.9.8 (2021-02-22)

* fix FP-316: Throw error objects without casting thme to string
* Added translations

## Version 1.9.7 (2021-02-19)

* fix: Fixed webpack post processing import path
* feat: adyen integration, discounts, taxes
* chore: Tested default stream assignment
* chore: Clean up unused exception

## Version 1.9.6 (2021-02-18)

* fix: Do not override selected stream with default
* fix: ensured page exist before complete default streams

## Version 1.9.5 (2021-02-18)

## Version 1.9.4 (2021-02-18)

* hotfix: Handle iinitial session

## Version 1.9.3 (2021-02-18)

* fix: Created dummy cart stream handler

## Version 1.9.2 (2021-02-18)

* fix: Set default stream to first of its kind
* fix: Use master stream as stream, if non defined
* fix: Always preserve master streams
* fix: Wording
* fix: Wording & case
* fix: Added missing template
* fix: PHP 7.2 compatibility
* fix: POC to avoid massive mysql update attacks due to session writes (#603)

## Version 1.9.1 (2021-02-16)

* chore (catwalk): updated deprecated scss elseif
* fix: Reverted accidetally comitted experiemnt
* fix: Set ansible roles path

## Version 1.9.0 (2021-02-11)

* fix: Only include module webpack configuration if module is mentioned in package.json
* chore (catwalk): fixed eslint errors
* chore (catwalk): update linting errors
* feat: Allow to override SSR polyfills

## Version 1.8.0 (2021-02-09)

* chore: moved new scripts to paas/catwalk and modified templates accordingly
* fix: checked if request content exist before decode
* feat: Add index for faster session cleanup
* changed unambigious sourceType to target only domino packages
* chore: Faster JSON encoding in ContextService
* modification of build process to throw error when there's an error in the customer's config. 
* fix: Do not crash on `?s` in the URL
* fix: create package symlinks also on windows

## Version 1.7.0 (2021-02-02)

* feat: required library common 2.7 or higher on catwalk/composer.json
* !feat(fp-90): catwalk controllers (#580)
* chore: Increase body size limit to value required by prym
* feat: Allow to inject customer response handler
* chore: Moved external library from src/vendor to libraries/
* feat: allow lazy loading of media images
* fix: Made stupid custom ini parser more resilient

## Version 1.6.0 (2021-01-27)

* feat: included shippingInfo and taxed fields to Cart.js
* feat(FP-190): Mergeable CSP
* feat(FP-177): fcli addition of generate Tastic command (#562)
* fix: used correct parm order for processCommand
* refactor: replaced source by logSource and remove error time
* feat(FP-102): used error as array and use different source on logging
* fix: removes unused packages from catwalk
* fix: project instance instead of customer
* feat: Prevent click-jacking via CSP frame-ancestors

## Version 1.5.0 (2021-01-18)

* feat: Use locale data only if our own IntlProvider is used
* fix: sitemap paths were missing the generated subpath and therefore faulty
* fix: Upgraded @types/react consistently to 17.0.0
* fix: Prevent our loader API from going to error when loading a error master page.
* fix: Resilience for empty debug statements.
* feat: Cache serialized context in Twig extension.
* feat(CartFetcher): Make CartFetcher available in Catwalk.
* feat: TasticFieldHandlerV3 retrieving the Tastic definition in addition.
* fix: Do not override errors or redirect status codes when SSR fails.
* fix: Avoid sessions being created for Frontastic API requests.
* feat: Allow API responses to trigger redirects

## Version 1.4.2 (2021-01-06)

* fix!: Switch to default symfony error handling
* fix: Define fallback values in case properties are undefined

## Version 1.4.1 (2020-12-18)

* fix: ContextService and RedirectService use 2 different router services.
* fix: removed extra comma at the end of parameter list

## Version 1.4.0 (2020-12-18)

* feat: Log frontend errors without creating a DB connection

## Version 1.3.0 (2020-12-14)

* fix(FT-519): removed available shipping controller and use method as part of cart info
* fix(FT-519): removed parameters and included method documentation
* feat(FT-519): included shipping methods loaders

## Version 1.2.2 (2020-12-09)

* fix(FT-514): Track errors from a cron job.
* fix: increasing timeout and adding timeout logging
* locks @types/react version
* feat: adds typescript to svgr loader

## Version 1.2.1 (2020-12-01)

* fix: Parameter order deprecated
* fix: Switch to `apply spaceless` instead of `spaceless` in twig templates
* fix: map projectSpecificData in Cart
* fix: Set composer platform to PHP 7.4

## Version 1.2.0 (2020-11-27)

* feat: CategoryRouter for Category Master Pages.
* Built assets for release 2020.11.26.17.12
* fix: IE detection function

## Version 1.1.20 (2020-11-26)

* fix: return error code in debug error controller
* fix: correctly render error pages on locale change

## Version 1.1.19 (2020-11-13)

* fix: Set required values on images test
* fix: Added mock logger to unit test
* feat: log errors in custom stream handlers
* fix: use Filesystem to delete schema update file

## Version 1.1.18 (2020-11-04)

* fix: allows for images with only one size prop

## Version 1.1.17 (2020-10-30)

* feat: Implement Kameleoon tracking if configured for abtesting
* fix: ProjectBasicAuth listener should also deal with kernel exceptions
* fix: Do not pass height to Cloudinary if not necessary
* fix: Tested dimension calculation in image component
* fix: A little more resilience

## Version 1.1.16 (2020-10-16)

* fix: More resilient determination of Tastic name for BC check.
* fix: bugfixes, types and cleanups for tile2
* fix: Expose projectSpecificData through client side Cart domain model.

## Version 1.1.15 (2020-10-13)

**@TODO:** Please adapt these raw commit messages since last release into CHANGELOG entries – they will be added to the package changelog.

* fix: Also tested RemoteImage and fixed various issues
* fix: Do not pass height if not necessary
* fix: Tested dimension calculation in image component
* Deprecate all tastics and patterns in catwalk
* fix: Initialize query parameters for initial page
* feat: moves notifications to more global place

## Version 1.1.14 (2020-10-07)

* chore: Merged imports
* fix: Import deprecate helper in a way it also works in tests
* chore: Added translation
* chore: Use deprecate() helper to announce deprecation notices
* chore: Moved page grid to page/ and deprecated all patterns

## Version 1.1.13 (2020-10-02)

* fix: Clear caches after adding bundle
* fix: Registered missing ShopwareBundle
* fix: Initialize query parameters for initial page
* fix: removed console.log
* featadds notifications for add/remove from cart
* refactor: adds Notifications to global layout
* feat: moves notifications to more global place
* refactor: removes rehooks from catwalk as well
* refactor: removes storybook from catwalk
* refactor: replaces rehooks with isomorphioc version
* category filters initial commit
* feat: call product search API directly
* feat: put product search API in DIC
* feat: move decorators to product search API
* fix: Display outline on cells again
* fix: Use correct notifier instance
* chore: Extracted common webspocket code
* feat: Implemented backchannel in notifier
* chore: Regenerated API documentation
* chore: Documented more properties as @required
* fix: also mind the catwalk styles when purging the CSS
* fix: Added default for regions access to work with empty object
* chore: Set properties @required based on `NOT NULL` in DB schema
* chore: Added some more translations
* chore (a11y): CS & start providing translated labels to buttons
* chore: Updated type definitions
* chore: Added note about autogenerated files
* chore: Removed outdated types specification
* chore: Also reverted newly added docs
* chore: Revert docs changes to reduce diff size
* fix: Import under "full name" to reduce naming conflicts
* feat: Correctly import referenced types
* Ensure type renaming works again
* Generated TypeScript types for catwalk & common domain models
* Flagged types in catwalk

## Version 1.1.12 (2020-10-01)

* fix: Clear caches after adding bundle
* fix: Registered missing ShopwareBundle
* fix: Initialize query parameters for initial page
* feat: Adds notifications for add/remove from cart
* refactor: Adds Notifications to global layout

## Version 1.1.11 (2020-09-17)

**@TODO:** Please adapt these raw commit messages since last release into CHANGELOG entries – they will be added to the package changelog.

* feat: call product search API directly
* feat: move decorators to product search API
* fix: Display outline on cells again
* fix: Use correct notifier instance

## Version 1.1.10 (2020-09-15)

* Regenerated API documentation
* Generated TypeScript types for catwalk & common domain models
* Fix: Added default for regions access to work with empty object

## Version 1.1.9 (2020-09-11)

* Added support for auto-tastify
* Implementd translations directly in `tastify`
* Deprecate Tastics not wrapped into tastify()
* Got rid of lodash in catwalk
* Allow disabling client side hydration
* Return promise from all possible loader methods.
* Implemented support for TypeScript
* fix: Refresh context after login / logout
* fix: Connect cart & wishlist; Added connecting notifications & order
* fix: Dispatch correct result to success()
* fix: Image rendering resilience

## Version 1.1.8 (2020-08-24)

* fix: Ensure getCountry() BC and throw out mappings
* fix: updates react-redux to fix ssr errors
* fix: Extended and tested JavaScript locale handling
* fix: Run yarn install lso for no-paas customers
* feat: Read Boost Theme theme prperties from project.yml
* fix(shopify integration): Implement missing queryCategories methods in Backstage and Catwalk
* fix(webpack): Make linking dependencies more resilient
* fix: adding resilience to StreamService when data is present but no streamId
* Introduce nodeType and allow styling by this
* Allow other locales than specified in the locale resolver.

## Version 1.1.7 (2020-08-11)

* feat: Improved displaying of SSR error message
* fix: Use default for undefined variable to not break SSR

## Version 1.1.6 (2020-08-10)

* feat: Allow another webpack modification at the very end of config generation

## Version 1.1.5 (2020-08-05)

* fix: Restore (again) missing CHANGELOG.md in catwalk

## Version 1.1.4 (2020-08-05)

* fix: Restore missing CHANGELOG.md in catwalk

## Version 1.1.3 (2020-08-05)

* Fixed release script

## Version 1.1.2 (2020-08-05)

* build(frontasticli): release-notes for version 0.8.0
* fix: remove outdated comment
* feat: undelete old facets when they show up again
* fix(frontasticli): Create files with normal user instead of root
* feat(frontasticli): Put all config files into .frontasticli dir
* fix: Removed prefix env. on yarn vairable
* fix(frontasticli): Use a sane default location for traefik config
* feat(custom query): Included RawApiInput as paratemer to all ProductApi/Query implementations
* feat(custom query): Changed extension of Query from DataObject to ApiDataObject
* fix: missing proptypes for catwalk cell
* fix: Included statement to check OS version where releaseLibraries is running
* fix: Resilience for non-string hydration errors.

## Version 1.1.1 (2020-07-30)

* fix: sets timeout for cron processes to 5min (default is 1)
* fix(boost-theme): getting back the height transition + some refactor
* feat: DISABLE_YARN_INSTALL=1 in vagrant provision.
* feat(boost): adding products on wishlist brushed
* refactor(boost-theme): error handling on wishlist page
* change also resetPassword token to confirmationToken
* fix: node-depth was missing in the tree
* fix: confirmation-token was filtered
* feat(boost-theme): functionality for empty state
* feat: Use google trace id as request id
* fix: preventing user from entering empty credentials and seeing parts of the page
* feat(Nan): Empty state for orders and addresses
* fix: Do not expose frontastic_request_id to client routes.
* feat(BOOST-219): order detail UI finished
* added translations
* feat!: Catr/Wishlist Loaders separate methods for getContinuously().
* fix: Even more resilient check for Master Page in frontend.
* refactor: Adjusted routing order
* refactor(account): using AccountMenu on different pages
* fix(FT-19): Tastic replication happens over and over again.
* feat: Allow cart fetch without fetching continuously.
* Fixed loading indicator for nodes in intial request
* Code resilience
* adds ability for ip whitelisting again
* feat(account): user details form validation
* fix: Resilience in error rendering.
* fix(FT-15): Fixed master stream completion for groups, too.
* feat(account): simple password requirement
* feat(account): Changing password by user
* feat: custom fields
* fix: Resilience for missing route in master page detection.
* fix!: Context cache in ContextService not static anymore.
* feat: Sitemap generation: Single file, override public URL & locale.
* fix: eslint issues
* feat(account): responsive account ui
* Adds a null check to provided image sizes
* Setting up basic password recovery.
* connected with profile tastic and style improvemed
* Implemented newrelic extension support
* refresh after setting address
* BC: Rename confirmation token route parameter
* Add new RulerZ operator categorypathcontains
* eslint fixes
* Register and Login functionality.
* Enable fecl for category detail pages
* Renamed variables in page/tastic.jsx for readability.
* Setting button loaders on checkout panels.
* fixes setting proxy correctly

## Version 1.0.2 (2020-05-27)

* Stability improvements for library webpack modules

## Version 1.0.1 (2020-05-27)

* Allow webpack extensions from libraries (like our boost theme)

## Version 1.0.0 (2020-05-27)

* Initial stable release
