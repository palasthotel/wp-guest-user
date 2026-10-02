# Guest User (WordPress-Plugin)

Guest User labels accounts as guests: they can be credited as post authors, but cannot
sign in. It is available on [WordPress.org](https://wordpress.org/plugins/guest-user/).

## Why

Guest authors, former staff or contributors who only send in their texts need a user
account to appear as the author of a post. They do not need to be able to sign in, and
every account that can is one more password that can leak.

## How it works

The plugin adds a checkbox "Is guest" to the user edit screen of *other* users and to
"Add New User". A checked box stores the user meta `_is_guest_user` with the value `yes`.

For an account marked as guest

| | |
|---|---|
| password login (login form, XML-RPC) | refused at the `authenticate` filter |
| open sessions | ended when the account is marked |
| any other authentication - cookies, application passwords, auth plugins | `determine_current_user` resolves to nobody |
| application passwords | not offered (`wp_is_application_passwords_available_for_user`) |

The account, its posts and its author archive are untouched. Unchecking the box restores
the previous state, application passwords included.

| API | Type | Purpose |
|---|---|---|
| `guest_user_is_guest( $user_id )` | function | whether an account is marked as guest |

## Repository layout

`public/` is exactly what ships to wordpress.org; everything else is repository-only.
`guest-user.php` in the root is a development wrapper that loads `public/`, so the whole
repository can be symlinked into `wp-content/plugins` during development.

Releases are cut by release-please from conventional commits and deployed to the
wordpress.org SVN by GitHub Actions — see [.github/WORKFLOWS.md](.github/WORKFLOWS.md).
Contribution rules and the local setup are in [CONTRIBUTING.md](CONTRIBUTING.md).

## License

GPL-3.0-or-later, see [LICENSE](LICENSE).
