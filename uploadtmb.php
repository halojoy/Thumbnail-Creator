<?php

if (isset($_FILES['userfile'])) {

    $tmp_file = $_FILES['userfile']['tmp_name'];
    if (!in_array(@exif_imagetype($tmp_file), [1,2,3,6]))
        exit('Error. File <b>\''.$_FILES['userfile']['name'].'\'</b> not supported.');

    $uploaddir  = $_POST['dir'];
    if (!is_dir($uploaddir))
        mkdir($uploaddir, 0777, true);
    $image      = $_FILES['userfile']['name'];
    $uploadfile = $uploaddir.'/'.$image;

    if (move_uploaded_file($tmp_file, $uploadfile)) {
        echo 'Image file is valid, and was successfully uploaded.<br><br>';
    } else {
        echo 'Error. Possible file upload attack!<br>';
    }

    require 'class/Thumbnail-Creator.php';
    /* Initiate the class */
    $rez = new Thumbnail;

    /* Do the resize */
    $tmb = $rez->resize($uploadfile);

    /* Display the clickable thumbnail */
    echo 'Here is the thumb:<br>';
    echo '<a href="'.$uploadfile.'" target="_blank"><img src="'.$tmb.'"></a>';
    echo '<br><br>';

    echo '<a href="">Once again.</a>';
    exit();

}
?>
<form enctype="multipart/form-data" action="" method="POST">

    Upload one image and create thumbnail:<br>
    <input type="file" name="userfile" required><br><br>
    Upload to directory:
    <input type="text" name="dir" required><br><br>
    <input type="submit" value="Send File">

</form>
