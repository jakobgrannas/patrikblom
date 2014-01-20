<!DOCTYPE html>
	<!--[if IE 8]>
	<html <?php language_attributes(); ?> class="no-js lt-ie9"> <![endif]-->
	<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		
		<?php // Google Chrome Frame for IE ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title><?php echo wp_title(); ?></title>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-icon-touch.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

		<?php // drop Google Analytics Here ?>
		<script>
			var _gaq = [
				['_setAccount', 'UA-XXXXX-X'],
				['_trackPageview']
			];
			(function (d, t) {
				var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
				g.src = '//www.google-analytics.com/ga.js';
				s.parentNode.insertBefore(g, s)
			}(document, 'script'));
		</script>
		<?php // end analytics ?>
	</head>
	<body>
		<!--[if lt IE 9]>
		<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
			your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to
			improve your experience.</p>
		<![endif]-->

		<nav class="boxsized main-menu animate menu-right menu-closed" id="main-nav">
			<div class="boxsized search-field-container">
				<input type="text" id="search" class="search-field" name="search" placeholder="search...">
				<button class="btn search-btn"><span class="fa search-icon"></span></button>
			</div>
			<?php
			$defaults = array(
				'menu' => 'Main nav',
				'menu_class' => 'boxsized menu-items menu',
				'echo' => true,
				'fallback_cb' => 'wp_page_menu',
				'items_wrap' => '%3$s',
				'depth' => 0
			);

			wp_nav_menu($defaults);
			?>
		</nav>

		<div id="wrapper" class="wrapper animate menu-push">

			<header id="banner" class="banner animate">
				<div class="banner-img"></div>
				<div class="banner-inner-container">
					<div class="inner">
						<a href="#main-nav" id="menu-btn" class="nav-btn menu-btn open-menu-btn"><span class="fa menu-icon"></span></a>
						<a href="#" class="nav-btn menu-btn close-menu-btn"><span class="fa menu-icon"></span></a>

						<div class="logo-container">
							<h1 class="logo">
								<a href="<?php echo home_url(); ?>" class="bare-link" rel="nofollow">
									<span class="row1">Patrik</span>
									<span class="row2">Blom</span>
								</a>
							</h1>
						</div>

						<div class="boxsized search-field-container">
							<input type="text" id="search2" class="search-field" name="search" placeholder="search...">
							<button class="btn search-btn"><span class="fa search-icon"></span></button>
						</div>
					</div>
				</div>
			</header>	