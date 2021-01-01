<?php
session_start();
require '../../private/tools/IO.php';

$WAV_file = IO::file('wav');
$frame_size = IO::post('frame_size');
$wavetable_name = IO::post('wavetable_name');
$wavetable_name = substr(trim($wavetable_name), 0, 8);

if ($WAV_file['size'] > 1050000) {
    print "WAV file too big. Maximum size is 1050000 bytes";
    exit;
}

// File metadata
$path = $WAV_file['tmp_name'];
$unique = uniqid();

// Try to convert the file into RAW
$s = move_uploaded_file($path, "../../../data/{$unique}.wav");
if (!$s) {
    print "Not moved";exit;
}
$cmd = "/usr/local/bin/serum2pro3 \"{$wavetable_name}\" 33 {$frame_size} ../../../data/{$unique}.wav";
$syx = shell_exec($cmd);

// Store sysex and remove wav
$fh = fopen("../../../data/{$unique}.syx", "w");
fwrite($fh, $syx);
fclose($fh);
unlink("../../../data/{$unique}.wav");

// Generate JSON array of sysex for eventual MIDI featur
$data = '';
for ($i = 0; $i < strlen($syx); $i++)
{
    $data .= ord(substr($syx, $i, 1)) . ",";
}
$data = trim($data, ",");

$name = str_replace(" ", "-", $wavetable_name);
IO::setSession('sysex_file', $unique);
IO::setSession('name', $name);
print "<script>window.top.window.wavetableHasUpdated([{$data}]);</script>\n{$name}/{$unique}";
exit;
