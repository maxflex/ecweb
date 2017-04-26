angular
    .module 'App'
    .controller 'Cv', ($scope, $timeout, $http, Cv) ->
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
            , (response) ->
                $scope.sending = false
                angular.forEach response.data, (errors, field) ->
                    $scope.errors[field] = errors
                    selector = "[ng-model$='#{field}']"
                    input = $("input#{selector}, textarea#{selector}")
                    input.focus()
                    input.notify errors[0], notify_options if isMobile
