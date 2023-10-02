<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ApiTokensTest  extends TestCase
{
    public function test_token()
    {
         $response = $this->postJson('http://127.0.0.1:8000/api/token', [
//            "email" => "daniel.stoyanovv@gmail.com",
          //   "email" => "superAdmin8674@gmail.com",
             'email' => 'daniel@gmail.com',
             "password" => "12345678",
//             "_token" => csrf_token()
         ]);
        $content = json_decode($response->getContent(), true);
        echo "<pre>";
        var_dump($content);
        var_dump($response->status());
    }
}
