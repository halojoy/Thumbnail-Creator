<?php

require '../class/Thumbnail-Creator.php';

$directory = '../images';
$image = 'rainbow.gif';

/* Initiate the class with target directory */
$rez = new Thumbnail($directory);

/* Load the image */
$rez->image = $image;

/* Do the resize */
$tmb = $rez->resize();

/* Get image path */
$img = $rez->source;

/* Display a clickable thumbnail */
echo '<a href="'.$img.'"><img src="'.$tmb.'"></a>';
