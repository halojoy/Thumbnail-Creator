 <?php

Class Thumbnail
{
    public $image;
    public $source;
    public $destin;
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

    public function resize($width='', $height='')
    {
        /* Set source and destination */
        $this->source = $this->image;
        $path = pathinfo($this->source);
        $this->destin = $path['dirname'].'/tmb_'.$path['basename'];
  
        /* Get the image type */
        $type = @exif_imagetype($this->source);
        if (!$type || !self::IMAGE_HANDLERS[$type])
            return;

        /* Set the thumbnail size */
        if ($width)  $this->maxWidth  = $width;
        if ($height) $this->maxHeight = $height;

        /* If thumb exists and already has wanted size then return */
        if (file_exists($this->destin)) {
            list($nowWidth, $nowHeight) = getimagesize($this->destin);
            if ($nowWidth == $this->maxWidth || $nowHeight == $this->maxHeight)
                return $this->destin;
        }

        /* Load the image */
        $img = call_user_func(self::IMAGE_HANDLERS[$type]['load'], $this->source);

        /* Get dimensions of the image */
        $srcWidth  = imagesx($img);
        $srcHeight = imagesy($img);
        
        /* Get the thumb ratio k */
        $k = min($this->maxWidth/$srcWidth, $this->maxHeight/$srcHeight);

        /* If already small image just copy and return thumb path */
        if ($k >= 1) {
            copy($this->source, $this->destin);
            return $this->destin;
        }

        /* Set the thumb size */
        $tmbWidth  = round($k * $srcWidth);
        $tmbHeight = round($k * $srcHeight);

        /* Create a thumb template with new dimensions */
        $thumb = imagecreatetruecolor($tmbWidth, $tmbHeight);

        /* Check if this image is GIF, then set Transparency */
        if ($type == IMAGETYPE_GIF)
            imagecolortransparent($thumb, imagecolorallocate($thumb, 0, 0, 0));

        /* Check if this image is PNG, then set Transparency */
        if ($type == IMAGETYPE_PNG) {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
        }

        /* Do the copy */
        imagecopyresampled($thumb, $img, 0, 0, 0, 0,
                           $tmbWidth, $tmbHeight, $srcWidth, $srcHeight);

        /* Save to path of new thumb image */
        call_user_func(self::IMAGE_HANDLERS[$type]['save'], $thumb, $this->destin);

        /* Clear memory, delete images used for creation */
        imagedestroy($img);
        imagedestroy($thumb);

        /* Return path to the thumb */
        return $this->destin;
    }
}
