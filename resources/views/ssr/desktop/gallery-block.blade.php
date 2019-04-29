<div class='block'>
    <div class='full-width-wrapper'>
        <div class='photos'>
            @foreach ($items as $index => $item)
            {{-- <div style='width: 25vw;height: 25vw;margin: 0 10px;display: flex; align-items:center;justify-content:center;font-size: 300px; border: 3px solid black'>
                {{ $index + 1}}
            </div> --}}
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
</div>