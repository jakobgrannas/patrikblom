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
								$gallery_IDs = array_map('intval', explode(',', $gallery['ids']));

								echo $gallery_IDs;
							}
						?>
							<article class="main-content" id="post-<?php the_ID(); ?>" role="article">
								<?php if (has_post_thumbnail()) : ?>
											<?php the_post_thumbnail(array(250,250), array('class' => 'featured-image')); ?>
								<?php endif; ?>
								
								<header class="post-header">
									<h2><?php the_title(); ?></h2>
								</header>

								<section>
									<?php echo strip_shortcodes(get_the_content()); ?>
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
					if(count($gallery_IDs) > 0) :
						
						$attachments = get_posts(array(
							'post_type' => 'attachment'
						));
						//$att_query = "SELECT post_parent FROM posts WHERE ID = " . ;
						//$post_parents = $wpdb->get_results($att_query);
						//echo '<pre>'; print_r($attachments); echo '</pre>';
						$parents = array();
								
						foreach($attachments as $attachment) : setup_postdata($attachment);
							array_push($parents, $attachment->post_parent);
						endforeach;
								
						//print_r($parents);
								
								
						wp_reset_postdata();
						$gq = new WP_Query(array(
							'post_per_page' => -1,
							'post_status' => 'publish',
							'post_type' => 'any', // TODO: Exclude index page
							'ignore_sticky_posts' => 1,
							'post__in' => $parents
						));								
				?>
					<div class="section">
						<div class="centered-inner gallery-section">

							<?php if ($gq->have_posts()) : while ($gq->have_posts()) : $gq->the_post(); ?>
								<a href="<?php the_permalink(); ?>" class="image-block">
									<span class="preview-overlay"><span class="fa search-plus-icon"></span></span>
									<?php the_post_thumbnail(array(180, 180), array('class' => 'preview-thumbnail')); ?>
								</a>
							<?php endwhile; ?>
							<div class="button-row">
								<a href="<?php echo home_url('/gallery'); ?>" class="btn btn-default btn-big"><?php _e('Hela galleriet', 'patrikblom'); ?></a>
							</div>
							<?php endif; ?>
							<?php wp_reset_postdata(); ?>
							
						</div>
					</div>
				<?php endif; ?>
			</main>
<?php get_footer(); ?>