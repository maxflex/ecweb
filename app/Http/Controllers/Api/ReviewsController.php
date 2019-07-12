<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\Review;
use App\Models\NewReview;
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
        $paginator = NewReview::orderBy('review_comments.created_at', 'desc')->simplePaginate(20);

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
        $query = NewReview::whereHas('client', function($query) {
	       return $query->whereHas('photo', function($query) {
		      return $query->where('has_cropped', 1); 
	       });
        });
        
        
        if ($request->ids) {
            $query->whereNotIn('reviews.id', $request->ids);
        }

        if ($request->grade_id) {
            $query->where('reviews.grade_id', '=', $request->grade);
        }

        if ($request->exclude_id) {
            $query->where('reviews.id', '<>', $request->exclude_id);
        }

        if ($request->subject) {
            $subject_id = Factory::getSubjectId($request->subject);
            $query->where('reviews.subject_id', '=', $subject_id);
        }

        $paginator = $query->orderBy(DB::raw("
            CASE
                WHEN (reviews.score / reviews.max_score) >= 0.81 THEN 6
                WHEN (reviews.score / reviews.max_score) >= 0.71 THEN 5
                WHEN (reviews.score / reviews.max_score) >= 0.61 THEN 4
                WHEN (reviews.score / reviews.max_score) >= 0.51 THEN 3
                WHEN (reviews.score / reviews.max_score) >= 0.41 THEN 2
                ELSE 1
            END
        "), 'desc')->orderBy('review_comments.created_at', 'desc')->simplePaginate($request->count ? $request->count : 9999);

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
        // return Review::withStudent()->where('id_teacher', $id)->orderBy(DB::raw("
        //     CASE
        //         WHEN ball_efficency >= 0.81 THEN 6
        //         WHEN ball_efficency >= 0.71 THEN 5
        //         WHEN ball_efficency >= 0.61 THEN 4
        //         WHEN ball_efficency >= 0.51 THEN 3
        //         WHEN ball_efficency >= 0.41 THEN 2
        //         ELSE 1
        //     END
        // "), 'desc')->orderBy('teacher_reviews.date', 'desc')->get();
    }

}
