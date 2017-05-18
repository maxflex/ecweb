<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\Review;
use DB;
use Cache;

class StatsController extends Controller
{
    /**
     * Все отзывы
     *
     */
    public function index(Request $request)
    {
        $query = Review::withStudent()->orderBy('score', 'desc');

        if ($request->tutor_id) {
            $query->where('id_teacher', $request->tutor_id);
        }

        if ($request->subject_id) {
            $query->where('id_subject', $request->subject_id);
        }

        if ($request->grade) {
            $query->where('teacher_reviews.grade', $request->grade);
        }

        $reviews = $query->get()->all();

        foreach($reviews as $review) {
            $review->tutor = Cache::remember(cacheKey('tutor', $review->id_teacher), 60 * 24, function() use ($review) {
                return DB::connection('egerep')->table('tutors')->whereId($review->id_teacher)->select('id', 'first_name', 'last_name', 'middle_name')->first();
            });
        }

        return [
            'reviews' => $reviews,
            'avg' => round($query->where('score', '>', 0)->avg('score'), 1),
        ];
    }
}
