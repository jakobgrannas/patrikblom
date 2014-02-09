<?php get_header(); ?>
<main role="main" class="boxsized main">	
	<header class="post-header">
		<h2><?php _e( 'Photo Category:', 'bonestheme' ); ?> <?php single_cat_title(); ?></h2>
	</header>

	<div class="section">
		<div class="centered-inner photo-feed js-masonry flexed" id="photo-feed">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="image-block" id="post-<?php the_ID(); ?>">
						<?php if (has_post_thumbnail()) : ?>
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(256, 256), array('class' => 'preview-thumbnail')); ?></a>
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
								<div class="footer-item post-category"><span class="fa"></span><a href="#tags-list-extended"><?php echo $num_terms . ' ' . __('tags', 'patrikblom'); ?></a></div>
								<div class="footer-item post-category-extended hidden" id="tags-list-extended"><?php echo get_the_term_list($post->ID, $taxonomy, '', ', '); ?></div>
							<?php endif; ?>
						</div>
					</div>
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
						<p><?php _e('This is the error message in the index.php template.', 'bonestheme'); ?></p>
					</footer>
				</article>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>
		<div class="button-row">
			<button class="btn btn-default btn-big">Hämta mer</button>
		</div>
	</div>
</main>

<?php get_footer(); ?>



