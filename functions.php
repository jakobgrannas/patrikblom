<?php
/*
Author: Jakob Grannas
URL: https://github.com/jakobgrannas/patrikblom
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
4. library/translation/translation.php
	- adding support for other languages
*/
require_once( 'library/translation/translation.php' ); // this comes turned off by default
$locale = get_locale();
$locale_file = TEMPLATEPATH."/languages/$locale.php";
if ( is_readable($locale_file) )
require_once($locale_file);

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
					<a href="<?php echo home_url('/gallery'); ?>" class="btn btn-default btn-big"><?php _e('View Gallery', 'patrikblom'); ?></a>
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
			'posts_per_page' => -1, // TODO: 4 after testing!
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
				printf('<time class="updated date-stamp" datetime="%1$s" pubdate><span class="fa clock-icon"></span>%2$s %3$s</time>', get_the_time('Y-m-j'), pb_human_time_diff(get_the_time('U'), current_time('timestamp')), __('ago', 'patrikblom'));
				?>
				<h3 class="image-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				<div class="image-description"><?php the_excerpt(); ?></div>
			</div>
			<div class="image-footer clearfix">
				<div class="footer-item num-comments"><span class="fa"></span><a href="<?php echo get_comments_number() > 0 ? get_permalink() . '#comments' : get_permalink(); ?>"><?php echo get_comments_number(); ?></a></div>
				<?php $num_terms = count(wp_get_post_terms($post->ID, $taxonomy)); ?>
				<?php if ($num_terms < 4) : ?>
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

/************* Time/Date *******************/

/**
 * Determines the difference between two timestamps.
 *
 * The difference is returned in a human readable format such as "1 hour",
 * "5 mins", "2 days".
 *
 * @since 1.5.0
 *
 * @param int $from Unix timestamp from which the difference begins.
 * @param int $to Optional. Unix timestamp to end the time difference. Default becomes time() if not set.
 * @return string Human readable time difference.
 */
function pb_human_time_diff( $from, $to = '' ) {
	if ( empty( $to ) )
		$to = time();

	$diff = (int) abs( $to - $from );

	if ( $diff < HOUR_IN_SECONDS ) {
		$mins = round( $diff / MINUTE_IN_SECONDS );
		if ( $mins <= 1 )
			$mins = 1;
		/* translators: min=minute */
		$since = sprintf( _n( '%s ' . __('min', 'patrikblom'), '%s ' . __('mins', 'patrikblom'), $mins ), $mins );
	} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		if ( $hours <= 1 )
			$hours = 1;
		$since = sprintf( _n( '%s ' . __('hour', 'patrikblom'), '%s ' . __('hours', 'patrikblom'), $hours ), $hours );
	} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
		$days = round( $diff / DAY_IN_SECONDS );
		if ( $days <= 1 )
			$days = 1;
		$since = sprintf( _n( '%s ' . __('day', 'patrikblom'), '%s ' . __('days', 'patrikblom'), $days ), $days );
	} elseif ( $diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
		$weeks = round( $diff / WEEK_IN_SECONDS );
		if ( $weeks <= 1 )
			$weeks = 1;
		$since = sprintf( _n( '%s ' . __('week', 'patrikblom'), '%s ' . __('weeks', 'patrikblom'), $weeks ), $weeks );
	} elseif ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) {
		$months = round( $diff / ( 30 * DAY_IN_SECONDS ) );
		if ( $months <= 1 )
			$months = 1;
		$since = sprintf( _n( '%s ' . __('month', 'patrikblom'), '%s ' . __('months', 'patrikblom'), $months ), $months );
	} elseif ( $diff >= YEAR_IN_SECONDS ) {
		$years = round( $diff / YEAR_IN_SECONDS );
		if ( $years <= 1 )
			$years = 1;
		$since = sprintf( _n( '%s ' . __('year', 'patrikblom'), '%s ' . __('years', 'patrikblom'), $years ), $years );
	}

	return $since;
}

/************* COMMENTS *********************/

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
					<?php printf('<cite class="author fn">%s</cite>', get_comment_author_link()) ?>
					<time datetime="<?php echo comment_time('Y-m-j'); ?>" class="datetime"><span class="fa clock-icon"></span><?php echo pb_human_time_diff( get_comment_time('U'), current_time('timestamp') ) . ' ' . __('ago', 'patrikblom'); ?></time>
				</header>

				<?php if ($comment->comment_approved == '0') : ?>
					<div class="alert alert-info">
						<p><?php _e('Your comment is awaiting moderation.', 'patrikblom') ?></p>
					</div>
				<?php endif; ?>
				
				<section class="comment-content clearfix">
					<?php comment_text() ?>
				</section>
				
				<?php
					$additionalArgs = array(
						'reply_text' => __('Reply', 'patrikblom'),
						'login_text' => __('Log in to leave a comment', 'patrikblom'),
						'depth' => $depth,
						'max_depth' => $args['max_depth']
					);
				?>

				<footer class="comment-footer">
					<ul class="comment-links clearfix">
						<li class="comment-link"><span class="fa reply-icon"></span><?php comment_reply_link(array_merge($args, $additionalArgs)) ?></li>
						<li class="comment-link"><span class="fa permalink-icon"></span><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>"><?php _e('Permalink', 'patrikblom'); ?></a></li>
						<?php edit_comment_link(__('(Edit)', 'patrikblom'), '<li class="comment-link align-right"><span class="fa edit-icon"></span>', '</li>') ?></li>
					</ul>
				</footer>
			</article>
	<?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!
?>
