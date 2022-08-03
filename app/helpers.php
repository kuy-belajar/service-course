<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Expr\Cast\Array_;

function getUser($userId){
    $url = env("SERVICE_USER_URL")."users/".$userId;

    try {
        $response = Http::timeout(10)->get($url);
        $data = $response->json();
        $data["http_code"] = $response->getStatusCode();
        return $data;

    } catch (\Throwable $th) {
        return [
            "status" => "error",
            "http_code" => 500,
            "message" => "Service user unavailable"
        ];
    }
}

function getUserByIds($userIds = []){
    $url = env("SERVICE_USER_URL")."users/";

    try {
        if (count($userIds) === 0) {
            return [
            "status" => "Success",
            "http_code" => 200,
            "data" => []
            ];
        }

        $response = Http::timeout(10)->get($url);
        $data = $response->json();
        $data["http_code"] = $response->getStatusCode();
        return $data;

    } catch (\Throwable $th) {
        return [
            "status" => "error",
            "http_code" => 500,
            "message" => "Service user unavailable"
        ];
    }
} 