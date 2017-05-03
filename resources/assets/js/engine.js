var scope = null
var player = null
var isMobile = false

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

    // $('body').on('click', function(event) {
    //     if ($(this).hasClass('modal-open')) {
    //         closeModal()
    //     }
    // })

    angular.element(document).ready(function() {
		setTimeout(function() {
			scope = angular.element('[ng-app=App]').scope()
		}, 50)
	})
})

function closeModal() {
    $('.modal').removeClass('active')
    $('body').removeClass()
	// $("body").addClass('open-modal-' + active_modal); active_modal = false
    $('.container').off('touchmove');
    // @todo: почему-то эта строчка ломает повторное воспроизведение видео видео
    if(window.location.hash == "#modal") {
        window.history.pushState('', document.title, window.location.pathname);
    }
    if (typeof(onCloseModal) == 'function') {
        onCloseModal()
    }
}

function openModal(id) {
    $(".modal#modal-" + id).addClass('active')
    $('#menu-overlay').height('95%').scrollTop(); // iphone5-safari fix
    $("body").addClass('modal-open open-modal-' + id);
    // active_modal = id
    $('.container').on('touchmove', function(e){e.preventDefault();});
    window.location.hash = '#modal'
    if (typeof(onOpenModal) == 'function') {
        onOpenModal()
    }
}

// close modal on «back» button
$(window).on('hashchange', function() {
    if(window.location.hash != "#modal") {
        closeModal()
    }
});

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

/**
 * Биндит аргументы контроллера ангуляра в $scope
 */
function bindArguments(scope, arguments) {
	function_arguments = getArguments(arguments.callee)

	for (i = 1; i < arguments.length; i++) {
		function_name = function_arguments[i]
		if (function_name[0] === '$') {
			continue
		}
		scope[function_name] = arguments[i]
	}
}
/**
 * Получить аргументы функции в виде строки
 * @link: http://stackoverflow.com/a/9924463/2274406
 */
var STRIP_COMMENTS = /((\/\/.*$)|(\/\*[\s\S]*?\*\/))/mg;
var ARGUMENT_NAMES = /([^\s,]+)/g;
function getArguments(func) {
  var fnStr = func.toString().replace(STRIP_COMMENTS, '');
  var result = fnStr.slice(fnStr.indexOf('(')+1, fnStr.indexOf(')')).match(ARGUMENT_NAMES);
  if(result === null)
     result = [];
  return result;
}

function googleClientId() {
    return ga.getAll()[0].get('clientId')
}

window.notify_options = {
    hideAnimation: 'fadeOut',
    showDuration: 0,
    hideDuration: 400,
    autoHideDelay: 3000
}