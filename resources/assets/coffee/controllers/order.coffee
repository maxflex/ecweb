angular
    .module 'App'
    .controller 'Order', ($scope, $timeout, $http, Grades, Subjects, Request) ->
        bindArguments($scope, arguments)
        $timeout ->
            # @todo: client_id, referer, referer_url, user agent
            $scope.order = {}

        $scope.request = ->
            $scope.sending = true
            $scope.errors = {}
            Request.save $scope.order, ->
                dataLayerPush
                    event: 'purchase'
                    ecommerce:
                        currencyCode: 'RUB'
                        purchase:
                            actionField:
                                id: googleClientId()
                            products: [
                                # класс
                                brand: $scope.order.grade
                                # предметы_филиал
                                category: (if $scope.order.subjects then $scope.order.subjects.sort().join(',') else '') + '_' + $scope.order.branch_id
                                quantity: 1
                            ]
                $scope.sending = false
                $scope.sent = true
                $('body').animate scrollTop: $('.header').offset().top
            , (response) ->
                $scope.sending = false
                angular.forEach response.data, (errors, field) ->
                    $scope.errors[field] = errors
                    selector = "[ng-model$='#{field}']"
                    input = $("input#{selector}, textarea#{selector}")
                    input.focus()
                    input.notify errors[0], notify_options if isMobile

        $scope.isSelected = (subject_id) ->
            return false if not ($scope.order and $scope.order.subjects)
            $scope.order.subjects.indexOf(subject_id) isnt -1

        $scope.selectSubject = (subject_id) ->
            $scope.order.subjects = [] if not $scope.order.subjects
            if $scope.isSelected subject_id
                $scope.order.subjects = _.without $scope.order.subjects, subject_id
            else
                $scope.order.subjects.push subject_id

        $scope.selectedSubjectsList = ->
            return false if not $scope.order?.subjects?.length

            subjects = []
            for subject_id in $scope.order.subjects
                subjects.push $scope.Subjects[subject_id].name

            subjects.join ', '