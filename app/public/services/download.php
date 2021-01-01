<?php
session_start();
require '../../private/tools/IO.php';

$code = IO::session('code');
$name = IO::session('name');

if (IO::get('code_override')) {
    $code = IO::get('code_override');
}

$wavetable_number = IO::get('number');
$filepath = "../../../data/{$code}.syx";
$syx = file_get_contents($filepath);
$len = strlen($syx);

if (IO::get('code_override')) {
    $name = substr($syx, 8, 8);
}
$name = trim($name); // Remove any trailing spaces

// Replace default position with position selected by user
$syx = substr($syx, 0, 7) . chr($wavetable_number - 1) . substr($syx, 8);

// Output sysex file
header("Content-Length: {$len}");
header("Pragma: ");
header("Cache-Control: ");
header("Content-type: application/octet-stream");
header("Content-disposition: attachment; filename={$wavetable_number}_{$name}.syx");
print $syx;
