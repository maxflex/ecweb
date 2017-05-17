angular
    .module 'App'
    .constant 'REVIEWS_PER_PAGE', 5
    .filter 'cut', ->
        (value, wordwise, max, tail = '') ->
            return '' if not value
            max = parseInt(max, 10)
            return value if not max
            return value if value.length <= max
            value = value.substr(0, max)
            if wordwise
                lastspace = value.lastIndexOf(' ')
                if lastspace isnt -1
                  if value.charAt(lastspace-1) is '.' || value.charAt(lastspace-1) is ','
                    lastspace = lastspace - 1
                  value = value.substr(0, lastspace)
            return value + tail
    .controller 'Reviews', ($scope, $timeout, $http, Subjects) ->
        bindArguments($scope, arguments)

        $timeout ->
            $scope.reviews = []
            $scope.page = 1
            $scope.has_more_pages = true
            # $scope.show_review = {}
            search()

        $scope.popup = (index) ->
            $scope.show_review = index

        $scope.nextPage = ->
            $scope.page++
            # StreamService.run('load_more_tutors', null, {page: $scope.page})
            search()

        # $scope.$watch 'page', (newVal, oldVal) -> $.cookie('page', $scope.page) if newVal isnt undefined

        search = ->
            $scope.searching = true
            $http.get('/api/reviews?page=' + $scope.page).then (response) ->
                console.log(response)
                $scope.searching = false
                $scope.reviews = $scope.reviews.concat(response.data.reviews)
                $scope.has_more_pages = response.data.has_more_pages
                # if $scope.mobile then $timeout -> bindToggle()
