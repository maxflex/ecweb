<div class='vertical-slider'>
    <div class='photos'>
        @foreach ($items as $item)
            <img src="{{ $item->url }}" />
        @endforeach
    </div>
</div>