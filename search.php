<?php
/*
Template Name: Search Results Page
*/
?>
<?php get_header(); ?>

<main role="main" class="boxsized main">
	<div class="section">
		<div class="centered-inner">
			<h2><?php printf(__('Search Results for: %s', 'bones'), get_search_query()); ?></h2>
		</div>
		<div class="centered-inner">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<article class="main-content" id="post-<?php the_ID(); ?>" role="article" itemtype="http://schema.org/BlogPosting">
						<header>
							<h2><?php the_title(); ?></h2>
							<p class="byline vcard"><?php
								printf(__('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>.', 'bonestheme'), get_the_time('Y-m-j'), get_the_time(__('F jS, Y', 'bonestheme')), bones_get_the_author_posts_link());
								?></p>
						</header>

						<section itemprop="articleBody">
							<?php the_content(); ?>
						</section>

						<footer class="article-footer">
							<?php the_tags('<span class="tags">' . __('Tags:', 'bonestheme') . '</span> ', ', ', ''); ?>

						</footer>
						
						<div class="separator">
							<span class="separator-line"></span>
							<span class="x-icon">x</span>
							<span class="separator-line"></span>
						</div>
					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
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
		</div>
	</div>
</main>

<?php get_footer(); ?>

