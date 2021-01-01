<?php
session_start();
require '../../private/tools/IO.php';

$WAV_file = IO::file('wav');
$frame_size = IO::post('frame_size');
$wavetable_name = IO::post('wavetable_name');
$wavetable_name = substr(trim($wavetable_name), 0, 8);
$name = str_replace(" ", "-", $wavetable_name);

if ($WAV_file['size'] > 1050000) {
    print "<script>window.top.window.wavetableTooBig();</script>\n";
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
$code = md5($syx); // Use MD5 as filename to avoid duplication

// Remove wav
unlink("../../../data/{$unique}.wav");

// Generate JSON array of sysex for eventual MIDI featur
$data = '';
for ($i = 0; $i < strlen($syx); $i++)
{
    $data .= ord(substr($syx, $i, 1)) . ",";
}
$data = trim($data, ",");

// Add to My Wavetables session and file system
$syx_path = "../../../data/{$code}.syx";

$fh = fopen($syx_path, "w");
fwrite($fh, $syx);
fclose($fh);

$my_wavetables = IO::session('my_wavetables');
if (!is_array($my_wavetables)) {$my_wavetables = array();}
$my_wavetables[] = array('code' => $code, 'name' => $name);
IO::setSession('my_wavetables', $my_wavetables);
IO::setSession('code', $code);
IO::setSession('name', $name);
print "<script>window.top.window.wavetableHasUpdated([{$data}]);</script>\n";

exit;
