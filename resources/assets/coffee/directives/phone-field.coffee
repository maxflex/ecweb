angular.module('App')
    .directive 'ngPhone', ->
        restrict: 'A'
        link: ($scope, element) ->
            $(element).mask("+7 (999) 999-99-99", { autoclear: false })
