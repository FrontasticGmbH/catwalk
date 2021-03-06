# catwalk Changelog

## `1.12.7` (2021-07-16)

* fix: Link to tastify() docs & better tastic name in deprecation message.
* fix(FP-497,FP-851): Introduce __suppressNotTastifiedNotice property.

## `1.12.6` (2021-07-13)

chore: Fixed new phpstan return type notices

## `1.12.5` (2021-07-08)

* fix: issue with SSR because polyfill for date missing on window in react development scheduler implementation

## `1.12.4` (2021-07-06)

* chore: fix not on master removed the collection json
* fix: translation typo

## `1.12.3` (2021-06-24)


* preview fix
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic
* misc: Tagged release 1.12.2 for catwalk

## `1.12.2` (2021-06-18)

* fix: set cookie samesite values as null
* fix: cookie security setting for dev environment as "none" is not working there

## `1.12.1` (2021-06-16)

* fix: restored string replacement on product url generation

## `1.12.0` (2021-06-15)

* feat: Do not track bot actions
* chore(singleServer): updates to config based on testing

## `1.11.0` (2021-06-14)

* fix(FP-736): set session samesite as non Only available from Symfony 4.3
* fix: Pass correct arguments
* fix: output proper HTML5 appData div tag
* fix: add required parameter to action
* feat: Allow tracking to multiple goals in kameleoon

## `1.10.15` (2021-06-08)

* fix(FP-729): extracted update cart logic and use it on checkout action

## `1.10.14` (2021-06-03)

* fix: used column name to search hooks
* feat(FP-345): added test for well and bad formed routes
* feat(FP-345): added test for MasterLoader::loader
* chore(singleServer): removed port parameter
* chore(singleServer): fixed port error
* chore(FP-345): improved Master route field docs

## `1.10.13` (2021-05-27)

* chore(catwalk): updated formatting so its less readable to fix eslint errors...
* chore(catwalk): updated formatting for readability
* fix: Remove newlines from SQL as our logging can't handle them
* chore(catwalk): fix eslint errors
* chore(catwalk): pre-render async component height by device type

## `1.10.12` (2021-05-26)


* introduced states and provinces

## `1.10.11` (2021-05-20)

* chore(catwalk): removed TODOs on public files
* added missing comma
* added style propertly to SSR return

## `1.10.10` (2021-05-18)

* chore(catwalk): modify server_start to error if nodemon is not installed
* fix(FP-709): moved cart validation to Commercetools integration
* fix: We now delete based on sess_time, so create an index on that…
* fix: Disable session garbage collection

## `1.10.9` (2021-05-13)

* fix(FP-703): implemented redirection on category routering

## `1.10.8` (2021-05-07)

* fix: Fix the ContentController to use Context in viewAction

## `1.10.7` (2021-05-07)


* refresh store after account information is changed
* Merge branch 'master' of github.com:FrontasticGmbH/frontastic

## `1.10.6` (2021-05-04)

* fix: update user context after address, user details, password changes
* chore(catwalk): force mime dependency resolvutions to v1

## `1.10.5` (2021-04-27)

* fix: used correct product service on Product Category Controller

## `1.10.4` (2021-04-22)

* chore(FP-91) Revert CartApiController is used by AdyenController too, refactored AdyenController
* chore(boost): fixed eslint errors
* chore(FP-91) Remove abstract controller due to get logger parameter bag
* chore(FP-91) fix environment secret on controllers and register ApiCoreBundle Controllers arguments
* chore(FP-91) refactor controllers to use API interfaces instead of factory, fix dependencies cleanup, fix parameterbag secret
* chore(FP-91) refactor single use variables, fix dependencies cleanup, review comments
* chore(FP-91) fix dependencies cleanup review #1
* chore(FP-91) add the dependencies on the preview controller
* chore(FP-91) cleanup uncessary includes of AbstractController

## `1.10.3` (2021-04-06)

* fix: removed extra logs in catwalk CHANGELOG

## `1.10.2` (2021-04-06)

* fix: Missing DataObject base class for MasterPageMatcherRules.
* fix(FP-565): CS.
* fix(FP-565): Test case to ensure all replication targets filter.
* fix(FP-565): Wrap endpoint for page-matcher in EnvironmentReplicationFilter.
* fix(FP-565): Make EnvironmentReplicationFilter work for MasterServiceTest.
* fix(FP-565): Regression test for MasterService replication.
* fix: Missing DataObject base class for MasterPageMatcherRules.

## `1.10.1` (2021-03-25)

* fix: Do not include tailwind in CSS module processing

## `1.10.0` (2021-03-23)

* feat: Allow to specify status code for redirects
* chore(webpack): override the 'To create a production build, run npm build' message on yarn run

## `1.9.21` (2021-03-18)

* fix!: properly arrange import order for encrypted vaults
* chore(webpack): explicity set useYarn to true in createCompiler
* chore(catwalk): made wiping the writePayloadToFile overridable by providing a JSON_LOG_PATH

## `1.9.20` (2021-03-15)

* fix: typecast depth param used in NodeService:getTree()

## `1.9.19` (2021-03-11)

* chore(catwalk): conformed shell variable name
* chore(catwalk): removed node signal handling, undefined AbortController
* feat: Also build and commit server source map

## `1.9.18` (2021-03-11)

* fix: wrapped custom fields action in an array

## `1.9.17` (2021-03-09)

* fix: Ignore annotations for Symfony

## `1.9.16` (2021-03-09)

* chore(FP-447): improved documentation and log messages
* docs: Also documented HTTP account API
* fix: Remove host from swagger file
* fix: Removed host from URLs
* fix: improve SSR start scripts
* docs: HTTP API docs for wishlist API
* docs: Documented cart HTTP API
* fix(FP-447): throw and handle Cart not active exception
* checkout moved pay button to right column in last checkout step

## `1.9.15` (2021-03-04)

* feat: Allow TypeScript in node_modules
* fix: Session expiration in GC cases failed

## `1.9.14` (2021-03-03)

* fix(FP-421): logged error at ErrorHandler level

## `1.9.13` (2021-03-02)

* added error message in checkuot if products are out of stock

## `1.9.12` (2021-03-01)

* fix(FP-395): implemented build query for next page and calculate last (#623)
* fix: remove wirecard test
* fix: Code resilience
* fix: Fix E_NOTICE when writing uninitialized sessions

## `1.9.11` (2021-02-25)

* Added checkout translations
* Renamed next:payment to order now
* fix: Work with babel 7.13.* by also processing .mjs files
* New session handler that produces fewer db writes

## `1.9.10` (2021-02-24)

* fix: Work with babel 7.13.* by also processing .mjs files

## `1.9.9` (2021-02-23)

* fix: adds noop Element for Image element during SSR

## `1.9.8` (2021-02-22)

* fix FP-316: Throw error objects without casting thme to string
* Added translations

## `1.9.7` (2021-02-19)

* fix: Fixed webpack post processing import path
* feat: adyen integration, discounts, taxes
* chore: Tested default stream assignment
* chore: Clean up unused exception

## `1.9.6` (2021-02-18)

* fix: Do not override selected stream with default
* fix: ensured page exist before complete default streams

## `1.9.5` (2021-02-18)

## `1.9.4` (2021-02-18)

* hotfix: Handle iinitial session

## `1.9.3` (2021-02-18)

* fix: Created dummy cart stream handler

## `1.9.2` (2021-02-18)

* fix: Set default stream to first of its kind
* fix: Use master stream as stream, if non defined
* fix: Always preserve master streams
* fix: Wording
* fix: Wording & case
* fix: Added missing template
* fix: PHP 7.2 compatibility
* fix: POC to avoid massive mysql update attacks due to session writes (#603)

## `1.9.1` (2021-02-16)

* chore (catwalk): updated deprecated scss elseif
* fix: Reverted accidetally comitted experiemnt
* fix: Set ansible roles path

## `1.9.0` (2021-02-11)

* fix: Only include module webpack configuration if module is mentioned in package.json
* chore (catwalk): fixed eslint errors
* chore (catwalk): update linting errors
* feat: Allow to override SSR polyfills

## `1.8.0` (2021-02-09)

* chore: moved new scripts to paas/catwalk and modified templates accordingly
* fix: checked if request content exist before decode
* feat: Add index for faster session cleanup
* changed unambigious sourceType to target only domino packages
* chore: Faster JSON encoding in ContextService
* modification of build process to throw error when there's an error in the customer's config. 
* fix: Do not crash on `?s` in the URL
* fix: create package symlinks also on windows

## `1.7.0` (2021-02-02)

* feat: required library common 2.7 or higher on catwalk/composer.json
* !feat(fp-90): catwalk controllers (#580)
* chore: Increase body size limit to value required by prym
* feat: Allow to inject customer response handler
* chore: Moved external library from src/vendor to libraries/
* feat: allow lazy loading of media images
* fix: Made stupid custom ini parser more resilient

## `1.6.0` (2021-01-27)

* feat: included shippingInfo and taxed fields to Cart.js
* feat(FP-190): Mergeable CSP
* feat(FP-177): fcli addition of generate Tastic command (#562)
* fix: used correct parm order for processCommand
* refactor: replaced source by logSource and remove error time
* feat(FP-102): used error as array and use different source on logging
* fix: removes unused packages from catwalk
* fix: project instance instead of customer
* feat: Prevent click-jacking via CSP frame-ancestors

## `1.5.0` (2021-01-18)

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

## `1.4.2` (2021-01-06)

* fix!: Switch to default symfony error handling
* fix: Define fallback values in case properties are undefined

## `1.4.1` (2020-12-18)

* fix: ContextService and RedirectService use 2 different router services.
* fix: removed extra comma at the end of parameter list

## `1.4.0` (2020-12-18)

* feat: Log frontend errors without creating a DB connection

## `1.3.0` (2020-12-14)

* fix(FT-519): removed available shipping controller and use method as part of cart info
* fix(FT-519): removed parameters and included method documentation
* feat(FT-519): included shipping methods loaders

## `1.2.2` (2020-12-09)

* fix(FT-514): Track errors from a cron job.
* fix: increasing timeout and adding timeout logging
* locks @types/react version
* feat: adds typescript to svgr loader

## `1.2.1` (2020-12-01)

* fix: Parameter order deprecated
* fix: Switch to `apply spaceless` instead of `spaceless` in twig templates
* fix: map projectSpecificData in Cart
* fix: Set composer platform to PHP 7.4

## `1.2.0` (2020-11-27)

* feat: CategoryRouter for Category Master Pages.
* Built assets for release 2020.11.26.17.12
* fix: IE detection function

## `1.1.20` (2020-11-26)

* fix: return error code in debug error controller
* fix: correctly render error pages on locale change

## `1.1.19` (2020-11-13)

* fix: Set required values on images test
* fix: Added mock logger to unit test
* feat: log errors in custom stream handlers
* fix: use Filesystem to delete schema update file

## `1.1.18` (2020-11-04)

* fix: allows for images with only one size prop

## `1.1.17` (2020-10-30)

* feat: Implement Kameleoon tracking if configured for abtesting
* fix: ProjectBasicAuth listener should also deal with kernel exceptions
* fix: Do not pass height to Cloudinary if not necessary
* fix: Tested dimension calculation in image component
* fix: A little more resilience

## `1.1.16` (2020-10-16)

* fix: More resilient determination of Tastic name for BC check.
* fix: bugfixes, types and cleanups for tile2
* fix: Expose projectSpecificData through client side Cart domain model.

## `1.1.15` (2020-10-13)

**@TODO:** Please adapt these raw commit messages since last release into CHANGELOG entries – they will be added to the package changelog.

* fix: Also tested RemoteImage and fixed various issues
* fix: Do not pass height if not necessary
* fix: Tested dimension calculation in image component
* Deprecate all tastics and patterns in catwalk
* fix: Initialize query parameters for initial page
* feat: moves notifications to more global place

## `1.1.14` (2020-10-07)

* chore: Merged imports
* fix: Import deprecate helper in a way it also works in tests
* chore: Added translation
* chore: Use deprecate() helper to announce deprecation notices
* chore: Moved page grid to page/ and deprecated all patterns

## `1.1.13` (2020-10-02)

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

## `1.1.12` (2020-10-01)

* fix: Clear caches after adding bundle
* fix: Registered missing ShopwareBundle
* fix: Initialize query parameters for initial page
* feat: Adds notifications for add/remove from cart
* refactor: Adds Notifications to global layout

## `1.1.11` (2020-09-17)

**@TODO:** Please adapt these raw commit messages since last release into CHANGELOG entries – they will be added to the package changelog.

* feat: call product search API directly
* feat: move decorators to product search API
* fix: Display outline on cells again
* fix: Use correct notifier instance

## `1.1.10` (2020-09-15)

* Regenerated API documentation
* Generated TypeScript types for catwalk & common domain models
* Fix: Added default for regions access to work with empty object

## `1.1.9` (2020-09-11)

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

## `1.1.8` (2020-08-24)

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

## `1.1.7` (2020-08-11)

* feat: Improved displaying of SSR error message
* fix: Use default for undefined variable to not break SSR

## `1.1.6` (2020-08-10)

* feat: Allow another webpack modification at the very end of config generation

## `1.1.5` (2020-08-05)

* fix: Restore (again) missing CHANGELOG.md in catwalk

## `1.1.4` (2020-08-05)

* fix: Restore missing CHANGELOG.md in catwalk

## `1.1.3` (2020-08-05)

* Fixed release script

## `1.1.2` (2020-08-05)

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

## `1.1.1` (2020-07-30)

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

## `1.0.2` (2020-05-27)

* Stability improvements for library webpack modules

## `1.0.1` (2020-05-27)

* Allow webpack extensions from libraries (like our boost theme)

## `1.0.0` (2020-05-27)


* Initial stable release
