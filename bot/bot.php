<?php

function bot_sendMessage($user_id, $message) {
    $msg = get_rhyme($message, $user_id);

    vkApi_messagesSend($user_id, $msg);
}

function get_rhyme($word, $user_id){

    $lines = file("rhymes.txt");
    shuffle($lines);

    file_put_contents("rhymes.txt", "");

    $fp = fopen('rhymes.txt', 'a');

    foreach($lines as $line)
        fwrite($fp, $line);

    $rhyme = '';

    $i = strlen($word);
    if($i > 8)
        $i = 8;

    $a = true;

    while($rhyme == ''){
        $descriptor = fopen('rhymes.txt', 'r');
        while (($string = fgets($descriptor)) !== false) {
            $string = str_replace("\r\n", "", $string);

            $explode = explode(' ', $string);
            $last = end($explode);

            if ($a) {
                vkApi_messagesSend($user_id, $last);
                $a = false;
            }

            if(substr($word, -$i) == substr($string, -$i) && $word != $last){
                $rhyme = $string;
                break;
            }
        }
        fclose($descriptor);
        $i--;
    }

    if($i <= 1)
        $rhyme = 'Ничего не удалось найти(';

    return $rhyme;

}
