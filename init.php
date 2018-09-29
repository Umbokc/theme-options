<?php 

if(!defined(UCTO_MODE_PHP)) define(UCTO_MODE_PHP, false);
if(!defined(UCTO_OPTION_NAME)) define(UCTO_OPTION_NAME, 'u_custom_to_wp');
if(!defined(UCTO_OPTION_GROUP)) define(UCTO_OPTION_GROUP, 'u_custom_to_wp_group');

require_once 'ThemeOptions.php';

$u_to = new ThemeOptions(UCTO_OPTION_NAME, UCTO_OPTION_GROUP);
$ucto_data = $u_to->get_data();

add_action( 'admin_init', function() use($u_to) {
  register_setting($u_to->theme_option_group, $u_to->theme_option_name, 'theme_options_validate');
});
add_action( 'admin_menu', function() {
  add_theme_page('Настройки темы', 'Настройки темы', 'edit_theme_options', 'theme_options', 'theme_options_do_page');
});

function theme_options_do_page(){
  global $u_to;
  if (!isset($_REQUEST['settings-updated']))
    $_REQUEST['settings-updated'] = false;
  $u_to->run();
}

function theme_options_validate( $input ) {
  return $input;
}
