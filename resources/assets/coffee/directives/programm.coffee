angular
    .module 'App'
    .directive 'programmItem', ->
        templateUrl: '/directives/programm'
        scope:
            item:   '='
            level:  '=?'
            levelstring: '='
        controller: ($timeout, $element, $scope) ->
            $scope.level = 0 if not $scope.level

            $scope.getChildLevelString = (child_index) ->
                str = if $scope.levelstring then $scope.levelstring else ''
                str + (child_index + 1) + '.'