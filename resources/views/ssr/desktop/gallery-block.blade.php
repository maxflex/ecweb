<div class='full-width-wrapper block'>
    <div class='photos'>
        @foreach ($items as $item)
            <img src="{{ $item->url }}" />
        @endforeach
    </div>
</div>
<div>
    <div class='photo-controls'>
        <div class='photo-controls__left flex-items'>
            <img src='/img/svg/arrow-pointing-left.svg' />
            <a>назад</a>
        </div>
        <div class='photo-controls__right flex-items'>
            <a>вперед</a>
            <img src='/img/svg/arrow-pointing-left.svg' />
        </div>
    </div>
</div>