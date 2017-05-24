angular
    .module 'App'
    .controller 'Empty', ($scope, $timeout) ->
        bindArguments($scope, arguments)

        $timeout ->
            # gallery methods
            $scope.gallery = {}
