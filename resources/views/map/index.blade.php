<div id='map'></div>
<script>
    map = new google.maps.Map(document.getElementById("map"), {
        center: MAP_CENTER,
        scrollwheel: false,
        zoom: 10,
        disableDefaultUI: true,
        clickableLabels: false,
        clickableIcons: false,
        zoomControl: true,
        zoomControlOptions: {position: google.maps.ControlPosition.LEFT_BOTTOM},
        scaleControl: true
    })
    @if($branch == 'ALL')
        $.each(BRANCH_COORDS, function(key, value) {
            type = key == 'TRG' ? 'white' : 'green'
            marker = newMarker(new google.maps.LatLng(value.lat, value.lng), map, type)
            newTooltip(marker, BRANCH_ADDRESS[key])
        })
    @else
        marker = newMarker(new google.maps.LatLng(BRANCH_COORDS.{{ $branch }}.lat, BRANCH_COORDS.{{ $branch }}.lng), map)
        map.setCenter(marker.getPosition())
        map.setZoom(14)
        newTooltip(marker, BRANCH_ADDRESS.{{ $branch }})
    @endif
</script>
