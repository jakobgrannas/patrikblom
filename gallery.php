<?php
/*
  Template Name: Gallery
 */
?>
<?php get_header(); ?>
<?php pb_get_header(); ?>
<main role="main" class="boxsized main">
	<section class="section descriptive-text">
		<div class="centered-inner">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<article class="main-content" id="post-<?php the_ID(); ?>" role="article" itemscope itemtype="http://schema.org/BlogPosting">
						<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail(array(250, 250), array('class' => 'featured-image')); ?>
						<?php endif; ?>

						<header class="post-header">
							<h1 class="post-title"><?php the_title(); ?></h1>
						</header>

						<section itemprop="articleBody">
							<?php the_content(); ?>
						</section>
					</article>
				<?php endwhile; ?>
			<?php else : ?>
				<article id="post-not-found" class="hentry clearfix">
					<header class="article-header">
						<h1 class="title"><?php _e('Oops, Post Not Found!', 'bonestheme'); ?></h1>
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
	</section>

	<section class="section">
		<div class="separator"> 
			<span class="separator-line"></span>
			<span class="x-icon">x</span>
			<span class="separator-line"></span>
		</div>
	</section>
	
	<aside class="flexed boxsized sorter-bar">
		<form class="boxsized form-container">
			<div class="boxsized form-inner">
				<fieldset class="sort-by-type">
					<label for="view-type"><?php _e('View', 'patrikblom'); ?>:</label>

					<div class="checkbox-group">
						<?php
							$options = array(
								'all' => array(
									'label' => __('All', 'patrikblom'),
									'isChecked' => false,
								),
								'album' => array(
									'label' => __('Album', 'patrikblom'),
									'isChecked' => true,
								)
							);
						?>
						<?php foreach ($options as $key => $option) : ?>
							<input id="view-option-<?php echo $key; ?>" type="radio" name="view-as" class="radio toggler visibly-hidden value-area" value="<?php echo $key; ?>" <?php checked($option['isChecked']); ?>/>
							<label for="view-option-<?php echo $key; ?>" class="toggle-button"><?php echo $option['label']; ?></label>
						<?php endforeach; ?>
					</div>
				</fieldset>

				<fieldset class="sort-by-category">
					<legend><?php _e('Category', 'patrikblom'); ?>:</legend>
					<div class="checkbox-group">
						<?php
							$sort_args = array(
								'orderby' => 'name',
								'parent' => 0
							);
							$sort_terms = get_terms('phototype', $sort_args);
						?>
						<?php foreach ($sort_terms as $term ) : ?>
						<input id="term-<?php echo $term->term_id; ?>-checkbox" type="checkbox" name="terms" class="checkbox visibly-hidden value-area" value="<?php echo $term->name; ?>"/>
						<label for="term-<?php echo $term->term_id; ?>-checkbox" class="toggle-button"><?php echo $term->name; ?></label>
						<?php endforeach; ?>
					</div>
				</fieldset>
			</div>
		</form>
	</aside>

	<section class="section">
		<div class="full-inner photo-feed js-masonry flexed" id="photo-feed"></div>
	</section>
</main>

<?php get_footer(); ?>

