<?php

namespace App\Console\Commands;

use App\Http\Controllers\SberController;
use Illuminate\Console\Command;

class GetTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-token-command';

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
        app(SberController::class)->getToken();
        return 0;
    }
}
