<div class='full-width-wrapper'>
    <div class='block__gallery'>
        @foreach ($items as $index => $item)
        {{-- <div style='width: 25vw;height: 25vw;margin: 0 10px;display: flex; align-items:center;justify-content:center;font-size: 300px; border: 3px solid black'>
            {{ $index + 1}}
        </div> --}}
            <img src="{{ $item->url }}" />
        @endforeach
    </div>
</div>
<style>
    .footer {
        margin-top: 0 !important;
    }
</style>