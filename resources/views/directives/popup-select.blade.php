<div>
    <div class="form-row-inner popup-show" ng-click="show_popup = true">
        <div class="select select__arrow">
            <span class="selected-items">@{{ getSelected() || noneText }}</span>
        </div>
    </div>
    <div class="popup" ng-class="{'active': show_popup}" >
        <div class="popup-close popup-hide" ng-click="show_popup = false"></div>
        <div class="popup-main">
            <div class="popup-main-close-button popup-hide" ng-click="show_popup = false"></div>
            <div class="popup-main-wrap">
                <div class="multiselect-popup">
                    <ul class="multiselect-list">
                        <li class="multiselect-list-item"
                            ng-class="{'multiselect-list-item__active': isSelected(item)}"
                            ng-click="selectItem(item)"
                            ng-repeat='item in filterItems(items)'
                        >@{{ (label && item[label]) || item }}</li>
                    </ul>

                    <div class="text-center">
                        <button class="button-green-large" ng-click="show_popup = false">
                            Выбрать
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
