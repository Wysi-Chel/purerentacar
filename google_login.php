<?php
session_start();
require_once 'vendor/autoload.php'; 

$client = new Google_Client();
$client->setClientId('512750139884-b2j0vkka61rqcba3gsjku83ulqnqkmmh.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/purerentacar/google_callback.php');
$client->addScope("email");
$client->addScope("profile");

$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit;
?>
