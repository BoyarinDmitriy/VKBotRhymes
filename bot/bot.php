<?php

function bot_sendMessage($user_id, $message) {
    $msg = get_rhyme($message);

    vkApi_messagesSend($user_id, $msg);
}

function get_rhyme($word){

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

    while($rhyme == ''){
        $descriptor = fopen('rhymes.txt', 'r');
        while (($string = fgets($descriptor)) !== false) {
            $string = str_replace("\r\n", "", $string);
            $explode = explode(' ', $string);
            $last = end($explode);
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
