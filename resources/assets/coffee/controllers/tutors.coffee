angular
    .module 'App'
    .constant 'REVIEWS_PER_PAGE', 5
    .controller 'Tutors', ($scope, $timeout, $http, Tutor, REVIEWS_PER_PAGE, Subjects) ->
        bindArguments($scope, arguments)
        initYoutube()

        # сколько загрузок преподавателей было
        search_count = 0

        # личная страница преподавателя?
        $scope.profilePage = ->
            RegExp(/^\/tutors\/[\d]+$/).test(window.location.pathname)

        # страница поиска
        $timeout ->
            if not $scope.profilePage()
                # SubjectService.init($scope.search.subjects)
                # StreamService.run('landing', 'serp')
                $scope.filter()

        $scope.reviews = (tutor, index) ->
            # StreamService.run 'reviews', StreamService.identifySource(tutor),
            #     position: $scope.getIndex(index)
            #     tutor_id: tutor.id
            if tutor.all_reviews is undefined
                tutor.all_reviews = Tutor.reviews
                    id: tutor.id
                , (response) ->
                    $scope.showMoreReviews(tutor)
            $scope.toggleShow(tutor, 'show_reviews', 'reviews', false)

        $scope.showMoreReviews = (tutor, index) ->
            # if tutor.reviews_page then StreamService.run 'reviews_more', StreamService.identifySource(tutor),
            #     position: $scope.getIndex(index)
            #     tutor_id: tutor.id
            #     depth: (tutor.reviews_page + 1) * REVIEWS_PER_PAGE
            tutor.reviews_page = if not tutor.reviews_page then 1 else (tutor.reviews_page + 1)
            from = (tutor.reviews_page - 1) * REVIEWS_PER_PAGE
            to = from + REVIEWS_PER_PAGE
            tutor.displayed_reviews = tutor.all_reviews.slice(0, to)
            # highlight('search-result-reviews-text')

        $scope.reviewsLeft = (tutor) ->
            return if not tutor.all_reviews or not tutor.displayed_reviews
            reviews_left = tutor.all_reviews.length - tutor.displayed_reviews.length
            if reviews_left > REVIEWS_PER_PAGE then REVIEWS_PER_PAGE else reviews_left

        # чтобы не редиректило в начале
        filter_used = false
        $scope.filter = ->
            $scope.tutors = []
            $scope.page = 1
            if filter_used
                # StreamService.updateCookie({search: StreamService.cookie.search + 1})
                # StreamService.run 'filter', null,
                #     search: StreamService.cookie.search
                #     subjects: $scope.SubjectService.getSelected().join(',')
                #     sort: $scope.search.sort
                #     station_id: $scope.search.station_id
                #     place: $scope.search.place
                # .then -> filter()
                filter()
            else
                filter()
                filter_used = true

        filter = ->
            search()
            # деселект hidden_filter при смене параметров
            # delete $scope.search.hidden_filter if $scope.search.hidden_filter and search_count
            # $.cookie('search', JSON.stringify($scope.search))

        $scope.nextPage = ->
            $scope.page++
            # StreamService.run('load_more_tutors', null, {page: $scope.page})
            search()

        # $scope.$watch 'page', (newVal, oldVal) -> $.cookie('page', $scope.page) if newVal isnt undefined

        search = ->
            $scope.searching = true
            Tutor.search
                filter_used: filter_used
                page: $scope.page
                search: $scope.search
            , (response) ->
                search_count++
                $scope.searching = false
                $scope.data = response
                $scope.tutors = $scope.tutors.concat(response.data)
                # if $scope.mobile then $timeout -> bindToggle()

        $scope.video = (tutor) ->
            player.loadVideoById(tutor.video_link)
            player.playVideo()
            if isMobile
                $('.fullscreen-loading-black').css('display', 'flex')
            openModal('video')

        # длительность видео
        $scope.videoDuration = (tutor) ->
            duration = parseInt(tutor.video_duration)
            format = if duration >= 60 then 'm мин s сек' else 's сек'
            moment.utc(duration * 1000).format(format)

        # длительность видео в ISO
        $scope.videoDurationISO = (tutor) ->
            moment.duration(tutor.video_duration, 'seconds').toISOString()

        # stream if index isnt null
        $scope.toggleShow = (tutor, prop, iteraction_type, index = null) ->
            if tutor[prop]
                $timeout ->
                    tutor[prop] = false
                , if $scope.mobile then 400 else 0
            else
                tutor[prop] = true
                # if index isnt false then StreamService.run iteraction_type, StreamService.identifySource(tutor),
                #     position: $scope.getIndex(index)
                #     tutor_id: tutor.id

        #
        # MOBILE
        #
        $scope.popup = (id, tutor = null, fn = null, index = null) ->
            openModal(id)
            if tutor isnt null then $scope.popup_tutor = tutor
            if fn isnt null then $timeout -> $scope[fn](tutor, index)
            # $scope.index = $scope.getIndex(index)
