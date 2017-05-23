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
            $scope.search = {page: 1}
            $scope.data = {}
            $scope.show_review = null
            $scope.filter()

        $scope.popup = (index) ->
            $scope.show_review = index

        $scope.nextPage = ->
            $scope.search.page++
            search()

        $scope.filter = ->
            $scope.search.page = 1
            search()

        $scope.getScoreLabel = ->
            [subject_id, grade, profile] = $scope.search.subject_grade.split('-')
            label = (if parseInt(grade) is 9 then 'ОГЭ' else 'ЕГЭ') + ' по ' + Subjects.dative[subject_id]
            if (parseInt(subject_id) is 1 && parseInt(grade) >= 10)
                if parseInt(grade) is 10
                    label += ' (база)'
                else
                    label += if parseInt(profile) then ' (профиль)' else ' (база)'
            label

        # предмет-класс-(профиль/база?)
        $scope.getSubjectsGrades = ->
            if $scope.subject_grades is undefined
                options = [
                    id: '1-11-1'
                    label: 'ЕГЭ математика (профиль)'
                ,
                    id: '1-11-0'
                    label: 'ЕГЭ математика (база)'
                ]

                [11, 10, 9].forEach (grade) ->
                    $.each Subjects.full, (subject_id, subject_name) ->
                        return if (grade is 11 && parseInt(subject_id) == 1)
                        subject_name = subject_name.toLowerCase()
                        switch parseInt(grade)
                            when 11 then grade_label = 'ЕГЭ'
                            when 9 then grade_label = 'ОГЭ'
                            else grade_label = "#{grade} класс"
                        label = "#{grade_label} #{subject_name}"
                        label += ' (база)' if (grade is 10 && parseInt(subject_id) is 1)
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
                if $scope.search.page is 1
                    $scope.data = response.data
                else
                    $scope.data.has_more_pages = response.data.has_more_pages
                    $scope.data.reviews = $scope.data.reviews.concat(response.data.reviews)
                $timeout -> $('.custom-select').trigger('render')
                if isMobile then $timeout -> bindToggle()
