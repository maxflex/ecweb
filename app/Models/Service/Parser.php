<?php
    namespace App\Models\Service;
    use App\Models\Program;
    use App\Models\Variable;
    use App\Models\Photo;
    use App\Models\Review;
    use App\Models\Page;
    use App\Models\Tutor;

    /**
     * Parser
     */
    class Parser
    {
        const START_VAR = '[';
        const END_VAR   = ']';

        public static function compileVars($html)
        {
            preg_match_all('#\\' . static::interpolate('[\S]+\\') . '#', $html, $matches);
            $vars = $matches[0];
            foreach ($vars as $var) {
                $var = trim($var, static::interpolate());
                static::compileFunctions($html, $var);
                $query = Variable::findByName($var);
                if ($query->exists()) {
                    $variable = $query->first();
                    static::replace($html, $var, $variable->html);
                }
            }
            return $html;
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
                        $replacement = Tutor::bySubject(...$args)->toJson();
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
                        $ids = explode(',', $args[0]);
                        $replacement = Photo::parse($ids);
                        break;
                    case 'program':
                        $replacement = view('pages.program', ['program' => Program::find($args[0])]);
                        break;
                    case 'map':
                        $replacement = view('address.map', ['branch' => strtoupper($args[0])]);
                        break;
                    case 'address-info':
                        $replacement = view('address.info', ['branch' => strtoupper($args[0])]);
                        break;
                    case 'count':
                        $type = array_shift($args);
                        switch($type) {
                            case 'tutors':
                                $replacement = Tutor::count(...$args);
                                break;
                            case 'reviews':
                                $replacement = Review::count();
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
                if ($page->{$field}) {
                    static::replace($html, $var, $page->{$field});
                }
            }
            return $html;
        }

        /**
         * Компилировать страницу препода
         */
        public static function compileProgram($id, &$html)
        {
            if ($program = Program::find($id)) {
                static::replace($html, 'current_program', $program->toJson());
            }
        }

        public static function compileFaq(&$html) {
            static::replace($html, 'faq', view('pages.faq'));
        }
        /**
         * Компилировать страницу препода
         */
        public static function compileTutor($id, &$html)
        {
            static::replace($html, 'current_tutor', Tutor::selectDefault()->whereId($id)->first()->toJson());
        }

        /**
         * Компилировать сео-страницу
         * [seo_text_top] или [seo_text_bottom] в зависимости от $page->seo_desktop
         */
        public static function compileSeo($page, &$html)
        {
            if ($page->seo_desktop) {
                static::replace($html, 'seo_text_top', "<div class='seo-text-top'>" . $page->getClean('html') . "</div>");
                static::replace($html, 'seo_text_bottom', '');
            } else {
                static::replace($html, 'seo_text_top', '');
                static::replace($html, 'seo_text_bottom', "<div class='seo-text-bottom'>" . $page->getClean('html') . "</div>");
            }
            static::replace($html, 'useful', view('blocks.useful', compact('page')));
            // detect page refresh

            static::replace($html, 'page_was_refreshed', (int)(
                (isset($_SESSION['page_was_refreshed']) || (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0') || $page->isMainSerp())
            ));
            unset($_SESSION['page_was_refreshed']);
        }

        public static function interpolate($text = '')
        {
            return self::START_VAR . $text . self::END_VAR;
        }

        /**
         * Произвести замену переменной в html
         */
        public static function replace(&$html, $var, $replacement)
        {
            $html = str_replace(static::interpolate($var), $replacement, $html);
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
