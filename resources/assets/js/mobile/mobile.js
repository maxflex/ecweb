var isMobile = true
var isIphone4 = window.screen && (window.screen.height == (960 / 2));

$(document).ready(function() {
    if (isIphone4) {
        $('body').addClass('iphone4');
    }

	$('.header-menu-button').click(function() {
        openModal('menu')
	})
    $('.price-list .price-list-item-title').click(togglePrice);
    $('.catalog-list .catalog-list-item-title').click(toggleCatalog);
	// if (isMobile && isIphone4) $('body').addClass('iphone4fix');

    $('.questions-item-answer img.can-resize').click(function() {
        elem = $('#modal-faq-img img');
        elem.attr('src', $(this).attr('src'));
        margin_top = (elem.parent().actual('height') - elem.actual('height')) / 2;
        elem.css({marginTop: margin_top + 'px'});
        elem.panzoom('reset');
        elem.panzoom('destroy');
        console.log('reset');

        (function(target) {
            setTimeout(function() {
                target.panzoom({
                    minScale: 1,
                    maxScale: 5,
                    increment: 1.2,
                    contain: 'automatic',
                    panOnlyWhenZoomed: false
                });
            }, 500);
        })(elem);

        openModal('faq-img');
    });
})

//
// close modal on «back» button
// 
$(window).on('hashchange', function() {
    if(window.location.hash != "#modal") {
        closeModal()
    }
});

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