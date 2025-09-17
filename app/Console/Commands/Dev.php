<?php

namespace App\Console\Commands;

use App\Http\Controllers\GigaController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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
            $data = File::get(__DIR__ . '/../../../private/sber_token.json');
            $data = json_decode($data, true);
            dd($data['expires_at']);
    }
}
