<?php

echo '<h4>Batch Thumbnails Creation</h4>';

if (isset($_POST['dir'])) {

    $directory = $_POST['dir'];
    if (is_dir($directory)) {

        $supported = array('gif', 'jpeg', 'jpg', 'png', 'bmp');
        $scanfiles = scandir($directory);
        $allimages = array();
        foreach($scanfiles as $file) {
            if (substr($file, 0, 4) == 'tmb_') continue;
            if (in_array(pathinfo(strtolower($file), PATHINFO_EXTENSION), $supported))
                $allimages[] = $file;
        }

        require 'class/Thumbnail-Creator.php';
        $rez = new Thumbnail;

        $count = 0;
        foreach($allimages as $filename) {
            $rez->image = $directory.'/'.$filename;
            $tmb = $rez->resize(300,100);
            $img = $rez->source;
            if ($count % 8 == 0 && $count != 0) echo '<br>';
            $count++;
            echo '<a href="'.$img.'" target="_blank"><img src="'.$tmb.'"></a>&nbsp;';
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
