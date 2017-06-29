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

class ReviewsController extends Controller
{
    /**
     * Все отзывы
     *
     */
    public function index(Request $request)
    {
        $paginator = Review::simplePaginate(20);

        $reviews = $paginator->getCollection()->map(function ($review) {
            $review->tutor = Cache::remember(cacheKey('tutor', $review->id_teacher), 60 * 24, function() use ($review) {
                $tutor = DB::connection('egerep')->table('tutors')->whereId($review->id_teacher)->select('id', 'first_name', 'last_name', 'middle_name', 'subjects')->first();
                $tutor->subjects_string_common = implode(', ', array_map(function($subject_id) {
                    return Cacher::getSubjectName($subject_id, 'name');
                }, explode(',', $tutor->subjects)));
                return $tutor;
            });
            return $review;
        });
        return [
            'reviews' => $reviews,
            'has_more_pages' => $paginator->hasMorePages(),
        ];
    }

    /**
     * Показать отзывы препода
     *
     */
    public function show($id)
    {
        return Review::where('id_teacher', $id)->get();
    }

}
