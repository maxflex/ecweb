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
    .controller 'Stats', ($scope, $timeout, $http, Subjects, Grades, AvgScores) ->
        bindArguments($scope, arguments)

        $timeout ->
            $scope.search = {}
            $scope.data = {}
            $scope.show_review = null
            $scope.filter()

        $scope.popup = (index) ->
            $scope.show_review = index

        $scope.filter = ->
            search()

        $scope.getScoreLabel = ->
            [subject_id, grade, profile] = $scope.search.subject_grade.split('-')
            label = Subjects.dative[subject_id]
            if subject_id == 1 and grade >= 10
                if grade == 10
                    label += ' (база)'
                else
                    label += if profile then ' (профиль)' else ' (база)'
            label

        # предмет-класс-(профиль/база?)
        $scope.getSubjectsGrades = ->
            if $scope.subject_grades is undefined
                options = [
                    id: '1-11-1'
                    label: 'математика 11 класс, профиль'
                ,
                    id: '1-11-0'
                    label: 'математика 11 класс, база'
                ]

                $.each Subjects.all, (subject_id, subject_name) ->
                    [11, 10, 9].forEach (grade) ->
                        return if (grade is 11 && parseInt(subject_id) == 1)
                        label = "#{subject_name} #{grade} класс"
                        label += ', база' if (grade is 10 && parseInt(subject_id) is 1)
                        options.push
                            id: "#{subject_id}-#{grade}"
                            label: label

                $scope.subject_grades = options

            $scope.subject_grades

        search = ->
            $scope.searching = true
            $http.get('/api/stats?' + $.param($scope.search)).then (response) ->
                console.log(response)
                $scope.searching = false
                $scope.data = response.data
                if isMobile then $timeout -> bindToggle()
