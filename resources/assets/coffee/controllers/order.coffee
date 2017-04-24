angular
    .module 'App'
    .controller 'Order', ($scope, $timeout, $http, Grades, Request) ->
        bindArguments($scope, arguments)
        $timeout ->
            # @todo: client_id, referer, referer_url, user agent
            $scope.order = {}

        $scope.request = ->
            $scope.sending = true
            $scope.errors = {}
            Request.save $scope.order, ->
                $scope.sending = false
                $scope.sent = true
            , (response) ->
                $scope.sending = false
                angular.forEach response.data, (errors, field) ->
                    $scope.errors[field] = errors
                    selector = "[ng-model$='#{field}']"
                    $("input#{selector}, textarea#{selector}").focus()
