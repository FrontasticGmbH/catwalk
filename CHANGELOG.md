# catwalk Changelog

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
