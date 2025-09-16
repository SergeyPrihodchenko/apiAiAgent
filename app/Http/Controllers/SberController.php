<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class SberController extends Controller
{
    public const SCOPE = 'GIGACHAT_API_PERS';

    static public function getToken()
    {
        $authorization = env('SBER_TKEN');
        $rqUid = uniqid('', true);

        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'RqUID' => $rqUid,
            'Authorization' => 'Basic ' . $authorization,
        ])->asForm()->post('https://ngw.devices.sberbank.ru:9443/api/v2/oauth', [
            'scope' => self::SCOPE,
        ]);

        $status = $response->status();
        $response = $response->json();
        
        File::put($_SERVER['DOCUMENT_ROOT'].'../private/sber_token.json', json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        File::put($_SERVER['DOCUMENT_ROOT'].'/../private/status.json', json_encode($status, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
