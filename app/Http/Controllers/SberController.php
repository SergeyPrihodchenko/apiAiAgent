<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class SberController extends Controller
{
    public const SCOPE = 'GIGACHAT_API_PERS';

    static public function getToken()
    {
        $authorization = env('SBER_TOKEN');
        $clientId = env('CLIENT_ID');
        $rqUid = uniqid('', true);

        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'RqUID' => $rqUid,
            'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $authorization),
        ])->asForm()->post('https://ngw.devices.sberbank.ru:9443/api/v2/oauth', [
            'scope' => self::SCOPE,
        ]);

        $status = $response->status();
        $response = $response->json();
        
        File::put(__DIR__. '/../../../private/sber_token.json', json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        File::put(__DIR__. '/../../../private/status.json', json_encode($status, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
