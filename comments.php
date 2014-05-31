<?php
/*
The comments page for Bones
*/

// Do not delete these lines
	if ( ! empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<div class="alert alert-help">
			<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'patrikblom' ); ?></p>
		</div>
	<?php
		return;
	}
?>

<?php // You can start editing here. ?>

<?php if ( have_comments() ) : ?>
	<h3 id="comments" class="h3 centered-header"><?php comments_number( __( '<span>No</span> comments', 'patrikblom' ), __( '<span>1</span> comment', 'patrikblom' ), _n( '<span>%</span> comments', '<span>%</span> comments', get_comments_number(), 'patrikblom' ) );?></h3>
	
	<?php if (is_paged()) : ?>
	<nav id="comment-nav">
		<ul class="clearfix">
			<li><?php previous_comments_link() ?></li>
			<li><?php next_comments_link() ?></li>
		</ul>
	</nav>
	<?php endif; ?>

	<ol class="commentlist">
		<?php wp_list_comments( 'type=comment&callback=bones_comments' ); ?>
	</ol>

	<?php if (is_paged()) : ?>
	<nav id="comment-nav">
		<ul class="clearfix">
			<li><?php previous_comments_link() ?></li>
			<li><?php next_comments_link() ?></li>
		</ul>
	</nav>
	<?php endif; ?>

	<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<?php // If comments are open, but there are no comments. ?>
	<?php else : // comments are closed ?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'patrikblom' ); ?></p>
	<?php endif; ?>

<?php endif; ?>


<?php if ( comments_open() ) : ?>

<section id="respond" class="respond-form">
	<h3 id="comment-form-title" class="h3 centered-header"><?php comment_form_title( __( 'Leave a Reply', 'patrikblom' ), __( 'Leave a Reply to %s', 'patrikblom' )); ?></h3>

	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
		<div class="alert alert-help">
			<p><?php printf( __( 'You must be %1$slogged in%2$s to post a comment.', 'patrikblom' ), '<a href="<?php echo wp_login_url( get_permalink() ); ?>">', '</a>' ); ?></p>
		</div>
	<?php else : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="form">

		<?php if ( is_user_logged_in() ) : ?>

		<p class="info comments-logged-in-as"><?php _e( 'Logged in as', 'patrikblom' ); ?> <a href="<?php echo get_option( 'siteurl' ); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php _e( 'Log out of this account', 'patrikblom' ); ?>"><?php _e( 'Log out', 'patrikblom' ); ?> <?php _e( '&raquo;', 'patrikblom' ); ?></a></p>

		<?php else : ?>

		<div id="comment-form-elements" class="comment-form-elements clearfix">

			<div class="list-item">
				<label for="author" class="form-label"><?php _e( 'Name', 'patrikblom' ); ?> <?php if ($req) _e( '(required)'); ?></label>
				<input type="text" name="author" class="input-field" id="author" value="<?php echo esc_attr($comment_author); ?>" placeholder="<?php _e( 'Name *', 'patrikblom' ); ?>" required tabindex="1" <?php if ($req) echo "aria-required='true'"; ?>>
				<span class="fa"></span>
			</div>

			<div class="list-item">
				<label for="email" class="form-label"><?php _e( 'Mail', 'patrikblom' ); ?> <?php if ($req) _e( '(required)'); ?></label>
				<input type="email" name="email" class="input-field" id="email" value="<?php echo esc_attr($comment_author_email); ?>" placeholder="<?php _e( 'E-Mail *', 'patrikblom' ); ?>" required tabindex="2" <?php if ($req) echo "aria-required='true'"; ?>>
				<span class="fa"></span>
			</div>

		</div>

		<?php endif; ?>

		<p><textarea name="comment" id="comment" class="boxsized input-field comment-field" placeholder="<?php _e( 'Comment here...', 'patrikblom' ); ?>" tabindex="4"></textarea></p>

		<p class="centered-inner-container">
			<?php echo pb_get_cancel_comment_reply_link(__('Cancel reply','patrikblom')); ?>
			<input name="submit" type="submit" id="submit" class="btn btn-default btn-action" tabindex="5" value="<?php _e( 'Submit', 'patrikblom' ); ?>" />
			<?php comment_id_fields(); ?>
		</p>

		<?php do_action( 'comment_form', $post->ID ); ?>

	</form>
	
	<div class="alert alert-info post-info boxsized icon-paragraph">
		<p class="icon-column"><span class="fa fa-code"></span></p>
		<p id="allowed_tags" class="small text-column"><strong>XHTML:</strong> <?php _e( 'You can use these tags', 'patrikblom' ); ?>: <code><?php echo allowed_tags(); ?></code></p>
	</div>

	<?php endif; // If registration required and not logged in ?>
</section>

<?php endif; // if you delete this the sky will fall on your head ?>
