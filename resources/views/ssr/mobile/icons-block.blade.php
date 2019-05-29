<div class='block block_icons'>
    <div class='block__icons'>
        @foreach($items as $item)
        <div>
            <img src='/img/icons-new/pros/{{ $item->icon }}' />
            <h1>{{ $item->title }}</h1>
            <div>{{ $item->desc }}</div>
        </div>
        @endforeach
    </div>
</div>