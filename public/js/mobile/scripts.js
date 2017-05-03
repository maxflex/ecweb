var isMobile = true
var isIphone4 = window.screen && (window.screen.height == (960 / 2));

$(document).ready(function() {
	$('.header-menu-button').click(function() {
        openModal('menu')
	})
    $('.price-list .price-list-item-title').click(togglePrice);
    $('.catalog-list .catalog-list-item-title').click(toggleCatalog);
	// if (isMobile && isIphone4) $('body').addClass('iphone4fix');
})

function togglePrice(){
    var $parent = $(this).closest('.price-list-item');

    $parent.toggleClass('price-list-item__active');
    $parent.children('.price-list-inner').slideToggle();
}

function toggleCatalog(){
    var $parent = $(this).parent('.catalog-list-item');

    $parent.toggleClass('catalog-list-item__active');
    $parent.children('.catalog-list-inner').slideToggle();
}
//# sourceMappingURL=scripts.js.map
