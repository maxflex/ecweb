angular
    .module 'App'
    .controller 'Gallery', ($scope, $timeout) ->
        bindArguments($scope, arguments)

        angular.element(document).ready ->
            $scope.all_photos = []
            _.each $scope.groups, (group) ->
                $scope.all_photos = $scope.all_photos.concat group.photo

        $scope.getFlatIndex = (photo_id) ->
            _.findIndex $scope.all_photos, id: photo_id
