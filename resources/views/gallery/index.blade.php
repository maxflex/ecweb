@if($hide_link)
    <span ng-init="images={{ json_encode($urls) }}"></span>
@else
    <a ng-click='gallery.open()'>фотогалерея ({{ count($urls) }})</a>
@endif
<ng-image-gallery images="{{ json_encode($urls) }}" thumbnails='false' methods='gallery' bg-close='true'></ng-image-gallery>
