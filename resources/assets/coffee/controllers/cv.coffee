angular
    .module 'App'
    .controller 'Cv', ($scope, $timeout, $http, Cv) ->
        bindArguments($scope, arguments)

        $timeout ->
            $scope.cv = {}

        $scope.send = ->
            $scope.sending = true
            $scope.errors = {}
            Cv.save $scope.cv, ->
                $scope.sending = false
                $scope.sent = true
            , (response) ->
                $scope.sending = false
                angular.forEach response.data, (errors, field) ->
                    $scope.errors[field] = errors
                    selector = "[ng-model$='#{field}']"
                    $("input#{selector}, textarea#{selector}").focus()
