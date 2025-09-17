<?php

namespace App\Data;

use App\Http\Controllers\SberController;
use Illuminate\Support\Facades\File;

class AccessToken
{
    public string $access_token;
    public int $expires_at;

    public function __construct()
    {
        if(!File::exists(__DIR__ . '/../../../private/sber_token.json')) {
            File::put(__DIR__ . '/../../../private/sber_token.json', json_encode([]));
            File::put(__DIR__ . '/../../../private/status.json', json_encode([]));
        }
        
        $data = File::get(__DIR__ . '/../../../private/sber_token.json');
        $data = json_decode($data, true);
        
        if(!isset($data['access_token']) || !isset($data['expires_in'])) {
            (new SberController())->getToken();
            $data = File::get(__DIR__ . '/../../../private/sber_token.json');
            $data = json_decode($data, true);
        }

        $this->access_token = $data['access_token'];
        $this->expires_at = $data['expires_in'];
    }

    private function isExpired(): bool
    {
        return time() >= $this->expires_at;
    }

    public function getToken(): string
    {
        return $this->checkAndRefreshToken();
    }

    private function checkAndRefreshToken(): string
    {
        if ($this->isExpired()) {
            (new SberController())->getToken();
            $data = File::get(__DIR__ . '/../../../private/sber_token.json');
            $data = json_decode($data, true);
            $this->access_token = $data['access_token'];
            $this->expires_at = $data['expires_in'];
        }
        return $this->access_token;
    }

}