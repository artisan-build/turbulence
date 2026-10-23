# Workflow — turbulence

Project profile for Solo-driven work in the shared `artisan-build/turbulence` package. This is a
library with no application shell.

## Phase and mode

- phase: pre-release library
- default mode: A-autonomous; merge each PR when CI is green
- merge method: squash and delete the branch with `gh pr merge --squash --delete-branch`
- release: manual; workers do not tag unless a brief explicitly authorizes it

## Test ownership

- Implementers run focused tests covering the files they edit, then `composer stan` and
  `composer lint -- --test` once before handoff.
- Implementers do not run the full suite.
- The coordinator runs the full hard gate once on each committed candidate before opening its PR,
  and once more after rework.

## Hard gate

- command: `composer ready`
- contents: Pint formatting, PHPStan static analysis, and the Pest suite
- run from the repository root on a committed SHA with a clean working tree

## CI

- workflow: `.github/workflows/tests.yml`
- the test matrix covers the PHP floor through PHP 8.5 and Laravel 11 through Laravel 13
- the coverage job runs after every test-matrix lane passes

## Dependency install

- command: `composer install --no-interaction`
- post-install: none beyond Composer's package-discovery scripts

## Ship details

- PR target: `artisan-build/turbulence` branch `main`
- branch naming: use a short type-prefixed slug
- merge only after every required GitHub check is green
- do not create tags or submit releases to Packagist without explicit authorization
