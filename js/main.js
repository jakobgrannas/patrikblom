// TODO: Reactor to module/prototype pattern
(function($) {
	var config = {
		wrapperEl: $('#wrapper'),
		menuBtn: $('#menu-btn'),
		menuEl: $('#main-nav'),
		menuClosedCls: 'menu-closed',
		lastScrollPos: 0,
		photoFeed: $('#photo-feed'),
		masonryEl: ''
	};
	$(document).ready(function() {
		// Load gravatars if not on mobile (hopefully)
		var responsive_viewport = $(window).width();
		if (responsive_viewport >= 768) {
			$('.comment img[data-gravatar]').each(function(){
				$(this).attr('src',$(this).attr('data-gravatar'));
			});
		}
		
		addEvtListeners();
	});
	
	function addEvtListeners () {
		/*
		 *  Menu listeners
		 */
		$('#menu-btn').on('click', toggleMenuVisibility);
		$('#wrapper').on('click', closeMenu);
		$(window).on('resize', hideMobileMenu);

		$('#scroll-top-btn').on('click', scrollToTop);
		
		/**
		 * Image loading listeners
		 */
		$(document).on('click', '#load-more', loadMorePhotos);
		
		// Get initial photo set
		var photoFeed = $('#photo-feed');
		if (photoFeed.length > 0) {
			loadMorePhotos();
		}
		
		/**
		 * Gallery filter listeners
		 */
		
		// Set up checkbox filterlistener
		var filterCheckboxes = $('input[name="terms"]');
		if(filterCheckboxes.length > 0) {
			$(document).on('change','input[name="terms"]', filterPhotos);
		}
		
		// Set up selectbox filter listener
		var filterSelectBox = $('.view-as option');
		if(filterSelectBox.length > 0) {
			$(document).on('change', '.view-as', filterPhotos);
		}
		
		/**
		 * Validation event listeners
		 * If browser has no native validation, use js validation
		 */
		if (!Modernizr.input.required) {
			$(document).on('change', '.input-field[type="email"]', validateEmail);
			$(document).on('change', '.input-field[required]', validateRequired);
		}
	}
	
	function validateEmail(e) {
		var el = $(e.target);
		var val = el.val();
		if (/[a-z0-9!#$%&'*+/=?^_`{|}~.-]+@[a-z0-9-]+(\.[a-z0-9-]+)*/.test(val)) {
			toggleClass(el, 'valid', 'invalid');
		}
		else {
			toggleClass(el, 'invalid', 'valid');
		}
	}
	
	function validateRequired(e) {
		var el = $(e.target);
		var val = el.val();
		
		if(el.attr('type') === 'email') {
			return false;
		}
		
		if(val && val.length > 0) {
			toggleClass(el, 'valid', 'invalid');
		}
		else {
			toggleClass(el, 'invalid', 'valid');
		}
	}
	
	function toggleClass(el, primCls, secCls) {
		if (el.hasClass(secCls)) {
			el.removeClass(secCls);
			el.addClass(primCls);
		}
		else {
			el.addClass(primCls);
		}
	}
	
	function filterPhotos() {
		var filterTerms = $('input[name="terms"]:checked').map(function() {
			return $(this).val();
		}).get();
		
		var filterOption = $('#view-type option:selected').val();
		    filterOption = parseInt(filterOption);
				
		var filters = {
			terms: filterTerms,
			includeChildren: filterOption !== NaN ? filterOption : 0
		};
		
		getTerms(filters, function (scope, content) {
			var feed = config.photoFeed
			feed.html(content);
			
			// Fix to make masonry properly calculate its height
			var children = $('.image-block');
			feed.imagesLoaded(function () {
				config.masonryEl.prepended(children);
				$('.image-block .preview-thumbnail').lazyload({
					effect: 'fadeIn'
				});
			});
		});
	}
	
	function initMasonry (callback) {
		var feed = config.photoFeed;
		feed.imagesLoaded(function() {
			config.masonryEl = new Masonry(feed[0], {
				itemSelector: '.image-block',
				isAnimated: !Modernizr.csstransitions,
				isFitWidth: true,
				containerStyle: null,
				columnWidth: 280
			});
			callback(config.masonryEl);
		});
	}
	
	function loadMorePhotos() {
		var offset = $('.image-block').length;
		getPhotos(offset, appendPhotos);
	}
	
	function appendPhotos(scope, content) {		
		var feed = config.photoFeed;
		var msnry = config.masonryEl;
		
		feed.imagesLoaded(function() {
			var elems = $(content);
			if(elems.length > 0) {
				feed.append(elems).masonry( 'appended', elems, true );
				if(msnry) {
					msnry.appended(elems);
				}
				else {
					initMasonry(function (scope) {
						scope.addItems(elems);						
					});
				}
				$('.image-block .preview-thumbnail').lazyload({
					effect: 'fadeIn'
				});
			}
		});
	}
	
	function getPhotos(offset, successHandler) {
		var scope = config.photoFeed;
		// TODO: Add localStorage handling - check checkboxes etc
		var prevSorters = localStorage.getItem('image-sort-settings');
		var data = {
			offset: offset || 0
		};
		var callback = appendPhotos;
		
		if(successHandler && typeof successHandler === 'function') {
			callback = successHandler;
		}
		
		if(prevSorters) {
			data.terms = prevSorters;
			getTerms(data, callback, scope);
		}
		else {
			data.terms = null;
			getTerms(data, callback, scope);
		}
	}
	
	function getTerms(filters,  successHandler, scope) {
		// TODO: Add spinner beforeSend?
		//jQuery("#loading-animation").show();
				
		var errorHandler = function () {
			$('#images-not-found').removeClass('hidden');
		};
		
		$.ajax({
			type: 'POST',
			url: pbAjax.ajaxurl,
			data: { 
				action: "load-filter2",
				offset: filters.offset || 0,
				taxonomy: 'phototype',
				postType: 'gallery',
				includeChildren: filters.includeChildren === 1 ? 1 : 0, // Use true if not explicitly set to false
				terms: filters.terms && filters.terms.length > 0 ? filters.terms : ''
			},
			success: function(response) {
				//$("#loading-animation").hide();
				//localStorage.setItem('image-sort-settings', terms);
				if(response && response.toString().length > 0) {
					if(typeof successHandler === 'function') {
						successHandler.call(this, scope, response);
					}
					else {
						config.photoFeed.html(response);
						$('.image-block .preview-thumbnail').lazyload({
							effect: 'fadeIn'
						});
					}
				}
				else {
					errorHandler();
				}
			},
			error: errorHandler
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