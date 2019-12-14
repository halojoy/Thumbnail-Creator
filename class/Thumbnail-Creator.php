 <?php

Class Thumbnail
{
    public $maxWidth  = 300;
    public $maxHeight = 100;

    const IMAGE_HANDLERS = [
        IMAGETYPE_GIF =>  ['load' => 'imagecreatefromgif',
                           'save' => 'imagegif'],
        IMAGETYPE_JPEG => ['load' => 'imagecreatefromjpeg',
                           'save' => 'imagejpeg'],
        IMAGETYPE_PNG =>  ['load' => 'imagecreatefrompng',
                           'save' => 'imagepng'],
        IMAGETYPE_BMP =>  ['load' => 'imagecreatefrombmp',
                           'save' => 'imagebmp']
    ];

    public function __construct()
    {
    }

    public function resize($image, $width='', $height='')
    {
        /* Get the image type */
        $type = @exif_imagetype($image);
        if (!$type || !self::IMAGE_HANDLERS[$type])
            return;

        /* Set destination thumb */
        $path = pathinfo($image);
        $thumb = $path['dirname'].'/tmb_'.$path['basename'];

        /* Set the thumbnail size */
        if ($width)  $this->maxWidth  = $width;
        if ($height) $this->maxHeight = $height;

        /* If thumb exists and already has wanted size then return */
        if (file_exists($thumb)) {
            list($nowWidth, $nowHeight) = getimagesize($thumb);
            if ($nowWidth == $this->maxWidth || $nowHeight == $this->maxHeight)
                return $thumb;
        }

        /* Load the image */
        $img = call_user_func(self::IMAGE_HANDLERS[$type]['load'], $image);

        /* Get dimensions of the image */
        $srcWidth  = imagesx($img);
        $srcHeight = imagesy($img);

        /* Get the thumb ratio k */
        $k = min($this->maxWidth/$srcWidth, $this->maxHeight/$srcHeight);

        /* If already small image just copy and return thumb path */
        if ($k >= 1) {
            copy($image, $thumb);
            return $thumb;
        }

        /* Set the thumb size */
        $tmbWidth  = round($k * $srcWidth);
        $tmbHeight = round($k * $srcHeight);

        /* Create a thumb template with new dimensions */
        $tmb = imagecreatetruecolor($tmbWidth, $tmbHeight);

        /* Check if this image is GIF, then set Transparency */
        if ($type == IMAGETYPE_GIF)
            imagecolortransparent($tmb, imagecolorallocate($tmb, 0, 0, 0));

        /* Check if this image is PNG, then set Transparency */
        if ($type == IMAGETYPE_PNG) {
            imagealphablending($tmb, false);
            imagesavealpha($tmb, true);
        }

        /* Do the copy */
        imagecopyresampled($tmb, $img, 0, 0, 0, 0,
                           $tmbWidth, $tmbHeight, $srcWidth, $srcHeight);

        /* Save to path of new thumb image */
        call_user_func(self::IMAGE_HANDLERS[$type]['save'], $tmb, $thumb);

        /* Clear memory, delete images used for creation */
        imagedestroy($img);
        imagedestroy($tmb);

        /* Return path to the thumb */
        return $thumb;
    }
}
