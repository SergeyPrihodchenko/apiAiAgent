<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GigaController extends Controller
{
    private const NAME_MODEL = 'GigaChat-2';
    private const BASE_URL = 'http://gigachat.devices.sberbank.ru/api/v1/chat/';
    private string $TOKEN;
    private string $SCOPE;

    public function __construct()
    {
        $this->TOKEN = env('GIGA_CLIENT_TOKEN');
        $this->SCOPE = env('SCOPE');

        parent::__construct(
            self::BASE_URL,
            $this->TOKEN,
            self::NAME_MODEL
        );

        $this->setHeaders([
            'Authorization Bearer '.$this->TOKEN,
        ]);
    }

    protected function sendRequest(string $content)
    {
        $response = $this->request('completions', [
            "model" => self::NAME_MODEL,
            'scope' => $this->SCOPE,
            "messaeg" => [
                "role" => "user",
                "content" => $content
            ],
            "n" => 1,
            "stream" => false,
            "max_tokens" => 512,
            "repetition_penalty" => 1,
            "update_interval" => 0
        ]);

        $response->onError(function($res) {
            Log::error('GIGA CHAT ответил со статусом ' . $res->status());
            throw new \Exception();
        });

        if($response->ok()) {
            return $response->body();
        }
    }

    public function sendPrepareData(string $data): string
    {
        try {
            $result = $this->sendRequest($data);
            return $result;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return "Ошибка былна в {$th->getFile()} на {$th->getLine()} строке";
        }
    }

}
