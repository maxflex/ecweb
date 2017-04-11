<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Program;
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

    public function program($id)
    {
        if (Program::whereId($id)->exists()) {
            $html = Page::whereUrl(Program::URL)->first()->html;
            Parser::compileProgram($id, $html);
        } else {
            $html = Variable::display('page-404');
        }
        return view('pages.index')->with(compact('html'));
    }

    public function about()
    {
        $html = Page::whereUrl(Faq::URL)->first()->html;
        Parser::compileFaq($html);
        return view('pages.index')->with(compact('html'));
    }
}
