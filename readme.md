# Description

This is WordPress plugin for convenient use of [holder.js](http://imsky.github.com/holder/) library by [Ivan Malopinsky](http://imsky.co/).

Holder generates customizable placeholder images right in browser, using JavaScript. You can use it both in templates (via template tag) and content (via shortcode).

Example usage:

```php
holder( array( 'height' => '50', 'width' => '50', 'theme' => 'industrial' ) );
```

```
Image [holder height="50" width="100" text="thumbnail"] in content
```

```php
holder_add_theme( 'wordpress', 'd54e21', '464646' );
```

# Installation

Plugin is a [Composer](https://getcomposer.org/) package and can be installed via:

```
composer create-project rarst/holder --no-dev
```

## Changelog

### 1.1

 - _(dependency update)_ holder.js v1.8
 - _(enhancement)_ implemented `font` argument for font family of text

### 1.0.2

 - _(docs)_ added Ivan Malopinsky account to contributors
 - _(enhancement)_ added default text support for custom themes

### 1.0.1

 - _(docs)_ fixed wrong function name in examples

### 1.0

 - Initial public release.