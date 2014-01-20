<footer class="boxsized footer">
				<a href="#" class="scroll-top-btn" id="scroll-top-btn" title="Scroll to top"><span class="fa"></span></a>
				<div class="vcard top-row">
					<div class="footer-block copyright">
						<p>&copy; <?php echo date('Y'); ?></p>

						<p class="url fn"><?php bloginfo( 'name' ); ?></p>
					</div>
					<div class="footer-block communication">
						<a class="tel" href="tel:+46702647799" title="Phonenumber"><span class="fa"></span>070 264 77 99</a>

						<a class="email" href="mailto:patrik@patrikblom.com" title="Email address"><span class="fa"></span>patrik@patrikblom.com</a>
					</div>
					<div class="footer-block address">
						<p class="street-address">Someplace BLV. 32</p>
						<p class="postal-code">123 45</p>
						<p class="region">Someplace, Sweden</p>
					</div>
					<div class="footer-block social-media-icons">
						<a href="#" class="facebook-icon"><span title="Facebook" class="fa"></span></a>
						<a href="#" class="linkedin-icon"><span title="LinkedIn" class="fa"></span></a>
						<a href="#" class="flickr-icon"><span title="Flickr" class="fa"></span></a>
					</div>
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
