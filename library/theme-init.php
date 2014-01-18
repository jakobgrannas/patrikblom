<?php
add_action( 'after_setup_theme', 'init_theme', 17 );

function init_theme() {
	add_action( 'wp_enqueue_scripts', 'init_scripts_and_styles', 999 );
}

function init_scripts_and_styles() {
	if (!is_admin()) {
		wp_register_script('base-js', get_stylesheet_directory_uri() . '/js/main.js', array( 'jquery' ), '', true );
		
		wp_register_style('base-normalize', get_stylesheet_directory_uri() . '/css/vendor/normalize.min.css', array(), '', 'all');
		wp_register_style('theme-stylesheet', get_stylesheet_directory_uri() . '/css/style.css', array('base-normalize'), '', 'all');
		wp_register_style('theme-enhanced-styles', get_stylesheet_directory_uri() . '/css/enhance.css', array('theme-stylesheet'), '', 'all');
		
		wp_enqueue_style('base-normalize');
		wp_enqueue_style('theme-stylesheet');
		wp_enqueue_style('theme-enhanced-styles');
		
		wp_enqueue_script('base-js');
	}
}
