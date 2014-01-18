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
							<article class="main-content" id="post-<?php the_ID(); ?>" role="article">
								<?php if (has_post_thumbnail()) : ?>
											<?php the_post_thumbnail(array(250,250), array('class' => 'profile-pic')); ?>
								<?php endif; ?>
								

								<header>
									<h2><?php the_title(); ?></h2>
								</header>

								<section>
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
									<p><?php _e('This is the error message in the index.php template.', 'bonestheme'); ?></p>
								</footer>
							</article>
						<?php endif; ?>
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
					<div class="centered-inner gallery-section">
						<a href="#" class="image-block">
							<span class="preview-overlay"><span class="fa search-plus-icon"></span></span>
							<img src="<?php echo get_template_directory_uri(); ?>/images/patrik.jpg" width="100%" height="100%" class="preview-thumbnail">
						</a>
						<a href="#" class="image-block">
							<span class="preview-overlay"><span class="fa search-plus-icon"></span></span>
							<img src="<?php echo get_template_directory_uri(); ?>/images/patrik.jpg" width="100%" height="100%" class="preview-thumbnail">
						</a>
						<a href="#" class="image-block">
							<span class="preview-overlay"><span class="fa search-plus-icon"></span></span>
							<img src="<?php echo get_template_directory_uri(); ?>/images/patrik.jpg" width="100%" height="100%" class="preview-thumbnail">
						</a>
						<a href="#" class="image-block">
							<span class="preview-overlay"><span class="fa search-plus-icon"></span></span>
							<img src="<?php echo get_template_directory_uri(); ?>/images/patrik.jpg" width="100%" height="100%" class="preview-thumbnail">
						</a>
						<a href="#" class="image-block">
							<span class="preview-overlay"><span class="fa search-plus-icon"></span></span>
							<img src="<?php echo get_template_directory_uri(); ?>/images/patrik.jpg" width="100%" height="100%" class="preview-thumbnail">
						</a>
						<a href="#" class="image-block">
							<span class="preview-overlay"><span class="fa search-plus-icon"></span></span>
							<img src="<?php echo get_template_directory_uri(); ?>/images/patrik.jpg" width="100%" height="100%" class="preview-thumbnail">
						</a>
						<div class="button-row">
							<button class="btn btn-default btn-big">Hela galleriet</button>
						</div>
					</div>
				</div>
			</main>

<?php get_footer(); ?>
