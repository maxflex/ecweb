var isMobile = true
var isIphone4 = window.screen && (window.screen.height == (960 / 2));

$(document).ready(function() {
	$('.header-menu-button').click(function() {
        openModal('menu')
	})
	// if (isMobile && isIphone4) $('body').addClass('iphone4fix');
})

//# sourceMappingURL=scripts.js.map
