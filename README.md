### Thumbnail-Creator
PHP Class for resize and create, generate thumbnails.<br>
Supports GIF, JPG (JPEG), PNG and BMP images.<br>
Will preserve transparency for GIF and PNG.<br>
(JPG and BMP are not transparent.)<br>
For BMP thumbnails you need PHP >= 7.2.<br>
<br>
The class is very easy to use and do the generation of thumbnail.<br>
Have a look at the examples!
```
<?php

require 'class/Thumbnail-Creator.php';
$rez = new Thumbnail();
$image = 'images/foxes.bmp';
$thumb = $rez->resize($image);
echo '<img src="'.$thumb.'">';

?>
```
