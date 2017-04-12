<a ng-click='gallery.open()'>смотреть фото ({{ count($urls) }})</a>
<ng-image-gallery images="{{ json_encode($urls) }}" thumbnails='false' methods='gallery' bg-close='true'></ng-image-gallery>
