<?php

require '../class/Thumbnail-Creator.php';

$image = 'sonjaedit.jpg';

$rez = new Thumbnail();
$rez->image = $image;
$tmb = $rez->resize();

echo '<a href="'.$image.'"><img src="'.$tmb.'"></a>';
