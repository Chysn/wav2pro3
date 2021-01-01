<?php
session_start();
require '../../private/tools/IO.php';

$unique = IO::session('sysex_file');
$name = IO::session('name');
$wavetable_number = IO::get('number');
$filepath = "../../../data/{$unique}.syx";
$syx = file_get_contents($filepath);
$len = strlen($syx);

// Replace default position with position selected by user
$syx = substr($syx, 0, 7) . chr($wavetable_number - 1) . substr($syx, 8);

// Output sysex file
header("Content-Length: {$len}");
header("Pragma: ");
header("Cache-Control: ");
header("Content-type: application/octet-stream");
header("Content-disposition: attachment; filename={$wavetable_number}_{$name}.syx");
print $syx;
