<?php
    namespace App\Models\Service;
    use App\Models\Program;
    use App\Models\Variable;
    use App\Models\Photo;
    use App\Models\Review;
    use App\Models\Page;
    use App\Models\Tutor;
    use DB;

    /**
     * Parser
     */
    class Parser
    {
        // обёртка для переменных
        const START_VAR = '[';
        const END_VAR   = ']';

        // обёртка для высчитываемых переменных типа [map|focus=trg]
        const START_VAR_CALC = '{';
        const END_VAR_CALC   = '}';

        public static function compileVars($html)
        {
            preg_match_all('#\\' . static::interpolate('((?>[^\[\]]+)|(?R))*\\') . '#U', $html, $matches);
            $vars = $matches[0];
            foreach ($vars as $var) {
                $var = trim($var, static::interpolate());
                // если в переменной есть знак =, то воспроизводить значения
                if (strpos($var, '=')) {
                    static::replace($html, $var, static::compileValues($var));
                } else {
                    $variable = Variable::findByName($var)->first();
                    if ($variable) {
                        static::replace($html, $var, $variable->html);
                    }
                }
            }
            // preg_match_all('#\\' . static::interpolate('[\S]+\\', self::START_VAR_CALC, self::END_VAR_CALC) . '#', $html, $matches);

            // compile functions after values & vars
            preg_match_all('#\\' . static::interpolate('[\S]+\\') . '#', $html, $matches);
            $vars = $matches[0];
            foreach($vars as $var) {
                // если функция содержит внутри {} – пропускать
                if (strpos($var, '{')) {
                    continue;
                }
                $var = trim($var, static::interpolate());
                static::compileFunctions($html, $var);
            }
            return $html;
        }

        /**
         * Компилировать значения типа [map|center=95,23|branch=trg|deadline=[deadline]]
         */
        public static function compileValues($var_string)
        {
            // map|a=1|b=2
            // tutor|{subject}|{count}
            $values = explode('|', $var_string);
            // первая часть – название переменной
            $html = Variable::findByName($values[0])->first()->html;
            // $html = DB::table('variables')->whereName($values[0])->value('html');

            // если переменная нашлась
            if ($html !== null) {
                // убираем название переменной из массива
                array_shift($values);

                foreach($values as $value) {
                    // разбиваем a=1
                    list($var_name, $var_val) = explode('=', $value);

                    // если $var_val – это переменная
                    if (@$var_val[0] == self::START_VAR) {
                        // заменяем на значение переменной, если таковая найдена
                        $variable = Variable::findByName(trim($var_val, self::START_VAR . self::END_VAR))->first();
                        if ($variable) {
                            static::replace($html, $var_name, $variable->html, self::START_VAR_CALC, self::END_VAR_CALC);
                        }
                    } else {
                    // иначе просто заменяем на значение после =
                        static::replace($html, $var_name, $var_val, self::START_VAR_CALC, self::END_VAR_CALC);
                    }
                }

                return $html;
            } else {
                // если переменная не нашлась, возвращаем неизменную $var_string
                return $var_string;
            }
        }

        /**
         * Компилирует функции типа [factory|subjects|name]
         */
        public static function compileFunctions(&$html, $var)
        {
            $replacement = '';
            $args = explode('|', $var);
            if (count($args) > 1) {
                $function_name = $args[0];
                array_shift($args);
                switch ($function_name) {
                    case 'mobile':
                        $replacement = isMobile($args[0] == 'raw');
                        break;
                    case 'factory':
                        $replacement = fact(...$args);
                        break;
                    case 'tutor':
                        $replacement = Tutor::find($args[0])->toJson();
                        break;
                    case 'tutors':
                        // поиск по ID
                        if (strpos($args[0], ',') !== false) {
                            $replacement = Tutor::whereIn('id', explode(',', $args[0]))->get()->toJson();
                        } else {
                            $replacement = Tutor::bySubject(...$args)->toJson();
                        }
                        break;
                    case 'filesize':
                        $replacement = getSize($args[0], 0);
                        break;
                    case 'reviews':
                        if ($args[0] === 'random') {
                            $replacement = Review::get(1, true)->toJson();
                        } else {
                            $replacement = Review::get(...$args)->toJson();
                        }
                        break;
                    case 'const':
                        $replacement = Factory::constant($args[0]);
                        break;
                    case 'session':
                        $replacement = json_encode(@$_SESSION[$args[0]]);
                        break;
                    case 'param':
                        $replacement = json_encode(@$_GET[$args[0]]);
                        break;
                    case 'year':
                        $replacement = date('Y');
                        break;
                    case 'subject':
                        $replacement = json_encode(Page::getSubjectRoutes());
                        break;
                    case 'link':
                        // получить ссылку либо по [link|id_раздела] или по [link|math]
                        $replacement = is_numeric($args[0]) ? Page::getUrl($args[0]) : Page::getSubjectUrl($args[0]);
                        break;
                    case 'gallery':
                        if ($args[0] == 'all') {
                            $replacement = Photo::parse(Photo::pluck('id'));
                        } else {
                            $ids = explode(',', $args[0]);
                            $replacement = Photo::parse($ids);
                        }
                        break;
                    case 'photo':
                        $replacement = Photo::find($args[0])->url;
                        break;
                    case 'program':
                        $replacement = view('pages.program', ['program' => Program::find($args[0])]);
                        break;
                    case 'price':
                        $replacement = \App\Service\Price::parse(...$args);
                        break;
                    case 'faq':
                        $replacement = \App\Models\FaqGroup::getAll()->toJson();
                        break;
                    case 'count':
                        $type = array_shift($args);
                        switch($type) {
                            case 'tutors':
                                if (@$args[0] == 'egerep') {
                                    $replacement = egerep('tutors')->where('public_desc', '!=', '')->count();
                                } else {
                                    $replacement = Tutor::count(...$args);
                                }
                                break;
                            case 'reviews':
                                if (@$args[0] == 'egerep') {
                                    $replacement = egerep('reviews')->where('state', 'published')->count();
                                } else {
                                    $replacement = Review::count();
                                }
                                break;
                            case 'subjects':
                                $counts = [];
                                foreach(range(1, 11) as $subject_id) {
                                    $counts[$subject_id] = Tutor::whereSubject($subject_id)->count();
                                }
                                $replacement = json_encode($counts);
                                break;
                        }
                    break;
                }
                static::replace($html, $var, $replacement);
            }
        }

        /**
         * Компиляция значений страницы
         * значения типа [page.h1]
         */
        public static function compilePage($page, $html)
        {
            preg_match_all('#\\' . static::interpolate('page\.[\S]+\\') . '#', $html, $matches);
            $vars = $matches[0];
            foreach ($vars as $var) {
                $var = trim($var, static::interpolate());
                $field = explode('.', $var)[1];
                // if ($page->{$field}) {
                    static::replace($html, $var, @$page->{$field});
                // }
            }
            return $html;
        }

        /**
         * Компилировать страницу препода
         */
        public static function compileTutor($id, &$html)
        {
            $tutor = Tutor::selectDefault()->whereId($id)->first();
            static::replace($html, 'subject', $tutor->subjects_string);
            static::replace($html, 'tutor-name', implode(' ', [$tutor->last_name, $tutor->first_name, $tutor->middle_name]));
            static::replace($html, 'current_tutor', $tutor->toJson());
        }

        public static function interpolate($text = '', $start = null, $end = null)
        {
            if (! $start) {
                $start = self::START_VAR;
            }
            if (! $end) {
                $end = self::END_VAR;
            }
            return $start . $text . $end;
        }

        /**
         * Произвести замену переменной в html
         */
        public static function replace(&$html, $var, $replacement, $start = null, $end = null)
        {
            $html = str_replace(static::interpolate($var, $start, $end), $replacement, $html);
        }

        /**
         * Заменить переносы строки и двойные пробелы,
         * а так же пробел перед запятыми, пробелы на краях
         */
        private static function _cleanString($text)
        {
            return str_replace(' ,', ',',
                trim(
                    preg_replace('!\s+!', ' ',
                        str_replace(PHP_EOL, ' ', $text)
                    )
                )
            );
        }
    }
