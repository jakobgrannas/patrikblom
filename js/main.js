// TODO: Reactor to module/prototype pattern
(function($) {
	var config = {
		wrapperEl: $('#wrapper'),
		menuBtn: $('#menu-btn'),
		menuEl: $('#main-nav'),
		menuClosedCls: 'menu-closed'
	};
	$(document).ready(function() {
		$('#menu-btn').on('click', toggleMenuVisibility);
		$('#wrapper').on('click', closeMenu);

		$('#scroll-top-btn').on('click', scrollToTop);
		
		$('.category-list-btn').live('click', toggleElementCollapsed);

		$(window).on('resize', hideMobileMenu);

		// Get initial photo set
		var photoFeed = $('#photo-feed');
		if (photoFeed) {
			getPhotos();
		}
		
		// Set up filter checkbox listener
		var sortCheckboxes = $('input[name="terms"]');
		if(sortCheckboxes) {
			$(document).on('change','input[name="terms"]', filterPhotos);
		}
	});
	
	function filterPhotos() {
		var filterTerms = $('input[name="terms"]:checked').map(function() {
			return $(this).val();
		}).get();
		
		getTerms(filterTerms, function (scope, content) {
			$('#photo-feed').html(content);
		});
	}
	
	function initMasonry (scope, content) {
		scope.html(content);
		new Masonry(scope[0], {
			itemSelector: '.image-block',
			isAnimated: !Modernizr.csstransitions,
			isFitWidth: true,
			columnWidth: 280
		});
	}
	
	function getPhotos() {
		// TODO: Check localStorage for previous settings
		var scope = $('#photo-feed');
		var prevSorters = localStorage.getItem('image-sort-settings');
		
		if(prevSorters) {
			getTerms(prevSorters, initMasonry, scope);
		}
		else {
			getTerms(null, initMasonry, scope); // TODO: Remove after testing
		}
	}
	
	function getTerms(terms, successHandler, scope) {
		// TODO: Add spinner
		//jQuery("#loading-animation").show();
		
		$.ajax({
			type: 'POST',
			url: pbAjax.ajaxurl,
			data: { 
				action: "load-filter2",
				taxonomy: 'phototype',
				postType: 'gallery',
				terms: terms && terms.length > 0 ? terms : ''
			},
			success: function(response) {
				//$("#loading-animation").hide();
				//localStorage.setItem('image-sort-settings', terms);
				if(typeof successHandler === 'function') {
					successHandler.call(this, scope, response);
				}
			}
		});
	}

	function toggleMenuVisibility(e) {
		if (e) {
			e.preventDefault();
			e.stopPropagation();
		}

		if (Modernizr.csstransitions) {
			$('.menu-push').toggleClass('menu-animate-left');
			config.menuEl.toggleClass(config.menuClosedCls);
		}
		else {
			toggleAnimateLeft(config.wrapperEl, function() {
				config.wrapperEl.toggleClass('menu-pushed-left');
			});
		}
	}

	function closeMenu(e) {
		if ($('.menu-push') && !config.menuEl.hasClass(config.menuClosedCls)) {
			toggleMenuVisibility();
		}
	}

	function hideMobileMenu() {
		var maxWidth = 55.5 * 16;
		if ($(window).width() >= maxWidth) {
			closeMenu();
		}
	}

	function toggleAnimateLeft(el, callback) {
		var position = el.hasClass('menu-pushed-left') ? 0 : '-15em',
				animateLeft = function(position) {
					el.stop().animate({left: position}, {
						duration: 350,
						complete: function() {
							callback();
						}
					});
				};

		animateLeft(position);
	}
	
	function toggleElementCollapsed (e) {
		e.preventDefault();
		e.stopPropagation();
		
		var container = $(e.target).closest('.image-block-footer');
		container.toggleClass('slide-down');
	}

	function scrollToTop(e) {
		e.preventDefault();
		e.stopPropagation();
		$('html,body').stop().animate({scrollTop: 0}, 'slow');
	}
}(jQuery));