<?php
session_start();
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('512750139884-b2j0vkka61rqcba3gsjku83ulqnqkmmh.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/google_callback.php');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if(!isset($token["error"])){
        $client->setAccessToken($token['access_token']);
        $oauth = new Google_Service_Oauth2($client);
        $profile = $oauth->userinfo->get();
        
        $_SESSION['user'] = [
            'email'   => $profile->email,
            'name'    => $profile->name,
            'picture' => $profile->picture
        ];
        header("Location: admin-dashboard.html");
        exit;
    } else {
        echo "Error during Google login.";
    }
}
?>
