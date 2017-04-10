angular
    .module 'App'
    .controller 'Order', ($scope, $timeout, $http, Grades, Branches, Request) ->
        bindArguments($scope, arguments)

        $timeout ->
            # @todo: client_id, referer, referer_url, user agent
            $scope.order = {}
            $scope.sent = true

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
