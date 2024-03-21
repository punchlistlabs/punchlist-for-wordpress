=== Punchlist ===
Contributors: plnic
Tags: punchlist, collaboration, feedback, annotation, tools
Requires at least: 5.5
Tested up to: 6.1.1
Stable tag: 1.4.4
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin will allow you to share your posts and pages (including drafts!) for collaboration on Punchlist.

== Description ==

This plugin is a companion to the Punchlist application. It allows you to create new projects and/or add new pages to existing projects without leaving the WordPress admin dashboard. 
It even creates draft previews that are hidden from the public but available via Punchlist, allowing you to collaborate with clients and peers without the need for issuing user accounts.
You will need a Punchlist account which is free forever. There is no limit to the number of projects that can be created.

We are always looking for ways to improve, so please send us any feedback, bug reports or feature requests.

== Frequently Asked Questions ==

= How do I get a Punchlist account? =

Head to the [Punchlist website](https://app.punchlist.com/register)

= How do I get an API key? =

Once you have an account head you ["My Settings"](https://app.punchlist.com/user/tokens) and fill out the form.

== Changelog ==
= 1.4.4 =
* Moves remote Punchlist script to the footer
* Updated installation instructions

= 1.4.0 =
* Prevents unnecessary warning

= 1.3.8 =
* Version bump

= 1.3.7 =
* Re-enables PHP 7.4 compatibility. 

= 1.3.6 =
* Fixed client error preventing retrieval of current projects

= 1.3.4 =
* Version bump

= 1.3.2 =
* URL fix

= 1.3.0 =
* Re-implements draft preview to work with the recently release Punchlist 2.0.

= 1.2.8 =
* Rolled back incorrect PHP version requirement

= 1.2.6 =
* Updated all dependecies to their latest version.
* Updates API endpoints.

= 1.2.5 =
* Fixes a bug which prevented retrieving name title in WP 5.9
* Updated all dependecies to their latest version.

= 1.2.4 =
* Updated domains to match Punchlist's infrastructure changes
* Updated all dependecies to their latest versions.

= 1.2.3 =
* Removed pre-populated field value from PL API key

= 1.2.2 =
* Corrected version mismatch in code vs README.

= 1.2.1 =
* Updated method names to avoid collisions.

= 1.2 =
* Added basic and sane security measures based on WP reviewer recommendations (thanks and sorry for sending it up half baked)
* Updates the API library to sanitize all third party responses
* No new functionality added

= 1.1 =
* Added support for adding pages to existing projects.

= 1.0 =
* Provides the ability to create projects from the edit screen.

== Upgrade Notice == 
= 1.2.8 =
* Updated API calls.

= 1.2.5 =
* Previous versions of this plugin won't be able to create a new Punchlist project in WP 5.9+
* Adding pages to existing projects would still work but it's recommened to update for full functionality

= 1.2.4 = 
* Fixes issues with API communication to the Punchlist application.

= 1.2.3 =
* The removal of the value removes a possible unescaped value from being echo'd.

= 1.2.2 =
* Avoid a version mismatch.

= 1.2.1 =
* Prevents possible conflicts with other third party code.

== Screenshots ==

1. Admin page
2. Editor