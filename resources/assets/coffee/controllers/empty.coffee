angular
    .module 'App'
    .controller 'Empty', ($scope, $timeout, $filter, StreamService) ->
        bindArguments($scope, arguments)

        # для развертывания предметов на главной странице
        # не нужно, чтобы отправлялось событие при свертывании
        $scope.expand_items = {}
        $scope.expandStream = (action, type) ->
            # type = $scope.$eval "'#{type}' | filter:cut:false:10"
            type = $filter('cut')(type, false, 20, '...')
            $scope.expand_items[type] = not $scope.expand_items[type]
            StreamService.run(action, type) if $scope.expand_items[type]



        $timeout ->
            # gallery methods
            $scope.gallery = {}
