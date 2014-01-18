var config = {
	wrapperEl: $('#wrapper'),
	menuBtn: $('#menu-btn'),
	menuEl: $('#main-nav'),
	menuClosedCls: 'menu-closed'
};

// TODO: Reactor to module/prototype pattern

$(document).ready(function () {
	$('#menu-btn').on('click', toggleMenuVisibility);
	$('#wrapper').on('click', closeMenu);

	$('#scroll-top-btn').on('click', scrollToTop);

	$(window).on('resize', hideMobileMenu);

	var photoFeed = document.querySelector('#photo-feed');
	if(photoFeed) {
		new Masonry(photoFeed, {
			itemSelector: '.image-block',
			isAnimated: !Modernizr.csstransitions,
			isFitWidth: true,
			columnWidth: 280
		});
	}
});

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
		toggleAnimateLeft(config.wrapperEl, function () {
			config.wrapperEl.toggleClass('menu-pushed-left');
		});
	}
}

function closeMenu (e) {
	if($('.menu-push') && !config.menuEl.hasClass(config.menuClosedCls)) {
		toggleMenuVisibility();
	}
}

function hideMobileMenu () {
	var maxWidth = 55.5 * 16;
	if($(window).width() >= maxWidth) {
		closeMenu();
	}
}

function toggleAnimateLeft (el, callback) {
	var position = el.hasClass('menu-pushed-left') ? 0 : '-15em',
	    animateLeft = function (position) {
		    el.stop().animate({left: position}, {
			    duration: 350,
			    complete: function () {
				    callback();
			    }
		    });
	    };

	animateLeft(position);
}

function scrollToTop (e) {
	e.preventDefault();
	e.stopPropagation();
	$('html,body').stop().animate({scrollTop: 0},'slow');
}