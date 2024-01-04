<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class KanyeWestController extends BaseController
{
    public function getRandomQuotes()
    {
        $client = new Client();
        $quotes = [];
    
        for ($i = 0; $i < 5; $i++) {
            $response = $client->request('GET', 'https://api.kanye.rest/');
            $quote = json_decode($response->getBody(), true);
            $quotes[] = $quote;
        }
    
        return $this->sendResponse($quotes, 'Successful Quotes.');
    }

    public function getNumberRandomQuotes(Request $request)
{
    $num = $request->input('number',1);
    $client = new Client();
    $quotes = [];

    for ($i = 0; $i < $num; $i++) {
        $response = $client->request('GET', 'https://api.kanye.rest/');
        $quote = json_decode($response->getBody(), true);
        $quotes[] = $quote;
    }

    return $this->sendResponse($quotes, 'Successful Quotes.');
}
}
