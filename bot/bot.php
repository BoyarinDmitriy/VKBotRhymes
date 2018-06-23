<?php

function bot_sendMessage($user_id, $message) {
    $msg = get_rhyme($message);
    vkApi_messagesSend($user_id, $msg);
}

function get_rhyme($word) {
    $lines = file("rhymes.txt");
    $acceptable_lines = get_acceptable_lines($word, $lines);
    $acceptable_rhymes = get_acceptable_rhymes($word, $acceptable_lines);
    return !empty($acceptable_rhymes) ? $acceptable_rhymes[array_rand($acceptable_rhymes)] : 'Ничего не удалось найти';
}

function get_acceptable_lines($word, $lines) {
    $acceptable_lines = array();
    foreach($lines as $line) {
        $line = str_replace(PHP_EOL, '', $line);
        $words = explode(' ', $line);
        $last_word = end($words);
        if(substr($last_word, -2) == substr($word, -2)) {
            array_push($acceptable_lines, $line);
        }
    }
    return $acceptable_lines;
}

function get_acceptable_rhymes($word, $acceptable_lines) {
    $acceptable_rhymes = array();
    $len = mb_strlen($word, 'utf-8') - 1;
    $isRhymesWereFound = false;
    while ($len >= 0 && !$isRhymesWereFound) {
        foreach ($acceptable_lines as $line) {
            $words_in_line = explode(' ', $line);
            $last_word = end($words_in_line);
            $sub_word = mb_substr($word, -$len, null, 'utf-8');
            $sub_last_word = mb_substr($last_word, -$len, null, 'utf-8');
            if ($sub_word == $sub_last_word && $word != $last_word) {
                array_push($acceptable_rhymes, $line);
                $isRhymesWereFound = true;
            }
        }
        $len--;
    }
    return $acceptable_rhymes;
}