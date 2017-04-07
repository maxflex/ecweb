<?php

namespace App\Http\Controllers;

use App\Models\Programm;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Page;
use App\Models\Variable;
use App\Models\Yacht;
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
     * Yacht profile page
     */
    public function yacht($id)
    {
        if (Yacht::whereId($id)->exists()) {
            $html = Variable::display('page-yacht');
            Parser::compileYacht($id, $html);
        } else {
            $html = Variable::display('page-404');
        }
        return view('pages.index')->with(compact('html'));
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
