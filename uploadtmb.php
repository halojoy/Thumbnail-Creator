<?php

if (isset($_FILES['userfile'])) {

    $type = $_FILES['userfile']['type'];
    if ($type != 'image/jpeg' && $type != 'image/gif' &&
        $type != 'image/png'  && $type != 'image/bmp')
        exit('Error. File type <b>'.$type.'</b> not supported.');

    $uploaddir  = $_POST['dir'];
    $image      = $_FILES['userfile']['name'];
    $uploadfile = $uploaddir.'/'.$image;
    if (!is_dir($uploaddir))
        mkdir($uploaddir, 0777, true);

    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        echo 'Image file is valid, and was successfully uploaded.<br><br>';
    } else {
        echo 'Error. Possible file upload attack!<br>';
    }

    require 'class/Thumbnail-Creator.php';
    /* Initiate the class */
    $rez = new Thumbnail;
    /* Submit image path */
    $rez->image = $uploadfile;
    /* Do the resize */
    $tmb = $rez->resize(300,100);

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
