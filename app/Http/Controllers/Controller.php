<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class Controller
{

    protected Http $http;
    protected string $url;
    protected string $authorizationToken;
    protected string $nameModel;
    protected array $headers;

    public function __construct(
        $url,
        $authorizationToken,
        $nameModel
    )
    {
        $this->http = new Http();
        $this->url = $url;    
        $this->authorizationToken = $authorizationToken;
        $this->nameModel = $nameModel;
    }

    public function request(string $action, array $data): Response
    {
        $url = $this->preparedUrl($action);
        $response = $this->http::
        withHeaders(
            $this->headers
        )
        ->post(
            $url,
            $data
        );

        return $response;
    }

    protected function prepareAction(string $action): string
    {
        if($action[0] == '/') {
            return substr($action, 0, 1);
        }

        return $action;
    }

    protected function preparedUrl($action): string
    {
        if($this->url[strlen($this->url) - 1] == '/') {
            return $this->url . $this->prepareAction($action);
        } else {
            return $this->url . '/' . $this->prepareAction($action);
        }
    }

    protected function setHeaders(string|array $headers): void
    {
        if(is_array($headers)) {
            $this->headers = $headers;
        }

        if(is_string($headers)) {
            $this->headers = [$headers];
        }

    }
}