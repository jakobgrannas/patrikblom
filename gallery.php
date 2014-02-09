<?php
/*
  Template Name: Gallery
 */
?>
<?php get_header(); ?>

<aside class="flexed boxsized sorter-bar">
	<form class="boxsized form-container">
		<div class="boxsized form-inner">
			<fieldset class="sort-by-type">
				<label for="view-type">View as:</label>

				<div class="select-container">
					<select id="view-type" class="view-as">
						<option>Album</option>
						<option>Alla bilder</option>
					</select>
					<span class="fa drop-down-trigger"></span>
				</div>
			</fieldset>

			<fieldset class="sort-by-category">
				<legend>Kategori:</legend>
				<div class="checkbox-group">
					<input id="clothes-checkbox" type="checkbox" name="category" class="visibly-hidden"
						   value="clothes"/>
					<label for="clothes-checkbox" class="checkbox-button">Kläder</label>
					<input id="bikes-checkbox" type="checkbox" name="category" class="visibly-hidden"
						   value="clothes"/>
					<label for="bikes-checkbox" class="checkbox-button">Motorcyklar</label>
					<input id="sports-checkbox" type="checkbox" name="category" class="visibly-hidden"
						   value="clothes"/>
					<label for="sports-checkbox" class="checkbox-button">Sport</label>
					<input id="home-decor-checkbox" type="checkbox" name="category" class="visibly-hidden"
						   value="clothes"/>
					<label for="home-decor-checkbox" class="checkbox-button">Inredning</label>
					<input id="other-checkbox" type="checkbox" name="category" class="visibly-hidden"
						   value="clothes"/>
					<label for="other-checkbox" class="checkbox-button">Annat</label>
				</div>
			</fieldset>
		</div>
	</form>
</aside>

<main role="main" class="boxsized main">
	<div class="section descriptive-text">
		<div class="centered-inner">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<article class="main-content" id="post-<?php the_ID(); ?>" role="article" itemscope itemtype="http://schema.org/BlogPosting">
						<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail(array(250, 250), array('class' => 'featured-image')); ?>
						<?php endif; ?>

						<header class="post-header">
							<h2><?php the_title(); ?></h2>
						</header>

						<section itemprop="articleBody">
							<?php the_content(); ?>
						</section>
					</article>
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
						<p><?php _e('This is the error message in the gallery.php template.', 'bonestheme'); ?></p>
					</footer>
				</article>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>

	<div class="section">
		<div class="separator">
			<span class="separator-line"></span>
			<span class="x-icon">x</span>
			<span class="separator-line"></span>
		</div>
	</div>

	<div class="section">
		<div class="centered-inner photo-feed js-masonry flexed" id="photo-feed">
			<?php
			$post_type = 'gallery';
			$taxonomy = 'phototype';
			$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'caller_get_posts' => 1
			);

			$image_query = new WP_Query($args);
			?>
			<?php if ($image_query->have_posts()) : while ($image_query->have_posts()) : $image_query->the_post(); ?>
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

