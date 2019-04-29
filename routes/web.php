<?php
    use App\Models\Variable;
    use App\Models\Programm;
    use App\Models\Tutor;

    URL::forceSchema('https');

    Route::get('sitemap.xml', 'SitemapController@index');

	Route::get('testy', function() {
		$words = 'ВАВТ ВГНА ВЗФЭИ ГУУ МАДИ МАИ МАРХИ МАТИ МГГУ МГИМО МГЛУ МГМСУ МГМУ МГОУ МГСУ МГТУ МГУ МГУПИ МГЮА МИИТ МИРЭА МИСИС МИФИ МИЭМ МИЭТ МПГУ МСХА МТУСИ МФТИ МЭИ МЭСИ НИУ ВШЭ РАНХИГС РГГУ РГСУ РГУ РНИМУ РУДН РХТУ РЭУ ФА';

		$words = explode(' ', $words);
		$return = [];
		foreach($words as $word) {
			$cnt = \App\Models\Review::withStudent()->where('students.photo_extension', '<>', '')->where('teacher_reviews.score', '>=', 75)
					->where('teacher_reviews.grade', 11)->where('teacher_reviews.admin_comment_final', 'like', "%{$word}%")->count();
			$return[$word] = $cnt;
		}
		asort($return, true);
		foreach(array_reverse($return, true) as $word => $cnt) {
			echo "$word | $cnt<br>";
		}
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

    # Tutor profile page
    Route::get(Tutor::URL . '/{id}', 'PagesController@tutor')->where('id', '[0-9]+');

    # All serp pages
    Route::get('{url?}', 'PagesController@index')->where('url', '.*');
