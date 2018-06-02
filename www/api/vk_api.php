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

function vkApi_docsGetMessagesUploadServer($peer_id, $type) {
    return _vkApi_call('docs.getMessagesUploadServer', array(
        'peer_id' => $peer_id,
        'type' => $type,
    ));
}

function vkApi_docsSave($file, $title) {
    return _vkApi_call('docs.save', array(
        'file' => $file,
        'title' => $title,
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
  $error = curl_error($curl);
  if ($error) {
    print('_vkApi_call Error');
  }

  curl_close($curl);

  $response = json_decode($json, true);
  if (!$response || !isset($response['response'])) {
      print('_vkApi_call Error');
  }

  return $response['response'];
}

function vkApi_upload($url, $file_name) {
  if (!file_exists($file_name)) {
      print('File is note exist');
  }

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, array('file' => new CURLfile($file_name)));
  $json = curl_exec($curl);
  $error = curl_error($curl);
  if ($error) {
      print('vkApi_upload Error');
  }

  curl_close($curl);

  $response = json_decode($json, true);
  if (!$response) {
    print('Invalid response');
  }

  return $response;
}
