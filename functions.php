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

require_once( 'custom-widgets.php' );

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

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
    ) );
}

add_filter('excerpt_length', 'pb_get_excerpt');

function pb_get_excerpt() {
    return 20;
}

// Add custom image sizes
add_image_size('pb-index-thumb', 180, 180, true);
add_image_size('pb-featured-image', 250, 250, true);
add_image_size('pb-gallery-thumb', 256, 0, true);

// Responsive formats
add_image_size('resp-tiny', 320, 0, true);
add_image_size('resp-small', 480, 0, true);
add_image_size('resp-medium', 640, 0, true);
add_image_size('resp-large', 1024, 0, true);

/**
 * Registers a custom gallery shortcode
 * Gets the configured post gallery with permalinks to the
 * to the associated gallery post
 */
add_shortcode('pb_gallery', 'register_gallery_shortcode');
function register_gallery_shortcode($attr) {
    $post = get_post();
	$parents = array();
	
	extract(shortcode_atts(array(
		'id'         => $post->ID,
		'image_ids'  => '',
		'include'    => '',
		'exclude'    => ''
	), $attr));
	
	$attachments = get_posts(array(
		'post_type' => 'attachment',
		'post__in' => array_map('intval', explode(',', $attr['image_ids']))
	));

	foreach ($attachments as $attachment) {
		array_push($parents, $attachment->post_parent);
	}
	wp_reset_postdata();
	
	$gq = new WP_Query(array(
		'post_per_page' => -1,
		'post_status' => 'publish',
		'post_type' => 'any',
		'exclude' => $post->ID,
		'ignore_sticky_posts' => 1,
		'post__in' => $parents
	));
	ob_start();
?>
	<section class="section">
		<div class="centered-inner gallery-section">
			<?php if ($gq->have_posts()) : while ($gq->have_posts()) : $gq->the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="image-block">
					<?php
						$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'pb-index-thumb');
						$thumb_src = $thumbnail[0];
						$thumb_width = $thumbnail[1];
						$thumb_height = $thumbnail[2];
						
						$thumb_id = get_post_thumbnail_id(get_the_ID());
						$alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
					?>
					<span class="preview-overlay"><span class="fa search-plus-icon"></span></span>
					<img data-original="<?php echo $thumb_src; ?>" width="<?php echo $thumb_width; ?>" height="<?php echo $thumb_height; ?>" class="preview-thumbnail" alt="<?php echo $alt; ?>">
				</a>
				<?php endwhile; ?>
				<div class="button-row">
					<a href="<?php echo home_url('/gallery'); ?>" class="btn btn-default btn-big"><?php _e('Hela galleriet', 'patrikblom'); ?></a>
				</div>
			<?php endif; ?>							
		</div>
	</section>
<?php
	wp_reset_postdata();
	$response = ob_get_contents();
	ob_end_clean();
	return $response;
}

/**
 * Gets post based on filters supplied via AJAX
 * TODO: Refactor!!!
 */

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
	$offset = $_POST['offset'];
	
	if(count($terms) == 0 || $terms == '') {
		$terms = get_terms($taxonomy, array (
			'fields' => 'names',
			'parent' => $include_children == 0 ? 0 : ''
		));
	}
	
	$args = array (
			'posts_per_page' => 2, // TODO: 4 after testing!
			'offset' => $offset,
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
				<?php
					$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'pb-gallery-thumb');
					$src = $thumb[0];
					$width = $thumb[1];
					$height = $thumb[2];
					
					$thumb_id = get_post_thumbnail_id(get_the_ID());
					$alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
				?>
				<a href="<?php the_permalink(); ?>" class="image-link"><img data-original="<?php echo $src; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" alt="<?php echo $alt; ?>" class="preview-thumbnail"></a>
			<?php endif; ?>
			<div class="image-text boxsized">
				<?php
				printf(__('<time class="updated date-stamp" datetime="%1$s" pubdate><span class="fa clock-icon"></span>%2$s %3$s</time>', 'bonestheme'), get_the_time('Y-m-j'), human_time_diff(get_the_time('U'), current_time('timestamp')), __('ago', 'patrikblom'));
				?>
				<h3 class="image-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				<div class="image-description"><?php the_excerpt(); ?></div>
			</div>
			<div class="image-footer clearfix">
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

/* Site header */

function pb_get_header() {
?>
		<nav class="boxsized main-menu animate menu-right menu-closed" id="main-nav">
			<div class="boxsized search-field-container">
				<?php get_search_form(); ?>
			</div>
			<?php
			$defaults = array(
				'container_class' => 'boxsized menu-items menu',
				'menu' => 'Main nav',
				'echo' => true,
				'fallback_cb' => 'wp_page_menu',
				'items_wrap' => '%3$s',
				'depth' => 0
			);

			wp_nav_menu($defaults);
			?>
		</nav>

		<div id="wrapper" class="wrapper animate menu-push">

			<header id="banner" class="banner animate">
				<div class="banner-img"></div>
				<div class="banner-inner-container">
					<div class="inner">
						<a href="#main-nav" id="menu-btn" class="nav-btn menu-btn open-menu-btn"><span class="fa menu-icon"></span></a>
						<a href="#" class="nav-btn menu-btn close-menu-btn"><span class="fa menu-icon"></span></a>

						<div class="logo-container">
							<div class="logo">
								<a href="<?php echo home_url(); ?>" class="bare-link" rel="nofollow">
									<span class="row1">Patrik</span>
									<span class="row2">Blom</span>
								</a>
							</div>
						</div>
						
						<div class="boxsized search-field-container">
						<?php get_search_form(); ?>
						</div>
					</div>
				</div>
			</header>
<?php
}

/************* COMMENTS*********************/

function pb_get_cancel_comment_reply_link($text = '') {
      if ( empty($text) ) {
          $text = __('Click here to cancel reply.');
	  }
      $hiddenCls = isset($_GET['replytocom']) ? '' : 'hidden';
      $link = esc_html( remove_query_arg('replytocom') ) . '#respond';
      return apply_filters('cancel_comment_reply_link', '<a rel="nofollow" id="cancel-comment-reply-link" class="btn btn-default cancel-reply ' . $hiddenCls . '" href="' . $link . '">' . $text . '</a>', $link, $text);
  }

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
