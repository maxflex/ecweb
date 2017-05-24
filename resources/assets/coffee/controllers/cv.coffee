angular
    .module 'App'
    .controller 'Cv', ($scope, $timeout, $http, Subjects, Cv) ->
        bindArguments($scope, arguments)

        $timeout ->
            $scope.cv = {}
            $scope.sent = false

        $scope.send = ->
            $scope.sending = true
            $scope.errors = {}
            Cv.save $scope.cv, ->
                $scope.sending = false
                $scope.sent = true
                $('body').animate scrollTop: $('.header').offset().top
                dataLayerPush
                    event: 'cv'
                    # ecommerce:
                    #     currencyCode: 'RUR'
                    #     purchase:
                    #         actionField:
                    #             id: googleClientId()
                    #         products: [
                    #             # класс
                    #             # brand: $scope.order.grade
                    #             # предметы_филиал
                    #             category: (if $scope.cv.subjects then $scope.cv.subjects.sort().join(',') else '')
                    #             quantity: 1
                    #         ]
            , (response) ->
                $scope.sending = false
                angular.forEach response.data, (errors, field) ->
                    $scope.errors[field] = errors
                    selector = "[ng-model$='#{field}']"
                    input = $("input#{selector}, textarea#{selector}")
                    input.focus()
                    input.notify errors[0], notify_options if isMobile

        $scope.isSelected = (subject_id) ->
                return false if not ($scope.cv and $scope.cv.subjects)
                $scope.cv.subjects.indexOf(subject_id) isnt -1

        $scope.selectSubject = (subject_id) ->
            $scope.cv.subjects = [] if not $scope.cv.subjects
            if $scope.isSelected subject_id
                $scope.cv.subjects = _.without $scope.cv.subjects, subject_id
            else
                $scope.cv.subjects.push subject_id

        $scope.selectedSubjectsList = ->
            return false if not $scope.cv?.subjects?.length
            subjects = []
            for subject_id in $scope.cv.subjects
                subjects.push $scope.Subjects[subject_id].name
            subjects.join ', '