$(document).ready(function () {
	setBackstretchConfig();

	if(!Modernizr.csstransitions) {
		$('body').removeClass('menu-push');
		$('#main-nav').removeClass('menu-animate');
		$('#menu-btn').sidr({
			name: 'main-nav',
			side: 'right'
		});
	}
	else {
		$('#menu-btn').on('click', toggleMenuVisibility);
	}

	$('#wrapper').on('click', closeMenu);

	$(window).on('scroll', toggleScrollTopBtnVisibility);
	$('#scroll-top-btn').on('click', scrollToTop);
});

function setBackstretchConfig () {
	$('#banner').backstretch('images/banner.jpg', {
		centeredY: false
	});
}

function toggleMenuVisibility(e) {
	if (e) {
		e.stopPropagation();
	}

	$('body').toggleClass('menu-push-toleft');
	$('#main-nav').toggleClass('menu-open');
}

function closeMenu (e) {
	var mainNav = $('#main-nav');

	if(mainNav.hasClass('menu-open')) {
		toggleMenuVisibility();
	}
}

function scrollToTop (e) {
	e.preventDefault();
	$('html,body').animate({scrollTop: 0},'slow');
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