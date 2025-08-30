<?php

namespace App\Services;

class Wablas
{
    private $domain;
    private $token;
    private $secretKey;

    function __construct()
    {
        $this->domain = env('WABLAS_DOMAIN');
        $this->token = env('WABLAS_TOKEN');
        $this->secretKey = env('WABLAS_SECRET_KEY');
    }

    function sendText($phone, $message)
    {
        if(!empty($this->domain) && !empty($this->token)){
            $curl = curl_init();
            $data = [
                'phone' => $phone,
                'message' => $message,
                'secret' => false, // or true
                'priority' => false, // or true
            ];

            curl_setopt($curl, CURLOPT_HTTPHEADER,
                array(
                    "Authorization: $this->token.$this->secretKey",
                )
            );
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($curl, CURLOPT_URL, $this->domain."/api/send-message");
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_exec($curl);
            curl_close($curl);
        }
    }
}
