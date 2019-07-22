<?php

require '../class/Thumbnail-Creator.php';

$image = 'sonjaedit.jpg';

/* Initiate the class */
$rez = new Thumbnail();

/* Load the image */
$rez->image = $image;

/* Do the resize */
$tmb = $rez->resize();

/* Display a clickable thumbnail */
echo '<a href="'.$image.'"><img src="'.$tmb.'"></a>';
