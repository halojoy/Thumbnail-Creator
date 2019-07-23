<?php

if (isset($_FILES['userfile'])) {

    $type = $_FILES['userfile']['type'];
    if ($type != 'image/jpeg' && $type != 'image/gif' && $type != 'image/png' &&
        $type != 'image/bmp')
        exit('Error. File type <b>'.$type.'</b> not supported.');

    $uploaddir  = '../uploads';
    $image = $_FILES['userfile']['name'];
    $uploadfile = $uploaddir.'/'.$image;

    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        echo 'File is valid, and was successfully uploaded.<br><br>';
        echo 'Thumb:<br>';
    } else {
        echo 'Error. Possible file upload attack!<br>';
    }

    require '../class/Thumbnail-Creator.php';

    /* Initiate the class with target directory */
    $rez = new Thumbnail($uploaddir);

    /* Load the image */
    $rez->image = $image;

    /* Do the resize */
    $tmb = $rez->resize();

    /* Display the thumbnail */
    echo '<img src="'.$tmb.'"><br><br>';

    echo '<a href="">Once again.</a>';
    exit();

}
?>
<form enctype="multipart/form-data" action="" method="POST">

    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />

    <!-- Name of input element determines name in $_FILES array -->
    Upload one image and create thumbnail:<br>
    <input name="userfile" type="file" /><br><br>

    <input type="submit" value="Send File" />

</form>
