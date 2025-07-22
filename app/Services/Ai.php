<?php

namespace App\Services;

use Curl\Curl;

class Ai
{
    private $apiUrl, $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('OPENROUTER_API_URL', 'https://openrouter.ai/api/v1');
        $this->apiKey = env('OPENROUTER_API_KEY');
    }

    public function translateToEnglish($text)
    {
        $curl = new Curl();
        $curl->setHeader('Authorization', 'Bearer ' . $this->apiKey);
        $curl->setHeader('Content-Type', 'application/json');

        $data = [
            'model' => 'deepseek/deepseek-r1-0528:free',
            'prompt' => 'Terjemahkan langsung ke dalam bahasa Inggris: ' . $text,
            // 'max_tokens' => 100,
            // 'temperature' => 0.7,
        ];

        // dd($data);;

        $curl->post($this->apiUrl . '/completions', json_encode($data));

        // dd($curl->response);

        if ($curl->error) {
            throw new \Exception('Error: ' . $curl->errorMessage);
        }

        return $curl->response->choices[0]->text ?? '';
    }

}