<?php
    use App\Models\Variable;

    URL::forceSchema('https');

    Route::get('sitemap.xml', 'SitemapController@index');

    Route::get('/request', function() {
        $html = Variable::display('page-tutor-request');
        return $html;
    });

    Route::get('/cv', function() {
        $html = Variable::display('page-cv');
        return $html;
    });

    Route::get('/login', function() {
        $html = Variable::display('page-login');
        return $html;
    });

    Route::get('/full', function() {
        unset($_SESSION['force_mobile']);
        $_SESSION['force_full'] = true;
        $_SESSION['page_was_refreshed'] = true;
        return redirect()->back();
    });

    Route::get('/mobile', function() {
        unset($_SESSION['force_full']);
        $_SESSION['force_mobile'] = true;
        $_SESSION['page_was_refreshed'] = true;
        return redirect()->back();
    });

    # Templates for angular directives
    Route::get('directives/{directive}', function($directive) {
        return view("directives.{$directive}");
    });

    # Tutor auto login
    Route::get('login/{hash}', 'PagesController@login');

    # Tutor profile page
    Route::get('{id}', 'PagesController@yacht')->where('id', '[0-9]+');

    Route::get('programm/{id}', 'PagesController@programm')->where('id', '[0-9]*');

    # All serp pages
    Route::get('{url?}', 'PagesController@index')->where('url', '.*');
