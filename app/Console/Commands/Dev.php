<?php

namespace App\Console\Commands;

use App\Http\Controllers\GigaController;
use Illuminate\Console\Command;

class Dev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $g = new GigaController();

        $response = $g->sendPrepareData(
            'Это мое первое обращение по api'
        );

        dd($response);
    }
}
