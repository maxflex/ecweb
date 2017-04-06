var scope = null
var player = null

$(document).ready(function() {
    //Custom select
    var $cs = $('.custom-select').customSelect();

    $('.questions-item-title').click(function() {
        $(this)
            .parent()
            .children('.questions-item-answer')
            .toggle();
    });

    $(document).on('keyup', function(event) {
        if (event.keyCode == 27) {
            closeModal()
        }
    })

    angular.element(document).ready(function() {
		setTimeout(function() {
			scope = angular.element('[ng-app=App]').scope()
		}, 50)
	})
})

function closeModal() {
    $('.modal').removeClass('active')
    $('body').removeClass('modal-open')
	$("body").addClass('open-modal-' + active_modal); active_modal = false
    $('.container').off('touchmove');
    if(window.location.hash == "#modal") {
        window.history.back()
    }
    if (typeof(onCloseModal) == 'function') {
        onCloseModal()
    }
}

function openModal(id) {
    $(".modal#modal-" + id).addClass('active')
    $('#menu-overlay').height('95%').scrollTop(); // iphone5-safari fix
    $("body").addClass('modal-open open-modal-' + id); active_modal = id
    $('.container').on('touchmove', function(e){e.preventDefault();});
    window.location.hash = '#modal'
    if (typeof(onOpenModal) == 'function') {
        onOpenModal()
    }
}

// Автовоспроизведение видео с открытием модального окна
function initYoutube() {
    window.onYouTubeIframeAPIReady = function() {
        player = new YT.Player('youtube-video', {})
    }

    window.onCloseModal = function() {
        player.stopVideo()
    }

    window.onOpenModal = function() {
        setTimeout(function() {
            player.playVideo()
        }, 500)
    }
}
