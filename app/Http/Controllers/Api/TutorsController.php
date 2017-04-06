<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\Page;
use DB;

class TutorsController extends Controller
{
    public function index(Request $request)
    {
        return Tutor::paginate(10);
    }

    /**
     * Получить отзывы
     */
    public function reviews($id)
    {
        return Tutor::reviews($id)
        ->select(
            'reviews.created_at',
            'reviews.score',
            'reviews.comment',
            'reviews.signature',
            DB::raw('attachments.date as attachment_date'),
            DB::raw('archives.date as archive_date'),
            DB::raw('(SELECT COUNT(*) FROM account_datas ad WHERE ad.tutor_id = attachments.tutor_id AND ad.client_id = attachments.client_id) as lesson_count')
        )->orderBy('reviews.created_at', 'desc')->get();
    }

    /**
     * Поиск по преподам
     */
    public function search(Request $request)
    {
        // потому что надо поменять subjects, а из $request нельзя
        $search = $request->search;

        @extract($search);

        // пытаемся найти serp-страницу с такими параметрами
        // если находит при пагинации страницу с похожими параметрами – не редиректить
        // if ($request->filter_used && $request->page < 2) {
        //     $page = Page::findByParams($search);
        //     if ($page->exists()) {
        //         return ['url' => $page->inRandomOrder()->value('url')];
        //     } else
        //     if ($id != 10) {
        //         unset($_COOKIE['search']);
        //         return ['url' => Page::whereId(10)->value('url')];
        //     }
        // }

        // force current page
        Paginator::currentPageResolver(function() use ($request) {
            return $request->page;
        });

        return Tutor::search($search)->paginate(10);
    }
}
