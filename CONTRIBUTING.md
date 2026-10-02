# Contributing

## Branching

`main` is the default branch and always reflects what is released (or about to be
released). Work on a feature branch and open a pull request against `main`.

## Commit messages

Releases and the changelog are generated from the commit history, so commit messages
follow [Conventional Commits](https://www.conventionalcommits.org/):

```
<type>[optional scope][!]: <description>

[optional body]

[optional footer]
```

| Type | Effect on the version | Appears in changelog |
|---|---|---|
| `fix:` | patch (1.0.0 → 1.0.1) | yes, "Bug Fixes" |
| `feat:` | minor (1.0.0 → 1.1.0) | yes, "Features" |
| `feat!:` or `BREAKING CHANGE:` footer | major (1.0.0 → 2.0.0) | yes, highlighted |
| `docs:`, `refactor:`, `chore:`, `deps:`, `style:`, `test:`, `ci:` | none | no |

A pull request that should trigger a release needs at least one `fix:` or `feat:`
commit. When squash-merging, make sure the squash commit message itself is a
conventional commit — that is the message release-please reads.

### Which changes get `fix:` or `feat:`

Only changes that matter to someone using the plugin. `fix:` and `feat:` decide the
version *and* write the line that ends up in the changelog on the wordpress.org plugin
page, so the question to ask before committing is whether a user of the plugin would care
about that line.

Everything else takes a type that releases nothing — workflows and CI, release tooling,
repository documentation, internal refactoring, and anything touching files that are not
shipped. As a rule of thumb, a change confined to files outside `public/` is almost never
a `fix:`.

## Repository layout

`public/` is exactly what ships to WordPress.org. Everything outside it is
repository-only.

| Path | Description |
|---|---|
| `public/guest-user.php` | the plugin: header and the `Plugin` class that wires the components |
| `public/classes/Security.php` | refuses the login and the current user for guest accounts |
| `public/classes/AdminView.php` | the checkbox on the user screens |
| `public/classes/Repository.php` | reads and writes the `_is_guest_user` user meta |
| `public/public-functions.php` | `guest_user_is_guest()` for themes and other plugins |
| `public/vendor/` | composer's autoloader for `classes/`; regenerated when the plugin is packed |
| `public/languages/` | translations; `guest-user.pot` is generated with `wp i18n make-pot` |
| `public/readme.txt` | the wordpress.org listing |
| `guest-user.php` | development wrapper, loads `public/`; never deployed |

The main file `public/guest-user.php` must keep its name. WordPress identifies an
installed plugin by `<directory>/<main file>` and stores that pair in `active_plugins`;
renaming it deactivates the plugin on every site at the next update.

Text domains are written as the literal `"guest-user"`, not as `Plugin::DOMAIN`: neither
`wp i18n make-pot` nor translate.wordpress.org can read a constant.

## Local setup

There is nothing to build. wp-env runs without a configuration file and mounts the
repository as the plugin:

```sh
npx @wordpress/env start      # http://localhost:8888, admin / password
```

Create a second user, mark it as guest on its profile and try to sign in with it.

After changing a translatable string:

```sh
npx @wordpress/env run cli wp i18n make-pot wp-content/plugins/guest-user/public \
  wp-content/plugins/guest-user/public/languages/guest-user.pot --exclude=vendor
msgmerge -U public/languages/guest-user-de_DE.po public/languages/guest-user.pot
msgfmt -o public/languages/guest-user-de_DE.mo public/languages/guest-user-de_DE.po
```

`npm run pack` stages the payload in `build/guest-user/` and zips it to `guest-user.zip`
— the same payload the release deploys. It runs the shared script from
[palasthotel/github-workflows](https://github.com/palasthotel/github-workflows), which has
to be checked out next to this repository.

## Versions

Never edit version numbers by hand. `package.json`, `CHANGELOG.md`, `public/guest-user.php`
and the `Stable tag:` in `public/readme.txt` are all maintained by the release pipeline —
see [.github/WORKFLOWS.md](.github/WORKFLOWS.md).

`package.json` holds nothing but that version and the `pack` script, and has to stay:
release-please and the shared release scripts read the version from it.

Content changes to `public/readme.txt` (description, FAQ, tested-up-to) are of course done
by hand; just leave `Stable tag:` and the `== Changelog ==` entries alone.

## Checks

Every PR runs `php -l` against PHP 7.4, 8.2, 8.3 and 8.4, packs the plugin and checks the payload, and checks the
version carriers agree.
