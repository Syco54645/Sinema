<div ng-repeat="featureItem in viewVars.assembledFeature">
    <div ng-class="isSpoiler(featureItem.type)">
        <div ng-if="featureItem.type != 'Trailer'">
            <b>{{ featureItem.type }}</b>: {{ featureItem.item.title }} <span ng-if="featureItem.item.year">- {{ featureItem.item.year }}</span>
        </div>
        <div ng-if="featureItem.type == 'Trailer'">
            <b>{{ featureItem.type }}</b>:
            <ul>
                <li ng-repeat="trailer in featureItem.item">
                    {{ trailer.title }} <span ng-if="featureItem.item.year">- {{ trailer.year }}</span>
                </li>
            </ul>
        </div>
    </div>
    <div ng-class="isSpoiler(featureItem.item.type)" ng-if="featureItem.item.type == 'trailer'">
    </div>
</div>
