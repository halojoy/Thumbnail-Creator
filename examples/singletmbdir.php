<?php

require '../class/Thumbnail-Creator.php';

$directory = '../images';
$image = 'rainbow.gif';

$rez = new Thumbnail();
$rez->directory = $directory;
$rez->image = $image;
$tmb = $rez->resize();
$img = $rez->source;

echo '<a href="'.$img.'"><img src="'.$tmb.'"></a>';
