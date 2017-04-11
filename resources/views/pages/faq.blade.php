@foreach(\App\Models\FaqGroup::getAll() as $faq_group)
    @if($faq_group->id)
        <h3>{{ $faq_group->title }}</h3>
    @endif
    <ul class="questions">
        @foreach($faq_group->faq as $faq)
            <li class="questions-item">
                <div class="questions-item-title">
                    <b>Вопрос:</b>
                    {{ $faq->question }}
                </div>
                <div class="questions-item-answer">
                    <b>Ответ:</b>
                    {{ $faq->answer }}
                </div>
            </li>
        @endforeach
    </ul>
@endforeach