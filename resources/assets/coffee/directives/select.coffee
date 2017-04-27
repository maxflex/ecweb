angular.module('App')
    .directive 'popupSelect', ->
        replace: true
        scope:
            noneText: '@'
            items:  '='
            model:  '='
            label:  '@'
            filter: '@'
            key:    '@'
        templateUrl: 'directives/popup-select'

        controller: ($scope, $attrs) ->
            $scope.multiple     = $attrs.hasOwnProperty 'multiple'
            $scope.show_popup   = false

            if $scope.multiple
                $scope.model = [] if not $scope.model

            $scope.selectItem = (item) ->
                item_id = itemId item

                if $scope.isSelected item
                    $scope.model = _.without $scope.model, item_id
                else
                    if $scope.multiple
                        $scope.model.push item_id
                    else
                        $scope.model = item_id

            $scope.isSelected = (item) ->
                item_id = itemId item

                if $scope.multiple
                    $scope.model.indexOf(item_id) isnt -1
                else
                    $scope.model is item_id

            itemId = (item) ->
                if _.isObject item
                    item.id
                else
                    return $scope.items.indexOf item if _.isArray $scope.items
                    return (_.invert $scope.items)[item]



            $scope.getSelected = ->
                return false if not $scope.model

                selected_items = []
                for item_id in (if $scope.multiple then $scope.model else [$scope.model])
                    if $scope.key
                        key = _.findKey($scope.items, {id: $scope.model})

                    label = if _.isObject(item = $scope.items[key || item_id]) then item[$scope.label] else item
                    selected_items.push label

                selected_items.join ', '

            $scope.filterItems = (items) ->
                return items if not $scope.filter

                _.filter items, (item, item_id) ->
                    eval $scope.filter