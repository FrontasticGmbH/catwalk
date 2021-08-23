## [2.0.6] - 2021-07-06
### updated
- Retrieve experiments with status used_as_personalization

## [2.0.6] - 2021-06-21
### updated
- Update job to handle json

## [2.0.5] - 2021-06-16
### changed
- Add DEVIATED status for experiment / feature flags
- Add Kameleoon-client custom header

## [2.0.4] - 2021-06-09
### changed
- Update priority for creating cookie, domain parameter is taken if set in configuration

## [2.0.3] - 2021-05-25
### changed
- Change configuration file (.conf) to JSON file
- Add options about cookies (samesite, secure, httponly, domain)
- Set domain as optional for obtainVisitorCode as can be declared on configuration
- Update job to log errors

## [2.0.2] - 2021-04-21
### changed
- Add security when parsing json

## [2.0.1] - 2021-04-06
### Changed
- Add security when fetching empty response.

## [2.0.0] - 2021-02-11
### Changed
- Fetch configurations from automation api.
- Add Feature flags.
- Add targeting for experiments / feature flags.
- Add obtainVariationAssociatedData.
- Change Kameleoon\Exceptions to Kameleoon\Exception
- Rename Custom into CustomData
- Update default configuration path
- Update job name

## [1.1.9] - 2021-01-11
### Changed
- Update configuration file naming to be able to load multiple site code.
