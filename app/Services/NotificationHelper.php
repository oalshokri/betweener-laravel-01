<?php
namespace app\Services;


use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class NotificationHelper {
    public static function sendNotification($data): JsonResponse
    {
        $token = FcmGoogleHelper::configureClient();
        $url = 'https://fcm.googleapis.com/v1/projects/betweener-72ad3/messages:send';

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' =>  'application/json',
        ];

        $client = new Client();

        try {
            $request = $client->post($url, [
                "headers" => $headers,
                "body" => json_encode($data),
            ]);

            Log::info("[Notification] SENT");

            $response = $request->getBody();

            return response()->json([
                'response' => $response,
            ],$request->getStatusCode());

        } catch (Exception $e) {
            Log::error("[Notification] ERROR", [$e->getMessage()]);

            return response()->json([
                'error' => $e,
            ],400);
        }
    }
}
