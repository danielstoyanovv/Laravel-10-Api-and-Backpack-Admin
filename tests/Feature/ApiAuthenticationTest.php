<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ApiAuthenticationTest extends TestCase
{
    public function test_login()
    {
         $response = $this->postJson('http://127.0.0.1:8000/api/login', [
          //   "email" => "daniel.stoyanovv@gmail.com",
             "email" => "superAdmin8674@gmail.com",
             "password" => "12345678"
         ]);
        $content = json_decode($response->getContent(), true);
        var_dump($content);
        var_dump($response->status());
    }

//    public function test_logout()
//    {
//        $response = $this->postJson('http://127.0.0.1:8000/api/logout', [
//            "email" => "daniel.stoyanovv@gmail.com",
//            "password" => "12345678",
//            "_token" => csrf_token()
//        ]);
//        $content = json_decode($response->getContent(), true);
//      var_dump('logout');
//        var_dump($content);
//        var_dump($response->status());
//
//
//    }
}
