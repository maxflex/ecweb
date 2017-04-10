<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CvStore;
use App\Http\Controllers\Controller;
use App\Models\Service\Api;
use App\Models\Service\Sms;
use Illuminate\Support\Facades\Redis;

class CvController extends Controller
{
    const REDIS_CV_COUNT     = 'ecweb:cv:count';
    const REDIS_CV_BLOCKED   = 'ecweb:cv:blocked';
    const CV_MAX_COUNT       = 200;
    const CV_FLUSH_TIME      = 3600 * 24;

    public function store(CvStore $request)
    {
        $ecweb_cv_count = intval(Redis::get(self::REDIS_CV_COUNT));
        // не более 100 за последние 24 часа
        if ($ecweb_cv_count < self::CV_MAX_COUNT) {
            Api::exec('tutorNew', $request->input());
            Redis::incr(self::REDIS_CV_COUNT);
            Redis::expire(self::REDIS_CV_COUNT, self::CV_FLUSH_TIME);
        } else {
            if ($ecweb_cv_count == self::CV_MAX_COUNT) {
                Sms::sendToAdmins('Внимание! DDoS на анкеты');
            }
            Redis::sadd(self::REDIS_CV_BLOCKED, json_encode($request->input()));
            return abort(403);
        }
    }
}
