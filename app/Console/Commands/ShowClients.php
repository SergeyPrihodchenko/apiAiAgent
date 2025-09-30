<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Passport\Client;

class ShowClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:show-clients';

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
        Client::all()->each(function ($client) {
            $this->info(
                "ID: {$client->id}, " .
                "Name: {$client->name}, " .
                "Redirect: {$client->redirect}, " .
                "Personal Access: " . ($client->personal_access_client ? 'Yes' : 'No') . 
                ", Password Client: " . ($client->password_client ? 'Yes' : 'No') . 
                ", Revoked: " . ($client->revoked ? 'Yes' : 'No')
            );
        });
    }
}
