var scope = null
var player = null
var isMobile = false
var modal_inited = false

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

    //
    // close modal on «back» button
    //
    $(window).on('hashchange', function() {
        if(window.location.hash != "#modal") {
            closeModal()
        }
    });

    angular.element(document).ready(function() {
		setTimeout(function() {
			scope = angular.element('[ng-app=App]').scope()
		}, 50)
    })
    
    if (! isMobile) {
        initPhotos()
    }

    // каждый раз, когда открывается любоая страница
    // отправляем стрим landing
    // $.post('/api/stream', {
    //     action: 'page',
    //     href: window.location.href,
    //     google_id: googleClientId(),
    //     yandex_id:
    // })
    setTimeout(function() {
        scope.StreamService.run('page', null, {href: window.location.href})
    }, 500)
})

function initPhotos() {
    wrapper = $('.photos')
    // текущий индекс фотки
    position = 1

    // сколько всего фоток (за исключением дублей в начале и конце для зацикливания)
    photosCount = wrapper.children().length

    // добавляем фотки в начало и конец для зациклиивания
    wrapper.prepend(wrapper.children().last().clone())
    wrapper.prepend(wrapper.children().eq(photosCount - 2).clone())
    wrapper.prepend(wrapper.children().eq(photosCount - 3).clone())
    
    wrapper.append(wrapper.children().eq(3).clone())
    wrapper.append(wrapper.children().eq(4).clone())
    wrapper.append(wrapper.children().eq(5).clone())

    applyPosition = function(newPosition, animate) {
        if (animate === false) {
            wrapper.css('transition', '')
        } else {
            wrapper.css('transition', 'all ease-in-out .3s')
        }
        wrapper.css('margin-left', newPosition + 'px')
    }

    getCurrentPosition = function() {
        return parseInt(wrapper.css('margin-left'))
    }

    goDirection = function(isForward, button) {
        $(button).css('pointer-events', 'none')
        photoWidth = wrapper.children().first().width()
        step = photoWidth + 20

        if (isForward) {
             // если листаем вперед и последняя фотка
             if (position === photosCount) {
                newPosition = getCurrentPosition() + (step * photosCount)
                applyPosition(newPosition, false)
                position = 0
            }
            newPosition = getCurrentPosition() + (step * -1)
            position++
        } else {
            // если листаем назад и первая фотка
            if (position === 1) {
                newPosition = getCurrentPosition() + (step * photosCount * -1)
                applyPosition(newPosition, false)
                position = photosCount + 1
            }
            newPosition = getCurrentPosition() + step
            position--
        }
        console.log(position)
        applyPosition(newPosition, true)
        setTimeout(function() {
            $(button).css('pointer-events', 'auto')
        }, 300)
    }
    $('.photo-controls__right').on('click', function() {
        goDirection(true, this)
    })
    $('.photo-controls__left').on('click', function() {
        goDirection(false, this)
    })
}

function closeModal() {
    $('.modal.active').removeClass('modal-animate-open').addClass('modal-animate-close')
    // if(window.location.hash == "#modal") {
    //     window.history.back()
    // }
    setTimeout(function() {
        $('.modal').removeClass('active')
        $('body').removeClass()
    	// $("body").addClass('open-modal-' + active_modal); active_modal = false
        $('.container').off('touchmove');
        // @todo: почему-то эта строчка ломает повторное воспроизведение видео
        if (typeof(onCloseModal) == 'function') {
            onCloseModal()
        }
    }, 300)
}

function openModal(id) {
    modal = $(".modal#modal-" + id)
    modal.removeClass('modal-animate-close').addClass('active').addClass('modal-animate-open')
    $('#menu-overlay').height('95%').scrollTop(); // iphone5-safari fix
    $("body").addClass('modal-open open-modal-' + id);
    // active_modal = id 
    $('.container').on('touchmove', function(e){e.preventDefault();});
    // window.location.hash = '#modal'
    if (typeof(onOpenModal) == 'function') {
        onOpenModal(id)
    }
}

// Автовоспроизведение видео с открытием модального окна
function initYoutube() {
    window.onYouTubeIframeAPIReady = function() {
        player = new YT.Player('youtube-video', {})
        player.addEventListener("onStateChange", function(state){
            if (state.data == YT.PlayerState.PLAYING) {
                setTimeout(function() {
                    $('.fullscreen-loading-black').css('display', 'none')
                }, 500)
            }
        })
    }

    window.onCloseModal = function() {
        player.stopVideo()
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

function dataLayerPush(object) {
    window.dataLayer = window.dataLayer || []
    window.dataLayer.push(object)
}

function keyCount (object) {
    return _.keys(object).length;
}

function streamLink(url, action, type, additional) {
    if (url === null) {
        scope.StreamService.run(action, type, additional)
        return
    }
    if (additional === undefined) {
        additional = {}
    }
    // в tel: тоже не подставлять
    if (url[0] != '/' && url.indexOf('tel') === -1 && url.indexOf('http') === -1) {
        url = '/' + url
    }

    if (url.indexOf('http') === -1 && url.indexOf('tel') === -1) {
        scope.StreamService.run(action, type, additional).then(function(data) {
            window.location = url
        })
    } else {
        scope.StreamService.run(action, type, additional)
        if (url.indexOf('tel') === -1) {
            window.open(url, '_blank')
        } else {
            window.location = url
        }
    }
}

function openChat() {
    $('#intergramRoot > div > div').first().click()
}
