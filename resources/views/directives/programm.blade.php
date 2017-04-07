<span ng-class="{'b-like': level == 1}" ng-if="level">
    @{{ levelstring }} @{{ item.title }}
</span>
<ul ng-class="{'list-main': !level }">
    <li ng-repeat="child in item.content">
        <programm-item item="child" level="level + 1" levelstring="getChildLevelString($index)"></programm-item>
    </li>
</ul>