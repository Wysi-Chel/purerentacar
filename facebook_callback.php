<?php
session_start();
require_once 'vendor/autoload.php';

$fb = new \Facebook\Facebook([
  'app_id' => 'YOUR_FACEBOOK_APP_ID',
  'app_secret' => 'YOUR_FACEBOOK_APP_SECRET',
  'default_graph_version' => 'v9.0',
]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  echo 'No OAuth data could be obtained from the signed request.';
  exit;
}

$_SESSION['fb_access_token'] = (string) $accessToken;

try {
  $response = $fb->get('/me?fields=id,name,email', $accessToken);
  $user = $response->getGraphUser();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$_SESSION['user'] = [
    'email' => $user->getField('email'),
    'name'  => $user->getField('name'),
];
header("Location: admin-dashboard.php");
exit;
?>
