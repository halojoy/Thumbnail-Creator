<?php

require '../class/Thumbnail-Creator.php';

$directory = '../images';

$scanfiles = scandir($directory);
$allfiles = array();
foreach($scanfiles as $file) {
    if (substr($file, 0, 1) == '.')    continue;
    if (substr($file, 0, 4) == 'tmb_') continue;
    $allfiles[] = $file;
}

$rez = new Thumbnail($directory);

$count = 0;
foreach($allfiles as $filename) {
    $rez->image = $filename;
    $tmb = $rez->resize();
    $img = $rez->source;
    if ($tmb == 'unsupported') continue;
    if ($count % 8 == 0 && $count != 0) echo '<br>';
    $count++;
    echo '<a href="'.$img.'"><img src="'.$tmb.'"></a>&nbsp;';
}
