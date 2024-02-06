# Changelog

All notable changes to `api-client` will be documented in this file.

* v1.0.0
    * Initial release
* v1.1.0
  * Cache circumvention by implementing
    `isCacheRetrievalAllowed()` method on the Payload.
* v2.0.0
  * Plug mechanism for development and testing purposes
  * Invalidation of cache by hierarchy key
* v3.0.0
  * Support for basic REST calls (no URL parameters)
  * Introduction of options parameter for `AbstractService` (use for e.g. guzzle options)
