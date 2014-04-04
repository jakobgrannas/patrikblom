<?php get_header(); ?>
<div id="wrapper" class="single-image-post">
	<nav id="single-image-nav" class="single-image-nav">
		<?php
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'gallery.php'
		));
		if(count($pages) === 1) {
			$permalink = get_permalink($pages[0]->ID);
		}
		?>
		<a href="<?php echo !empty($permalink) ? $permalink : '#' ?>" id="back-button" class="back-button"><span class="fa chevron-left"></span></a>
	</nav>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<section class="image-meta" id="post-<?php the_ID(); ?>">
				<?php if ( has_post_thumbnail() ) : ?>
					<a title="<?php the_title_attribute( "echo=1" ); ?>" rel="bookmark" class="full-width-image">
					<?php if (class_exists( 'RIP' )){
						echo RIP::get_picture(get_the_post_thumbnail(get_the_ID(), 'full'));
					} else {
						the_post_thumbnail('full');
					}?>
					</a>
				<?php endif; ?>
		</section>
		<aside id="main-content" class="image-content">
			<section class="section content">
				<div class="inner">
					<header class="post-header">
						<h1 class="post-title"><?php the_title(); ?></h1>
					</header>
					<?php the_content(); ?>
				</div>
			</section>
			<section class="section comments">
				<div class="inner">
					<?php comments_template(); ?>
				</div>
			</section>
		</aside>
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
<?php get_footer(); ?>
</body>
</html>