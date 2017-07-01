<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class StreamController extends Controller
{
    public function store(Request $request)
    {
        if (strpos(@$_SERVER['HTTP_X_REAL_IP'], '213.184.130.') === 0) {
            return;
        }

        $request->merge(['mobile' => isMobile()]);
        egecrm('stream')->insert($request->all());
    }
}
