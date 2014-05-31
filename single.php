<?php get_header(); ?>
<?php pb_get_header(); ?>

<main role="main" class="boxsized main">
	<section class="section">
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

						<footer class="article-footer">
							<?php the_tags('<span class="tags">' . __('Tags:', 'patrikblom') . '</span> ', ', ', ''); ?>
						</footer>
						
						<div class="separator">
							<span class="separator-line"></span>
							<span class="x-icon">x</span>
							<span class="separator-line"></span>
						</div>

						<?php comments_template(); ?>
					</article>
				<?php endwhile; ?>
			<?php else : ?>
				<article id="post-not-found" class="hentry clearfix">
					<header class="article-header">
						<h1 class="title"><?php _e('Oops, Post Not Found!', 'patrikblom'); ?></h1>
					</header>
					<section class="entry-content">
						<p><?php _e('Uh Oh. Something is missing. Try double checking things.', 'patrikblom'); ?></p>
					</section>
					<footer class="article-footer">
						<p><?php _e('This is the error message in the page.php template.', 'patrikblom'); ?></p>
					</footer>
				</article>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>

