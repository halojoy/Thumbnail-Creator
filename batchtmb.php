<?php

echo '<h4>Batch Thumbnails Creation</h4>';

if (isset($_POST['dir'])) {

    $directory = $_POST['dir'];
    if (is_dir($directory)) {

        $scanfiles = scandir($directory);
        $allimages = array();
        foreach($scanfiles as $file) {
            if (substr($file, 0, 4) == 'tmb_') continue;
            if (in_array(@exif_imagetype($directory.'/'.$file), [1,2,3,6]))
                $allimages[] = $file;
        }

        require 'class/Thumbnail-Creator.php';
        $rez = new Thumbnail;

        $count = 0;
        foreach($allimages as $filename) {
            $img = $directory.'/'.$filename;
            $tmb = $rez->resize($img);
            echo '<a href="'.$img.'" target="_blank"><img src="'.$tmb.'"></a>&nbsp;';
            $count++;
            if ($count % 7 == 0) echo '<br>';
        }
        exit();

    }else
        echo 'Directory not exists.<br><br>';
}

?>
<form method="POST">
    Submit a directory with images<br>
    and thumbnails will be created for all images.<br><br>
    Directory:<br>
    <input type="text" name="dir" required><br><br>
    <input type="submit">
</form>
