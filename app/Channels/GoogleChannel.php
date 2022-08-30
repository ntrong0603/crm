<?php
namespace App\Channels;

use Illuminate\Notifications\Notification;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class GoogleChannel
{
    /**
     * @var Client
     */
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toGoogle($notifiable);
        if (empty($data['webhook'])) {
            $webhook = config('google.chat.webhook');
        } else {
            $webhook = $data['webhook'];
        }
        $headers = [
            'content-type' => 'application/json',
        ];
        if (empty($webhook)) {
            Log::error('Empty webhook');
        } else {
            $data = json_encode([
                    'text' => \Str::limit($data['message'], 4080, ' (...)')
                ], JSON_UNESCAPED_UNICODE);
            $request = new Request('POST', $webhook, $headers, $data);
            $response = $this->client->send($request);
            if ($response->getStatusCode() !== 200) {
                Log::error($response);
            }
        }
    }
}
