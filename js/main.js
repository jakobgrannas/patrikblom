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
	$(window).on('scroll', toggleScrollTopBtnVisibility);
});

function setBackstretchConfig () {
	$('#banner').backstretch('images/banner.jpg', {
		centeredY: false
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
		toggleAnimateLeft(config.wrapperEl, function () {
			config.wrapperEl.toggleClass('menu-pushed-left');
		});
	}
}

function closeMenu (e) {
	if(!$('.menu-push')) {
		return false;
	}

	if(!config.menuEl.hasClass(config.menuClosedCls)) {
		toggleMenuVisibility();
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

function toggleScrollTopBtnVisibility (e) {
	var topPosition = $(window).scrollTop(),
		headerPosition = $('#banner').height(),
	    button = $('#scroll-top-btn');

	if(topPosition >= headerPosition) {
		button.fadeIn(800);
	}
	else if(button.is(':visible')) {
		button.fadeOut(800);
	}
}