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
            $scope.search = {}
            $scope.data = {}
            $scope.show_review = null
            $scope.filter()
            $(window).on 'click', (event) ->
                if ($scope.show_review isnt null && not $(event.target).hasClass('expand-comment'))
                    $scope.popup(null) if not $(event.target).hasClass 'review-popup'
                    $scope.$apply()

        $scope.popup = (index) ->
            # запускаем позже, чтобы успел отработать window.click
            $timeout -> $scope.show_review = index

        $scope.filter = ->
            $scope.data = null
            search()

        search = ->
            $scope.searching = true
            $http.get('/api/stats?' + $.param($scope.search)).then (response) ->
                console.log(response)
                $scope.searching = false
                $scope.data = response.data
                # if $scope.mobile then $timeout -> bindToggle()
