angular.module('App').directive 'review', (Subjects) ->
    restrict: 'E'
    templateUrl: 'directives/review'
    scope:
        review: '=review'
