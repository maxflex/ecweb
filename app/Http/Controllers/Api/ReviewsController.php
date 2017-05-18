<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\Review;
use DB;

class ReviewsController extends Controller
{
    /**
     * Все отзывы
     *
     */
    public function index(Request $request)
    {
        $query = Review::query();

        if ($request->tutor_id) {
            $query->where('id_teacher', $request->tutor_id);
        }

        if ($request->subject_id) {
            $query->where('id_subject', $request->subject_id);
        }

        if ($request->grade) {
            $query->where('grade', $request->grade);
        }

        $paginator = $query->simplePaginate(isset($request->per_page) ? $request->per_page : 20);

        $reviews = $paginator->getCollection()->map(function ($review) {
            $review->tutor = DB::connection('egerep')->table('tutors')->whereId($review->id_teacher)->select('id', 'first_name', 'last_name', 'middle_name')->first();
            $review->student = DB::connection('egecrm')->table('students')->whereId($review->id_student)->select('first_name', 'last_name', 'middle_name')->first();
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
