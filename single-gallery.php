<?php get_header(); ?>
<div id="wrapper" class="wrapper boxsized">
<div class="boxsized single-image-post">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<article class="main-content" id="post-<?php the_ID(); ?>" role="article" itemscope itemtype="http://schema.org/BlogPosting">
				<?php if ( has_post_thumbnail() ) : ?>
					<a title="<?php the_title_attribute( "echo=1" ); ?>" rel="bookmark" class="full-width-image">
					<?php if (class_exists( 'RIP' )){
						echo RIP::get_picture(get_the_post_thumbnail(get_the_ID(), 'full'));
					} else {
						the_post_thumbnail('full');
					}?>
					</a>
				<?php endif; ?>
			
			<?php //the_content(); ?>

			<?php //comments_template(); ?>
		</article>
	<footer class="single-image-footer image-footer clearfix">
			<div class="footer-item num-comments"><span class="fa"></span><?php echo get_comments_number(); ?></div>
			<div class="footer-item num-likes"><span class="fa"></span><a href="#">-</a></div>
			<?php $taxonomy = 'phototype'; ?>
			<?php $num_terms = count(wp_get_post_terms($post->ID, $taxonomy)); ?>
			<?php if ($num_terms < 3) : ?>
				<div class="footer-item post-category"><span class="fa"></span><?php echo get_the_term_list($post->ID, $taxonomy, '', ', '); ?></div>
				<?php else : ?>
				<div class="footer-item post-category"><span class="fa"></span><span class="category-list-btn"><?php echo $num_terms . ' ' . __('tags', 'patrikblom'); ?></div>
				<div class="footer-item post-category-extended"><?php echo get_the_term_list($post->ID, $taxonomy, '', ', '); ?></div>
			<?php endif; ?>
	</footer>
	<?php endwhile; ?>
	<?php else : ?>
		<article id="post-not-found" class="hentry clearfix">
			<header class="article-header">
				<h1><?php _e('Oops, Post Not Found!', 'bonestheme'); ?></h1>
			</header>
			<section class="entry-content">
				<p><?php _e('Uh Oh. Something is missing. Try double checking things.', 'bonestheme'); ?></p>
			</section>
			<footer class="article-footer">
				<p><?php _e('This is the error message in the page.php template.', 'bonestheme'); ?></p>
			</footer>
		</article>
	<?php endif; ?>
</div>
</div>
<?php // all js scripts are loaded in library/bones.php ?>
<?php wp_footer(); ?>
</body>
</html>