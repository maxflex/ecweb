<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Service\Api;

class MissingRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requests:missing';

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
        $requests = DB::table('request_log')->where('id', '>=', 6404)->get();

        foreach($requests as $request) {
            $request = json_decode($request->data);
            Api::exec('requests', array_merge((array)$request, [
                'branches' => [$request->branch_id],
                'grade_id' => $request->grade,
                'phones' => [
                    [
                        'phone' =>$request->phone,
                        'comment' => $request->name,
                    ],
                ]
            ]));
        }
    }

   
}
