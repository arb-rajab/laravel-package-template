# Upgrade Guide

## Nothing to upgrade from yet

This file exists before the first breaking change so that change has an
obvious place to be documented. When a release changes public API behaviour in
a way that requires action from an adopting application, add a section:

```
## Upgrading from vX to vY

- What changed, and why.
- The exact code or config change an adopting app needs to make.
```

## Laravel and PHP version support

The package supports Laravel 12.x and 13.x through its `illuminate/*`
constraints (`^12.0|^13.0`). The CI matrix (`.github/workflows/ci.yml`) runs
the full suite against every supported PHP and Laravel combination. Dropping a
Laravel major is a breaking change: document it here and bump the package's
major version.
