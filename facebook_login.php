<?php
session_start();
require_once 'vendor/autoload.php'; // Make sure to install Facebook SDK for PHP via Composer

$fb = new \Facebook\Facebook([
  'app_id' => 'YOUR_FACEBOOK_APP_ID',
  'app_secret' => 'YOUR_FACEBOOK_APP_SECRET',
  'default_graph_version' => 'v9.0',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Request email permission
$loginUrl = $helper->getLoginUrl('http://yourdomain.com/facebook_callback.php', $permissions);
header("Location: " . $loginUrl);
exit;
?>
