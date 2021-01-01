<?php
session_start();
require '../../private/tools/IO.php';

$code = IO::session('code');
if ($code and file_exists("../../../data/{$code}.syx")) {
    $description = IO::get('desc');
    $signature = IO::get('sig');
    $name = IO::session('name');
    IO::sanitize($description);
    IO::sanitize($signature);
    IO::sanitize($name);

    $sql = "INSERT INTO `wavetable` (`share_time`, `name`, `description`, `signature`, `code`, `shared`)
            VALUES (NOW(), '{$name}', '{$description}', '{$signature}', '{$code}', 1)";
IO::query($sql);


}