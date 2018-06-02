<?php

define('VK_API_VERSION', '5.50'); //Используемая версия API
define('VK_API_ENDPOINT', 'https://api.vk.com/method/');

function vkApi_messagesSend($peer_id, $message) {
    return _vkApi_call('messages.send', array(
        'peer_id' => $peer_id,
        'message' => $message,
    ));
}

function vkApi_usersGet($user_id) {
    return _vkApi_call('users.get', array(
        'user_id' => $user_id,
    ));
}

function _vkApi_call($method, $params = array()) {
  $params['access_token'] = VK_API_ACCESS_TOKEN;
  $params['v'] = VK_API_VERSION;

  $query = http_build_query($params);
  $url = VK_API_ENDPOINT.$method.'?'.$query;

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $json = curl_exec($curl);

  curl_close($curl);

  $response = json_decode($json, true);

  return $response['response'];
}

