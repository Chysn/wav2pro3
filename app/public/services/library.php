<?php
session_start();
require '../../private/tools/IO.php';

$table = "<table id=\"shared_wavetable_table\" class=\"table table-condensed table-hover table-striped\">\n";

$sql = "SELECT * FROM `wavetable`
        WHERE `shared` = 1
        ORDER BY `share_time` DESC";
$res = IO::query($sql);
if ($res) {
    while ($row = $res->fetch_assoc())
    {
        $id = $row['id'];
        $code = $row['code'];
        $name = "<span id=\"n{$code}\">{$row['name']}</span>";
        $time = date('M d, Y', strtotime($row['share_time']));
        $link = "<span class=\"glyphicon glyphicon-link\"></span> <a href=\"http://{$_SERVER['SERVER_NAME']}/?{$code}\">{$_SERVER['SERVER_NAME']}/?{$code}</a>";
        if ($row['signature']) {$name .= " <span class=\"sig\">@{$row['signature']}</span>";}
        $button = "<button class=\"btn btn-sm btn-primary start\" onclick=\"$('#download_name').html('Download SysEx');overrideDownload('{$code}');\" title=\"Download SysEx\" data-toggle=\"modal\" data-target=\"#sysex_send\"><span class=\"glyphicon glyphicon-download\"></span> </button>";
        $button .= " <button class=\"btn btn-sm btn-primary start\" onclick=\"$('#wt{$code}').toggle();\" title=\"Info\"><span class=\"glyphicon glyphicon-info-sign\"></span> </button>";
        $table .= "<tr><td class=\"wavetable_name col-md-9 col-lg-9\">{$name}\n<div style=\"display:none\" id=\"wt{$code}\" class=\"description\">{$row['description']}<br/><br/>{$link}</br></br><i>Shared {$time}</i></div></td>";
        $table .= "<td class=\"wavetable_name col-md-3 col-lg-3\">{$button}</td></tr>";
    }
}

$table .= "</table>";
print $table;
exit;
