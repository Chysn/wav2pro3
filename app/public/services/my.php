<?php
session_start();
require '../../private/tools/IO.php';

$my_wavetables = IO::session('my_wavetables');
if (!is_array($my_wavetables)) {$my_wavetables = array();}

$table = "<table class=\"table table-condensed table-hover table-striped\">\n";
$md5 = array();
foreach ($my_wavetables as $entry)
{
    $name = $entry['name'];
    $code = $entry['code'];
    if ($name and $code and !isset($md5[$code])) {
        if (file_exists("../../../data/{$code}.syx")) {
            $button = "<button class=\"btn btn-sm btn-primary start\" onclick=\"overrideDownload('{$code}');\" title=\"Download SysEx\" data-toggle=\"modal\" data-target=\"#sysex_send\"><span class=\"glyphicon glyphicon-download\"></span> </button>";
            $table .= "<tr><td class=\"col-md-9 col-lg-9\">{$name}</td><td class=\"col-md-3 col-lg-3\">{$button}</td></tr>\n";
            $md5[$code] = true;
        }
    }
}
$table .= "</table>";
print $table;
exit;
