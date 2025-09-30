<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Passport\Client;

class RevokeClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:revoke-client {name}';

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
        $name = $this->argument('name');
        $result = $this->revokeClientByName($name);
        if(!$result) {
            return;
        }
        $this->info("Client with name '{$name}' has been revoked.");
    }

    private function revokeClientByName($name)
    {
        $id = Client::where('name', $name)->value('id');
        if(!$id) {
            $this->error("Client with name '{$name}' not found.");
            return false;
        }
        $client = Client::find($id); // или findOrFail('CLIENT_ID')
        $client->revoked = true;
        $client->save();
        return true;
    }
}
