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
				<label for="view-type"><?php _e('View as', 'patrikblom'); ?>:</label>

				<div class="select-container">
					<select id="view-type" class="view-as">
						<option>Album</option>
						<option>Alla bilder</option>
					</select>
					<span class="fa drop-down-trigger"></span>
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
					<input id="term-<?php echo $term->term_id; ?>-checkbox" type="checkbox" name="terms" class="visibly-hidden" value="<?php echo $term->name; ?>"/>
					<label for="term-<?php echo $term->term_id; ?>-checkbox" class="checkbox-button"><?php echo $term->name; ?></label>
					<?php endforeach; ?>
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
			<article id="post-not-found" class="hentry clearfix">
				<header class="article-header">
					<h1><?php _e('Oops, Post(s) Not Found!', 'bonestheme'); ?></h1>
				</header>
				<section class="entry-content">
					<p><?php _e('Uh Oh. Something is missing. Try double checking things.', 'bonestheme'); ?></p>
				</section>
				<footer class="article-footer">
					<p><?php _e('This is the error message in the gallery.php template.', 'bonestheme'); ?></p>
				</footer>
			</article>
		</div>
		<div class="button-row">
			<button class="btn btn-default btn-big">Hämta mer</button>
		</div>
	</div>
</main>

<?php get_footer(); ?>

