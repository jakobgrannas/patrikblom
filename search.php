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
			<div class="main-content">
				<ul class="post-listing reset-box-model bare-list">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<li>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p class="post-body">
							<?php the_excerpt(); ?>
						</p>
						<div class="separator">
							<span class="separator-line"></span>
							<span class="x-icon">x</span>
							<span class="separator-line"></span>
						</div>
					</li>
				<?php endwhile; ?>
				</ul>
			</div>
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

