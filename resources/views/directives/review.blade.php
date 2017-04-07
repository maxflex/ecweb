<div>
    @{{ review.comment }}
    <b class="color-green">Оценка - @{{ review.rating }}.</b>
    <div class="reviews-item-signature">
        <span ng-if='review.tutor'>Преподаватель: <a href="#">@{{ review.tutor.last_name }} @{{ review.tutor.first_name }} @{{ review.tutor.middle_name }}</a><br /></span>
        Подготовка: ЕГЭ по @{{ Subjects.dative[review.id_subject] }} в <academic year='review.year'></academic> учебном году<br />
        @{{ review.signature }} {{-- (@{{ review.grade }} класс) --}}
    </div>
</div>
