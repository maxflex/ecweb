angular
    .module 'App'
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
    .controller 'Stats', ($scope, $timeout, $http, Subjects, Grades) ->
        bindArguments($scope, arguments)

        $timeout ->
            $scope.search = {per_page: 50}
            $scope.filter()

        $scope.popup = (index) ->
            $scope.show_review = index

        $scope.nextPage = ->
            $scope.page++
            # StreamService.run('load_more_tutors', null, {page: $scope.page})
            search()

        # $scope.$watch 'page', (newVal, oldVal) -> $.cookie('page', $scope.page) if newVal isnt undefined

        $scope.filter = ->
            $scope.reviews = null
            $scope.page = 1
            $scope.has_more_pages = true
            search()

        search = ->
            $scope.searching = true
            $http.get('/api/reviews?page=' + $scope.page + '&' + $.param($scope.search)).then (response) ->
                console.log(response)
                $scope.searching = false
                $scope.reviews = [] if $scope.page is 1
                $scope.reviews = $scope.reviews.concat(response.data.reviews)
                $scope.has_more_pages = response.data.has_more_pages
                # if $scope.mobile then $timeout -> bindToggle()
