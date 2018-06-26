<?php

use Illuminate\Foundation\Inspiring;
use App\Models\Service\Api;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('eji', function() {
	$data = DB::table('request_log')->where('created_at', '>=', '2018-06-01 00:00:00')->groupBy('data')->get();
	
	$bar = $this->output->createProgressBar(count($data));
	foreach($data as $d) {
		$request = json_decode($d->data);
		$request->date = $d->created_at;
		if (isset($request->branch_id)) {
			$request->branches = [$request->branch_id];	
		}
		Api::exec('AddRequest', (array)$request);
		$bar->advance();
	}
	$bar->finish();
});
