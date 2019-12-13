<?php

require 'class/Thumbnail-Creator.php';
/* Initiate the class with the image path */
$rez = new Thumbnail;

/* Load the image path */
$rez->image = 'images/rainbow.gif';

/* Do the resize */
$tmb = $rez->resize(300,100);

/* Get image path */
$img = $rez->source;

/* Display a clickable thumbnail */
echo '<a href="'.$img.'" target="_blank"><img src="'.$tmb.'"></a>';
