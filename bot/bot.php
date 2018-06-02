<?php

function bot_sendMessage($user_id, $message) {
  $users_get_response = vkApi_usersGet($user_id);
  $user = array_pop($users_get_response);
  $msg = "Привет, {$user->first_name}, {$message}!";

  vkApi_messagesSend($user_id, $msg);
}
