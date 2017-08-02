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

class ReviewsController extends Controller
{
    /**
     * Все отзывы
     *
     */
    public function index(Request $request)
    {
        $paginator = Review::withStudent()->simplePaginate(20);

        $reviews = $paginator->getCollection()->map(function ($review) {
            $review->subject_string = Cache::remember(cacheKey('subject-dative', $review->id_subject), 60 * 24, function() use ($review) {
                return Cacher::getSubjectName($review->id_subject, 'dative');
            });
            $review->date_string = date('j ', strtotime($review->date)) . Months::SHORT[date('n', strtotime($review->date))] . date(' Y', strtotime($review->date));
            // $review->tutor = Cache::remember(cacheKey('review-tutor', $review->id_teacher), 60 * 24, function() use ($review) {
            //     $tutor = DB::connection('egerep')->table('tutors')->whereId($review->id_teacher)->select('id', 'first_name', 'last_name', 'middle_name', 'subjects')->first();
            //     $tutor->subjects_string_common = implode(', ', array_map(function($subject_id) {
            //         return Cacher::getSubjectName($subject_id, 'name');
            //     }, explode(',', $tutor->subjects)));
            //     return $tutor;
            // });
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
