angular
    .module 'App'
    .controller 'Gallery', ($scope, $timeout) ->
        bindArguments($scope, arguments)

        $scope.shown_images = []

        $scope.nextPage = ->
            start = 0
            $scope.shown_images = _.union $scope.shown_images, $scope.images.splice start, Math.min start + 30, $scope.images.length
            console.log $scope.shown_images.length, $scope.images.length

        angular.element(document).ready ->
            $scope.nextPage()
