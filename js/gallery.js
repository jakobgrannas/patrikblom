/**
 * Gallery scripts
 * Handles image loading and masonry config
 * 
 * @param {object} config Configuration object
 * @param {element} config.masonryEl Masonry config element
 * @param {element} config.previewImage Element reference for images in the gallery
 * 
 * @returns {Gallery.me|Gallery}
 */
var Gallery = function (config) {	
	var me = config ? _.extend(this, config) : this;

	function registerListeners () {
		/**
		 * Image loading listeners
		 */
		$(document).on('load', '.full-width-image img', me.loadMorePhotos);
		
		// Get initial photo set
		if (me.photoFeed.length > 0) {
			me.loadMorePhotos();
		}
		
		if (me.previewImage.length > 0) {
			me.previewImage.lazyload({
				effect: 'fadeIn'
			});
		}
	}
	
	me.onSingleGalleryPhotoLoad = function (e) {
		var elem = $(e.target);
		var src = elem.attr('src');
		var newEl = document.createElement('div');
		newEl.style = 'background-image:' +  src;
		elem.appendChild(newEl);
	};
	
	me.filterPhotos = function () {
		var filterTerms = $('input[name="terms"]:checked').map(function() {
			return $(this).val();
		}).get();
		
		var filterOption = $('input[name="view-as"]:checked').val();
							
		var filters = {
			terms: filterTerms,
			includeChildren: filterOption.toLowerCase() === 'all' || !filterOption
		};
		
		getTerms(filters, function (scope, content) {
			var feed = me.photoFeed;
			feed.html(content);
			
			// Fix to make masonry properly calculate its height
			var children = $('.image-block');
			feed.imagesLoaded(function () {
				me.masonryEl.prepended(children);
				$('.preview-thumbnail').lazyload({
					effect: 'fadeIn'
				});
			});
		});
	};
	
	me.initMasonry = function (callback) {
		var feed = me.photoFeed;
		feed.imagesLoaded(function() {
			me.masonryEl = new Masonry(feed[0], {
				itemSelector: '.image-block',
				isAnimated: !Modernizr.csstransitions,
				isFitWidth: true,
				containerStyle: null,
				columnWidth: 280
			});
			callback(me.masonryEl);
		});
	};
	
	me.loadMorePhotos = function () {
		var offset = $('.image-block').length;
		getPhotos(offset, appendPhotos);
	};
	
	function appendPhotos(scope, content) {		
		var feed = me.photoFeed;
		var msnry = me.masonryEl;
		
		feed.imagesLoaded(function() {
			var elems = $(content);
			if(elems.length > 0) {
				feed.append(elems).masonry( 'appended', elems, true );
				if(msnry) {
					msnry.appended(elems);
				}
				else {
					me.initMasonry(function (scope) {
						scope.addItems(elems);						
					});
				}
				$('.preview-thumbnail').lazyload({
					effect: 'fadeIn'
				});
			}
		});
	}
	
	function getPhotos(offset, successHandler) {
		var scope = me.photoFeed;
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
		$("#spinner").toggleClass('hidden');
				
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
				includeChildren: filters.includeChildren === true ? 1 : 0, // Use true if not explicitly set to false
				terms: filters.terms && filters.terms.length > 0 ? filters.terms : ''
			},
			success: function(response) {
				$("#spinner").toggleClass('hidden');
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
	
	registerListeners();
	
	return me;
};