=== Guest User ===
Contributors: palasthotel, edwardbock, janaeggebrecht
Donate link: https://palasthotel.de/
Tags: user, guest, author, login, security
Requires at least: 5.0
Tested up to: 7.1.2
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Label users as guests: they can be credited as post authors, but cannot sign in.

== Description ==

Guest authors, former staff or contributors who only send in their texts need a user account to appear as the author of a post - but they do not need to be able to sign in.

Guest User adds a checkbox "Is guest" to the user edit screen and to the "Add New User" screen. An account marked as guest

* cannot sign in with its password, neither at the login form nor through XML-RPC,
* is signed out of every session it still had open when it was marked,
* cannot use application passwords or any other way of authenticating against the REST API.

The account itself, its posts and its author archive stay as they are. Uncheck the box and the account can sign in again.

Developers can check an account with `guest_user_is_guest( $user_id )`.

== Installation ==

1. Install the plugin from Plugins > Add New, or upload `guest-user.zip` there
1. Activate it
1. Open a user's profile and check "Is guest"

== Frequently Asked Questions ==

= Can I mark my own account as guest? =

No. The checkbox is only shown when you edit other users, so you cannot lock yourself out.

= Where is the flag stored? =

In the user meta `_is_guest_user` with the value `yes`.

== Changelog ==

= 1.0.0 =
* First release
