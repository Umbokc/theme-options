<?php

if(isset($_POST['delete_options'])){
	delete_option( 'u_panda_theme_option' );
}

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );


/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
	register_setting( 'u_theme_options', 'u_panda_theme_option', 'theme_options_validate' );
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
	add_theme_page( __( 'Настройки темы', 'sampletheme' ), __( 'Настройки темы', 'sampletheme' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}

/**
 * Create the options page
 */
if(function_exists( 'wp_enqueue_media' )){
	wp_enqueue_media();
}else{
	wp_enqueue_style('thickbox');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
}

require 'inc/custom-theme-options-class.php';

function theme_options_do_page(){

	$my_options = require 'inc/custom-theme-options.php';

	if (!isset($_REQUEST['settings-updated']))
		$_REQUEST['settings-updated'] = false;

	$to = new ThemeOptions($my_options, 'u_panda_theme_option', 'u_theme_options');
	$to->run();
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function theme_options_validate( $input ) {
	return $input;
}
