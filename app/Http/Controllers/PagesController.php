<?php

namespace App\Http\Controllers;

use App\Models\Programm;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Page;
use App\Models\Variable;
use App\Models\Tutor;
use App\Models\Service\Parser;

class PagesController extends Controller
{
    /**
     * Все страницы (на самом деле это теперь только серп)
     */
    public function index($url)
    {
        $page = Page::whereUrl($url);
        if (! $page->exists()) {
            $html = Variable::display('page-404');
        } else {
            $html = $page->first()->html;
        }
        return view('pages.index')->with(compact('html'));
    }

    /**
     * Tutor profile page
     */
    public function tutor($id)
    {
        if (Tutor::whereId($id)->exists()) {
            $html = Page::whereUrl(Tutor::URL . '/:id')->first()->html;
            Parser::compileTutor($id, $html);
            $status = 200;
        } else {
            $html = Variable::display('page-404');
            $status = 404;
        }
        $_SESSION['action'] = 'profile';
        return response()->view('pages.index', compact('html'), $status);
    }

    public function programm($id)
    {
        if (Programm::whereId($id)->exists()) {
            $html = Page::whereUrl(Programm::PAGE_URL)->first()->html;
            Parser::compileProgramm($id, $html);
        } else {
            $html = Variable::display('page-404');
        }
        return view('pages.index')->with(compact('html'));
    }
}
