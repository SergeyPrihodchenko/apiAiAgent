<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class SberController extends Controller
{
    public const SCOPE = 'GIGACHAT_API_PERS';

    public function getToken()
    {
        $clientSecret = env('CLIENT_SECRET');
        $clientId = env('CLIENT_ID');
        $rqUid = Uuid::uuid4()->toString();

        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'RqUID' => $rqUid,
            'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
        ])->asForm()->post('https://ngw.devices.sberbank.ru:9443/api/v2/oauth', [
            'scope' => self::SCOPE,
        ]);

        $status = $response->status();
        $response = $response->json();
        
        File::put(__DIR__. '/../../../private/sber_token.json', json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        File::put(__DIR__. '/../../../private/status.json', json_encode($status, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function sendPrompt(Request|string $request)
    {
        if($request instanceof Request) {
            $prompt = $request->input('prompt', null);
            if (!$prompt) {
                return ['error' => 'Prompt is required'];
            }
        } else {
            $prompt = $request;
        }

        dd($prompt);

        $accessToken = (new \App\Http\Data\AccessToken())->getToken();
        $rqUid = Uuid::uuid4()->toString();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
            'RqUID' => $rqUid,
        ])->post('https://api.gigachat.sber.ru/v1/chat/completions', [
            'messages' => [
            [
                'role' => 'system',
                'content' => 'Ты эксперт по кулинарии, можешь подсказывать рецепты блюд.'
            ],
            [
                'role' => 'user',
                'content' => $prompt
            ]
            ],
            'model' => 'GigaChat-2-Max'
        ]);

        return response()->json($response->json(), $response->status());
    }

    public function sendFileWithPrompt(Request $request)
    {
        $fileResponse = $this->sendFile();

        if (isset($fileResponse['error'])) {
            return $fileResponse;
        }

        if (!isset($fileResponse['id'])) {
            return ['error' => 'File upload failed'];
        }

        $prompt = $request->input('prompt', null);

        if (!$prompt) {
            return ['error' => 'Prompt is required'];
        }

        $fileId = $fileResponse['id'];
        $fullPrompt = $prompt . "\n[File ID: " . $fileId . "]";

        return $this->sendPrompt($fullPrompt);
    }

    private function sendFile()
    {
        if(Storage::fileExists('/data/data.txt')) {
            $filePath = Storage::path('/data/data.txt');
        } else {
            return ['error' => 'File not found'];
        }

        $accessToken = (new \App\Http\Data\AccessToken())->getToken();

        $fileContent = File::get($filePath);

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->attach('file', $fileContent, 'data.txt')
            ->asMultipart()
            ->post('https://gigachat.devices.sberbank.ru/api/v1/files', [
            ['name' => 'purpose', 'contents' => 'general'],
        ]);

        return $response->json();
    }
}
