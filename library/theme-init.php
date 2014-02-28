<?php
add_action( 'after_setup_theme', 'init_theme', 17 );

function init_theme() {
	add_action( 'wp_enqueue_scripts', 'init_scripts_and_styles', 999 );
	
	pb_add_gallery_page();
}

function init_scripts_and_styles() {
	if (!is_admin()) {
		wp_deregister_script('jquery');
		
		wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', '', true );
		wp_register_script('base-js', get_stylesheet_directory_uri() . '/js/main.js', array( 'jquery' ), '', true );
		wp_register_script('masonry', '//cdnjs.cloudflare.com/ajax/libs/masonry/3.1.2/masonry.pkgd.js', array( 'jquery' ), '', true );
		wp_register_script('images-loaded', '//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.0.4/jquery.imagesloaded.min.js', array( 'jquery' ), '', true );
		wp_register_script('lazyload', '//cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js', array( 'jquery' ), '', true );
		//wp_register_script('infinite-scroll-helper', get_stylesheet_directory_uri() . '/js/vendor/infinite-scroll-helper.min.js', array( 'jquery' ), '', true );
		
		wp_register_style('base-normalize', '//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.0/normalize.min.css', array(), '', 'all');
		wp_register_style('theme-stylesheet', get_stylesheet_directory_uri() . '/css/style.css', array('base-normalize'), '', 'all');
		wp_register_style('theme-enhanced-styles', get_stylesheet_directory_uri() . '/css/enhance.css', array('theme-stylesheet'), '', 'all');
		
		wp_enqueue_style('base-normalize');
		wp_enqueue_style('theme-stylesheet');
		wp_enqueue_style('theme-enhanced-styles');
		
		wp_enqueue_script('base-js');
		wp_enqueue_script('masonry');
		wp_enqueue_script('images-loaded');
		wp_enqueue_script('lazyload');
		//wp_enqueue_script('infinite-scroll-helper');
	}
}

function pb_add_gallery_page () {
	if (isset($_GET['activated']) && is_admin()) {
		$page_title = 'Gallery';
		$page_template = 'gallery.php';
		$page_check = get_page_by_title($page_title);
		$new_page = array(
			'post_type' => 'page',
			'post_title' => $page_title,
			'post_content' => '',
			'post_status' => 'publish',
			'post_author' => 1,
		);
		if (!isset($page_check->ID)) {
			$new_page_id = wp_insert_post($new_page);
			if (!empty($page_template)) {
				update_post_meta($new_page_id, '_wp_page_template', $page_template);
			}
		}
	}
}

if ( function_exists('register_sidebar') ) {
   register_sidebar(array(
       'name'=> __('Footer', 'patrikblom'),
       'before_widget' => '<div class="footer-block widget %2$s" id=%1$s">',
       'after_widget' => '</div>',
       'before_title' => '<h4 class="widget-title">',
       'after_title' => '</h4>',
   ));
}