<?php

require 'class/Thumbnail-Creator.php';

$rez = new Thumbnail;
$img = 'images/rainbow.gif';

/* Resize. Submit imagepath, thumb maxwidth, maxheight */
$tmb = $rez->resize($img, 300, 150);

/* Display a clickable thumbnail */
echo '<a href="'.$img.'" target="_blank"><img src="'.$tmb.'"></a>';
