<div class='full-width-wrapper'>
    <div class='block__gallery'>
        @foreach ($items as $index => $item)
            <div>
                <img src="{{ $item->url }}" />
            </div>
        @endforeach
    </div>
</div>
<style>
    .footer {
        margin-top: 0 !important;
    }
</style>