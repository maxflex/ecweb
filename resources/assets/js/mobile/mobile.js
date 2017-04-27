var isMobile = true
var isIphone4 = window.screen && (window.screen.height == (960 / 2));

$(document).ready(function() {
	$('.header-menu-button').click(function() {
        openModal('menu')
	})
    $('.price-list .price-list-item-title').click(togglePrice);
	// if (isMobile && isIphone4) $('body').addClass('iphone4fix');
})

function togglePrice(){
    var $parent = $(this).parent('.price-list-item');

    $parent.toggleClass('price-list-item__active');
    $parent.children('.price-list-inner').slideToggle();
}
