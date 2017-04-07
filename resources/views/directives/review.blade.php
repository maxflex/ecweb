<div>
    <div class="double-spacing">
        @{{ review.comment }}
        <span class='review-rating' ng-class="{
            'color-green': review.rating == 5,
            'color-gold': review.rating == 4,
            'color-red': review.rating <= 3
        }">Оценка - @{{ review.rating }}.</span>
    </div>
    <div class="reviews-item-signature">
        <span ng-if='review.tutor'>Преподаватель: <a href="#">@{{ review.tutor.last_name }} @{{ review.tutor.first_name }} @{{ review.tutor.middle_name }}</a><br /></span>
        Подготовка: ЕГЭ по @{{ Subjects.dative[review.id_subject] }} в <academic year='review.year'></academic> учебном году<br />
        @{{ review.signature }} {{-- (@{{ review.grade }} класс) --}}
    </div>
</div>
