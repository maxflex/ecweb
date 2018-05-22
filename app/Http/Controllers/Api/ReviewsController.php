<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\Review;
use DB;
use Cache;
use App\Service\Cacher;
use App\Service\Months;
use App\Models\Service\Factory;

class ReviewsController extends Controller
{
    /**
     * Все отзывы
     *
     */
    public function index(Request $request)
    {
        $paginator = Review::withStudent()->orderBy('teacher_reviews.date', 'desc')->simplePaginate(20);

        return [
            'reviews'        => $paginator->getCollection(),
            'has_more_pages' => $paginator->hasMorePages(),
        ];
    }

    /**
     * Все отзывы (блоки)
     *
     */
    public function block(Request $request)
    {
        $query = Review::withStudent()->where('users.photo_extension', '<>', '');

        if ($request->ids) {
            $query->whereNotIn('teacher_reviews.id', $request->ids);
        }

        if ($request->grade) {
            $query->where('teacher_reviews.grade', '=', $request->grade);
        }

        if ($request->subject) {
            $subject_id = Factory::getSubjectId($request->subject);
            $query->where('teacher_reviews.id_subject', '=', $subject_id);
        }

        $paginator = $query->orderBy(DB::raw("
            CASE
                WHEN ball_efficency >= 0.81 THEN 6
                WHEN ball_efficency >= 0.71 THEN 5
                WHEN ball_efficency >= 0.61 THEN 4
                WHEN ball_efficency >= 0.51 THEN 3
                WHEN ball_efficency >= 0.41 THEN 2
                ELSE 1
            END
        "), 'desc')->orderBy('teacher_reviews.date', 'desc')->simplePaginate($request->count ? $request->count : 9999);

        return [
            'reviews' => $paginator->getCollection(),
            'has_more_pages' => $paginator->hasMorePages(),
        ];
    }

    /**
     * Показать отзывы препода
     *
     */
    public function show($id)
    {
        return Review::withStudent()->where('id_teacher', $id)->orderBy(DB::raw("
            CASE
                WHEN ball_efficency >= 0.81 THEN 6
                WHEN ball_efficency >= 0.71 THEN 5
                WHEN ball_efficency >= 0.61 THEN 4
                WHEN ball_efficency >= 0.51 THEN 3
                WHEN ball_efficency >= 0.41 THEN 2
                ELSE 1
            END
        "), 'desc')->orderBy('teacher_reviews.date', 'desc')->get();
    }

}
