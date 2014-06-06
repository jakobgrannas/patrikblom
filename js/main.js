(function($) {
	var CONFIG;
	
	$(document).ready(function() {
		// Load gravatars if not on mobile (hopefully)
		var responsive_viewport = $(window).width();
		if (responsive_viewport >= 768) {
			$('.comment img[data-gravatar]').each(function(){
				$(this).attr('src',$(this).attr('data-gravatar'));
			});
		}
		
		CONFIG = {
			gallery: new Gallery({
				photoFeed: $('#photo-feed'),
				previewImage: $('.preview-thumbnail')
			})
		};
		
		new Menu();
				
		addEvtListeners();
	});
	
	function addEvtListeners () {
		$('#scroll-top-btn').on('click', scrollToTop);
		
		$(document).on('click', '.category-list-btn', toggleElementCollapsed);
		
		/**
		 * Gallery filter listeners
		 */
		// Set up checkbox filterlistener
		var filterCheckboxes = $('input[name="terms"]');
		if(filterCheckboxes.length > 0) {
			$(document).on('change','input[name="terms"]', filterPhotos);
		}
		
		// Set up selectbox filter listener
		var filterSelectBox = $('input[name="view-as"]');
		if(filterSelectBox.length > 0) {
			$(document).on('change', 'input[name="view-as"]', filterPhotos);
		}
		
		$(document).on('click', '.post-category a', filterSingleTermPhotos);
						
		/**
		 * Validation event listeners
		 * If browser has no native validation, use js validation
		 */
		if (!Modernizr.input.required) {
			$(document).on('change', '.input-field[type="email"]', Validation.validateEmail);
			$(document).on('change', '.input-field[required]', Validation.validateRequired);
		}
	}
	
	function filterSingleTermPhotos (e) {
		e.preventDefault();
				
		var filters = {
			terms: [e.target.textContent],
			includeChildren: true
		};
		
		CONFIG.gallery.filterPhotos(filters);
	}
	
	function filterPhotos () {
		var filterTerms = $('input[name="terms"]:checked').map(function() {
			return $(this).val();
		}).get();
		
		var filterOption = $('input[name="view-as"]:checked').val();
							
		var filters = {
			terms: filterTerms,
			includeChildren: filterOption.toLowerCase() === 'all' || !filterOption
		};
		
		CONFIG.gallery.filterPhotos(filters);
	}
	
	function toggleElementCollapsed (e) {
		e.preventDefault();
		e.stopPropagation();
		
		var container = $(e.target).closest('.image-footer');
		container.toggleClass('slide-down');
	}

	function scrollToTop(e) {
		e.preventDefault();
		e.stopPropagation();
		$('html,body').stop().animate({scrollTop: 0}, 'slow');
	}
})(jQuery);