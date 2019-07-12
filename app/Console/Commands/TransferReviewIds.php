<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Service\Api;

class TransferReviewIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:review-ids';

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
        $ids = explode("\n", file_get_contents('review-ids.txt'));

        $bar = $this->output->createProgressBar(count($ids));
        foreach($ids as $line) {
            list($newId, $oldId) = explode("\t", $line);
            DB::statement("update pages set html = replace(html, 'id=$oldId', 'id=$newId')");
            DB::statement("update pages set html_mobile = replace(html_mobile, 'id=$oldId', 'id=$newId')");
            $bar->advance();
        }
        $bar->finish();
    }
}
