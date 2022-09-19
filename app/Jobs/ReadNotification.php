<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReadNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private mixed $cookie_name;
    private mixed $cookie_value;
    private mixed $seen_url;

    public function __construct($cookie, $seen_url)
    {
        $this->cookie_name = $cookie['Name'];
        $this->cookie_value = $cookie['Value'];
        $this->seen_url = $seen_url;
    }


    public function handle(): void
    {
        $client = new Client(['cookies' => true]);
        try {
            $client->request('GET', $this->seen_url, [
                'headers' => [
                    'Cookie' => "$this->cookie_name=$this->cookie_value",
                ],
                'allow_redirects' => [
                    'max' => 15,
                ],
            ]);
        } catch (GuzzleException $e) {
            throw new \RuntimeException($e);
        }
    }
}
