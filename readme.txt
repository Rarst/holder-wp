=== Holder ===
Contributors: Rarst, imsky
Tags: image, placeholder
Requires at least: 3.4.2
Tested up to: 3.4.2
Stable tag: trunk

Quick and flexible placeholders, created right in browser.

== Description ==

This is WordPress plugin for convenient use of [holder.js](http://imsky.github.com/holder/) library by [Ivan Malopinsky](http://imsky.co/).

Holder generates customizable placeholder images right in browser, using JavaScript. You can use it both in templates (via template tag) and content (via shortcode).

Example usage:

 - `holder( array( 'height' => '50', 'width' => '50', 'theme' => 'industrial' ) );`
 - `Image [holder height="50" width="100" text="thumbnail"] in content`
 - `holder_add_theme( 'wordpress', 'd54e21', '464646' );`

[Development repository and issue tracker](https://bitbucket.org/Rarst/holder/).

== Changelog ==

= 1.0.2 =
* _(docs)_ added Ivan Malopinsky account to contributors
* _(enhancement)_ added default text support for custom themes

= 1.0.1 =
* _(docs)_ fixed wrong function name in examples

= 1.0 =
* Initial public release.