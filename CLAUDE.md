# CLAUDE.md

Notes for Claude Code sessions working in this Laravel package.

## Commands

- `composer check` runs Pint (check only), Larastan and Pest. Run it before pushing.
- `composer lint:fix` rewrites style problems. Pint is configured with
  `declare_strict_types`, so every PHP file needs `declare(strict_types=1);`.
- Run one test file while iterating: `./vendor/bin/pest tests/Feature/YourTest.php`.

## Library rules

- `composer.lock` is intentionally ignored. Do not commit it; CI resolves the
  newest versions the constraints allow, which is what consumers get.
- Depend on `illuminate/*` components, never on `laravel/framework`. If the
  package starts using a new component (`illuminate/http`, `illuminate/routing`,
  `illuminate/events`), add it to `require` **and** to the "Select
  Laravel/Testbench versions" step in `.github/workflows/ci.yml`.
- The CI matrix must mirror `composer.json`: Laravel 12 pairs with Testbench
  10 and PHP 8.2+, Laravel 13 pairs with Testbench 11 and PHP 8.3+. Check
  Packagist before changing it.
- Use `publishesMigrations()` (not `publishes()`) for migrations so
  published files get a fresh timestamp.
- Keep `.gitattributes` `export-ignore` in step with new dev-only files.

## Tests

- Tests run on Orchestra Testbench with SQLite in memory. Migrations are loaded
  through `defineDatabaseMigrations()` in `tests/TestCase.php`.
- Do not test against the package's own published config by editing files:
  use `config([...])` in the test.
- Commands that publish files (`vendor:publish`) write into the Testbench
  skeleton under `vendor/`, and a published config then shadows the package
  default in every later run. Delete what the test published in `afterEach`
  (see `tests/Feature/InstallCommandTest.php`).
- Anything that rebinds a contract in a test must use `app()->instance(...)`
  or `app()->bind(...)` before resolving the consumer. The manager is bound
  with `bind`, not `singleton`, for exactly that reason.

## Static analysis

- Larastan runs at level 8 over `src/`. Traits that only host applications
  `use` are reported as unused by PHPStan (`trait.unused`); add a targeted
  `ignoreErrors` entry with the reason, do not lower the level.

## Sandbox notes

- `composer install` as root needs `COMPOSER_ALLOW_SUPERUSER=1`.
- If `api.github.com` zipball downloads fail with "Could not authenticate
  against github.com", use `composer install --prefer-source`. The one package
  that cannot be cloned that way is `phpstan/phpstan` (its Packagist metadata
  has no `source`). Download the pinned `phpstan.phar` release and a shallow
  clone of the same tag, expose them as a temporary `path` repository in a
  throwaway `composer.tmp.json` (`COMPOSER=composer.tmp.json`), and never commit
  that workaround.
- CodeQL does not support PHP. The CodeQL workflow only analyses the GitHub
  Actions workflow files.
