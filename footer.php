			<footer class="boxsized footer">
				<a href="#" class="scroll-top-btn" id="scroll-top-btn" title="Scroll to top"><span class="fa"></span></a>
				<div class="vcard top-row">
					<div class="footer-block copyright">
						<p>&copy; <?php echo date('Y'); ?></p>
						<p class="url fn"><?php bloginfo( 'name' ); ?></p>
					</div>
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : ?>
					<?php endif; ?>
				</div>
				<div class="bottom-row">
					<h2 class="logo">
						<a href="<?php echo home_url(); ?>" class="bare-link" rel="nofollow">
							<span class="row1">Patrik</span>
							<span class="row2">Blom</span>
						</a>
					</h2>
				</div>
			</footer>
		</div>
		
		<?php // all js scripts are loaded in library/bones.php ?>
		<?php wp_footer(); ?>
	</body>
</html>
