<div class='block block_photos vertical-slider'>
    <div class='photos block__gallery'>
        @foreach ($items as $item)
            <img src="{{ $item->url }}" />
        @endforeach
    </div>
</div>
<style>
    .footer {
        margin-top: 0 !important;
    }
</style>