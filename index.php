<?php
/*
Template Name: Index Page
*/
?>

<?php get_header(); ?>

<main role="main" class="boxsized main">
				<div class="section">
					<div class="centered-inner">
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						<?php
							$gallery_IDs = array(); 
							if ( get_post_gallery() ) {
								$gallery = get_post_gallery( get_the_ID(), false );
								$gallery_IDs = $gallery['ids'];
							}
						?>
							<article class="main-content" id="post-<?php the_ID(); ?>" role="article">
								<?php if (has_post_thumbnail()) : ?>
									<?php the_post_thumbnail('pb-featured-image', array('class' => 'featured-image')); ?>
								<?php endif; ?>
								
								<header class="post-header">
									<h1 class="post-title"><?php the_title(); ?></h1>
								</header>

								<section>
									<?php echo strip_shortcodes(get_the_content()); ?>
								</section>
							</article>
						<?php endwhile; ?>
						<?php else : ?>
							<article id="post-not-found" class="hentry clearfix">
								<header class="article-header">
									<h1 class="post-title"><?php _e('Oops, Post Not Found!', 'bonestheme'); ?></h1>
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
				</div>
	
				<div class="section">
					<div class="separator">
						<span class="separator-line"></span>
						<span class="x-icon">x</span>
						<span class="separator-line"></span>
					</div>
				</div>
				<?php 
					if(isset($gallery_IDs) && !empty($gallery_IDs)) {
						echo do_shortcode('[pb_gallery image_ids=' . $gallery_IDs . ']');
					}
				?>
			</main>
<?php get_footer(); ?>