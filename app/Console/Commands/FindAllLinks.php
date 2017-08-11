<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Page;
use App\Models\Variable;
use Storage;

class FindAllLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $links = [];

        foreach(Page::all() as $page) {
            $links = array_merge($links, self::findHrefs($page->getClean('html')));
            $links = array_merge($links, self::findHrefs($page->getClean('html_mobile')));
        }

        foreach(Variable::all() as $variable) {
            $links = array_merge($links, self::findHrefs($variable->getClean('html')));
        }

        $links = array_unique($links);
        sort($links);

        Storage::put('links.txt', implode("\n", $links));

        $custom_check = ["", "================", "CUSTOM CHECK", "================"];
        $error_links  = ["================", "ERROR LINKS", "================"];

        foreach($links as $link) {
            if ($link[0] == '/' || strpos($link, 'http') !== false) {
                if ($link[strlen($link) - 1] != '/') {
                    $link = $link . '/';
                }
                $status_code = self::getStatusCode($link);
                dump($link . " | " . $status_code);
                if ($status_code != 200) {
                    $this->error("error");
                    $error_links[] = $link;
                } else {
                    $this->info("ok");
                }
            } else {
                $this->line("custom: $link");
                $custom_check[] = $link;
            }
        }

        Storage::put('link_problems.txt', implode("\n", array_merge($error_links, $custom_check)));
    }

    private static function findHrefs($html)
    {
        preg_match_all('/href=\'(.+)\'/U', $html, $m1);
        preg_match_all('/href="(.+)"/U', $html, $m2);
        return array_merge($m1[1], $m2[1]);
    }

    private static function getStatusCode($url)
    {
        $handle = curl_init('https://ege-centr.ru' . $url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        return $httpCode;
    }
}
