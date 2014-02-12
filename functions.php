<?php
/*
Author: Eddie Machado
URL: htp://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, ect.
*/

/************* INCLUDE NEEDED FILES ***************/

/*
1. library/bones.php
	- head cleanup (remove rsd, uri links, junk css, ect)
	- enqueueing scripts & styles
	- theme support functions
	- custom menu output & fallbacks
	- related post function
	- page-navi function
	- removing <p> from around images
	- customizing the post excerpt
	- custom google+ integration
	- adding custom fields to user profiles
*/
require_once( 'library/bones.php' ); // if you remove this, bones will break
/* library/theme-init.php
 * 
 * 
 */
require_once( 'library/theme-init.php' );

require_once( 'theme-admin.php' );
/*
2. library/custom-post-type.php
	- an example custom post type
	- example custom taxonomy (like categories)
	- example custom taxonomy (like tags)
*/
//require_once( 'library/custom-post-type.php' ); // you can enable this if you like
/*
3. library/admin.php
	- removing some default WordPress dashboard widgets
	- an example custom dashboard widget
	- adding custom login css
	- changing text in footer of admin
*/
// require_once( 'library/admin.php' ); // this comes turned off by default
/*
4. library/translation/translation.php
	- adding support for other languages
*/
// require_once( 'library/translation/translation.php' ); // this comes turned off by default

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select 
the new images sizes you have just created from within the media manager 
when you add media to your content blocks. If you add more image sizes, 
duplicate one of the lines in the array and name it according to your 
new image size.
*/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!

add_filter('excerpt_length', 'pb_get_excerpt');

function pb_get_excerpt() {
    return 20;
}

add_action( 'admin_print_scripts-index.php', 'pb_enqueue_scripts' ); // Dashboard
add_action( 'admin_print_scripts', 'pb_enqueue_scripts' ); // Admin all
add_action( 'wp_print_scripts', 'pb_enqueue_scripts' ); // Frontend

function pb_enqueue_scripts() {
	// This will localize the link for the ajax url to your 'my-script' js file (above). You can retreive it in 'script.js' with 'myAjax.ajaxurl'
	wp_localize_script( 'base-js', 'pbAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}

add_action( 'wp_ajax_nopriv_load-filter2', 'prefix_load_term_posts' );
add_action( 'wp_ajax_load-filter2', 'prefix_load_term_posts' );
function prefix_load_term_posts() {
	$terms = $_POST['terms'];
	$taxonomy = $_POST['taxonomy'];
	$include_children = $_POST['includeChildren'];
	$post_type = $_POST['postType'];
	
	if(count($terms) == 0 || $terms == '') {
		$terms = get_terms($taxonomy, array (
			'fields' => 'names',
			'parent' => $include_children == 0 ? 0 : ''
		));
	}
	
	$args = array (
			'posts_per_page' => -1,
			'order' => 'DESC',
			'orderby' => 'date',
			'post_type' => $post_type,
			'post_status' => 'publish',
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field' => 'name',
					'terms' => $terms,
					'include_children' => $include_children
				)
			)
		);
		
	global $post;
	$myposts = get_posts($args);
	ob_start();
?>

	<?php foreach ($myposts as $post) : setup_postdata($post); ?>
	<div class="image-block" id="post-<?php the_ID(); ?>">
			<?php if (has_post_thumbnail()) : ?>
				<a href="<?php the_permalink(); ?>" class="image-link"><?php the_post_thumbnail(array(256, 256), array('class' => 'preview-thumbnail')); ?></a>
			<?php endif; ?>
			<div class="image-text boxsized">
				<?php
				printf(__('<time class="updated date-stamp" datetime="%1$s" pubdate><span class="fa clock-icon"></span>%2$s %3$s</time>', 'bonestheme'), get_the_time('Y-m-j'), human_time_diff(get_the_time('U'), current_time('timestamp')), __('ago', 'patrikblom'));
				?>
				<h3 class="image-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				<div class="image-description"><?php the_excerpt(); ?></div>
			</div>
			<div class="image-block-footer clearfix">
				<div class="footer-item num-comments"><span class="fa"></span><a href="<?php echo get_comments_number() > 0 ? get_permalink() . '#comments' : get_permalink(); ?>"><?php echo get_comments_number(); ?></a></div>
				<div class="footer-item num-likes"><span class="fa"></span><a href="#">-</a></div>
				<?php $num_terms = count(wp_get_post_terms($post->ID, $taxonomy)); ?>
				<?php if ($num_terms < 3) : ?>
					<div class="footer-item post-category"><span class="fa"></span><?php echo get_the_term_list($post->ID, $taxonomy, '', ', '); ?></div>
				<?php else : ?>
					<div class="footer-item post-category"><span class="fa"></span><a href="#" class="category-list-btn"><?php echo $num_terms . ' ' . __('tags', 'patrikblom'); ?></a></div>
					<div class="footer-item post-category-extended"><?php echo get_the_term_list($post->ID, $taxonomy, '', ', '); ?></div>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>

<?php
	wp_reset_postdata();
	$response = ob_get_contents();
	ob_end_clean();
	echo $response;
	die(1);
}

/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class('clearfix'); ?>>
			<article id="comment-<?php comment_ID(); ?>" class="comment-body clearfix">
				<header class="comment-author vcard">
					<?php
						/*
						  this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
						  echo get_avatar($comment,$size='32',$default='<path_to_url>' );
						 */
						$bgauthemail = get_comment_author_email();
					?>
					<img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5($bgauthemail); ?>?s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
					<?php printf(__('<cite class="author fn">%s</cite>', 'bonestheme'), get_comment_author_link()) ?>
					<time datetime="<?php echo comment_time('Y-m-j'); ?>" class="datetime"><span class="fa clock-icon"></span><?php echo human_time_diff( get_comment_time('U'), current_time('timestamp') ) . ' ago'; ?></time>
				</header>

				<?php if ($comment->comment_approved == '0') : ?>
					<div class="alert alert-info">
						<p><?php _e('Your comment is awaiting moderation.', 'bonestheme') ?></p>
					</div>
				<?php endif; ?>
				
				<section class="comment-content clearfix">
					<?php comment_text() ?>
				</section>

				<footer class="comment-footer">
					<ul class="comment-links clearfix">
						<li class="comment-link"><span class="fa reply-icon"></span><?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></li>
						<li class="comment-link"><span class="fa permalink-icon"></span><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>"><?php _e('Permalink', 'bonestheme'); ?></a></li>
						<?php edit_comment_link(__('(Edit)', 'bonestheme'), '<li class="comment-link align-right"><span class="fa edit-icon"></span>', '</li>') ?></li>
					</ul>
				</footer>
			</article>
	<?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!
?>
