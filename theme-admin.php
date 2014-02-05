<?php
// admin_init
add_action('admin_init', 'pb_admin_init');
add_action('init', 'pb_create_gallery_taxonomies', 0);

function pb_admin_init() {
	// admin custom columns
	add_action('manage_posts_custom_column', 'pb_custom_columns');
	add_filter('manage_edit-gallery_columns', 'pb_add_new_gallery_columns');
	
	register_post_type('gallery', pb_register_gallery_post_type());
	add_image_size('admin-list-thumb', 40, 40, true); //admin thumbnail

	//add thumbnail images to column
	add_filter('manage_posts_columns', 'pb_add_post_thumbnail_column', 5);
	add_filter('manage_pages_columns', 'pb_add_post_thumbnail_column', 5);
	add_filter('manage_custom_post_columns', 'pb_add_post_thumbnail_column', 5);
}

// add theme support for thumbnails
// TODO: Add to admin hooks
if ( function_exists( 'add_theme_support')){
	add_theme_support( 'post-thumbnails' );
}


function pb_register_gallery_post_type () {
	// register and label gallery post type
	$gallery_labels = array(
		'name' => _x('Gallery', 'post type general name'),
		'singular_name' => _x('Gallery', 'post type singular name'),
		'add_new' => _x('Add New', 'Image'),
		'add_new_item' => __("Add New Image"),
		'edit_item' => __("Edit Image"),
		'new_item' => __("New Image"),
		'view_item' => __("View Image"),
		'search_items' => __("Search Images"),
		'not_found' =>  __('No images found'),
		'not_found_in_trash' => __('No images found in Trash'), 
		'parent_item_colon' => ''

	);
	$gallery_args = array(
		'labels' => $gallery_labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'query_var' => true,
		'rewrite' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'capability_type' => 'post',
		'supports' => array('title', 'excerpt', 'editor', 'thumbnail'),
		'menu_icon' => 'dashicons-format-gallery'
	);
	return $gallery_args;
}

// Create custom taxonomy
function pb_create_gallery_taxonomies(){
	register_taxonomy(
		'phototype', 'gallery', 
		array(
			'hierarchical'=> true, 
			'label' => 'Categories',
			'singular_label' => 'Category',
			'rewrite' => true
		)
	);	
}

function pb_add_new_gallery_columns( $columns ){
	$columns = array(
		'cb'				=>		'<input type="checkbox">',
		'pb_post_thumb'		=>		'Thumbnail',
		'title'				=>		'Title',
		'phototype'			=>		'Category',
		'author'			=>		'Author',
		'date'				=>		'Date'
		
	);
	return $columns;
}

function pb_custom_columns( $column ){
	global $post;
	
	switch ($column) {
		case 'pb_post_thumb' : echo the_post_thumbnail('admin-list-thumb'); break;
		case 'description' : the_excerpt(); break;
		case 'phototype' : echo get_the_term_list( $post->ID, 'phototype', '', ', ',''); break;
	}
}

// Add the column
function pb_add_post_thumbnail_column($cols){
	$cols['pb_post_thumb'] = __('Thumbnail');
	return $cols;
}

function pb_display_post_thumbnail_column($col, $id){
  switch($col){
    case 'pb_post_thumb':
      if( function_exists('the_post_thumbnail') )
        echo the_post_thumbnail( 'admin-list-thumb' );
      else
        echo 'Not supported in this theme';
      break;
  }
}

