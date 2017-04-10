angular
    .module 'App'
    .controller 'Empty', ($scope) ->
        bindArguments($scope, arguments)

        # gallery methods
        $scope.gallery = {}
