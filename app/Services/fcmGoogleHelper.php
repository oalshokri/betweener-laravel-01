<?php
namespace app\Services;

use Google\Client as GClient;
use Google\Service\FirebaseCloudMessaging;
use Google_Exception;

class FcmGoogleHelper
{
    public static function configureClient()
    {
//        $path = config('fcm_config.fcm_json_path');
        $path = base_path('betweener-72ad3-firebase-adminsdk-qhr9e-8ed3a62fab.json');

        $client = new GClient();
        try {
            $client->setAuthConfig($path);
            $client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);

            $accessToken = FcmGoogleHelper::generateToken($client);

            $client->setAccessToken($accessToken);

            $oauthToken = $accessToken["access_token"];

            return $oauthToken;
        } catch (Google_Exception $e) {
            return $e;
        }
    }

    private static function generateToken($client)
    {
        $client->fetchAccessTokenWithAssertion();
        $accessToken = $client->getAccessToken();

        return $accessToken;
    }
}
