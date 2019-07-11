<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Service\Api;
use App\Http\Requests\RequestStore;
use App\Service\Limiter;
use Illuminate\Support\Facades\Redis;
use DB;

class RequestsController extends Controller
{
    public function store(RequestStore $request)
    {
        $request->merge([
            'google_id' => $this->getGoogleId()
        ]);

        DB::table('request_log')->insert([
            'data' => json_encode($request->all())
        ]);
        return Limiter::run('request', 24, 200, function() use ($request) {
            if (isExperiment()) {
                $request->merge(['comment' => $request->comment . ' (цена в месяц)']);
            }
            Api::exec('requests', array_merge($request->input(), [
                'branches' => [$request->branch_id],
                'grade_id' => $request->grade,
                'phones' => [
                    [
                        'phone' =>$request->phone,
                        'comment' => $request->name,
                    ],
                ]
            ]));
        }, function() use ($request) {
            Redis::sadd('ecweb:request:blocked', json_encode($request->input()));
        }, 'Внимание! DDoS на заявки');
    }

    private function getGoogleId()
    {
        if (! isset($_COOKIE['_ga'])) {
            return null;
        }
        $parts = explode('.', $_COOKIE['_ga']);
        return "{$parts[2]}.{$parts[3]}";
     }
}
