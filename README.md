# theme-options
This plugin is a simple, fully responsive options framework for WordPress. Built on the WordPress Settings API, he supports a few of field types such as: input, h2, array, tab, h3, textarea, wp_editor, img.
##To enable via function.php
```php
require_once ( get_template_directory() . '/plugins/ThemeOptions/init.php' );
```
##Configs change
```php
define(UCTO_MODE_PHP, false);
define(UCTO_OPTION_NAME, 'u_custom_to_wp');
define(UCTO_OPTION_GROUP, 'u_custom_to_wp_group');
```
