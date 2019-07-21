 <?php

Class Thumbnail
{
    public $directory;
    public $image;
    public $thumb;
    public $maxWidth  = 300;
    public $maxHeight = 100;
    public $source;
    public $destin;

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

    public function __construct($directory = '')
    {
        if ($directory)
            $this->directory = rtrim($directory, '/');
    }

    public function resize($width='', $height='')
    {
        /* Get source and destination */
        $this->source = $this->image;
        if ($this->thumb)
            $this->destin = $this->thumb;
        else
            $this->destin = 'tmb_'.$this->image;

        if ($this->directory) {           
            $this->source = $this->directory.'/'.$this->source;
            $this->destin = $this->directory.'/'.$this->destin;
        }

        /* If this thumb already exists then return */
        if (file_exists($this->destin))
            return $this->destin;

        /* Get the image type */
        $type = exif_imagetype($this->source);
        if (!$type || !self::IMAGE_HANDLERS[$type])
            return 'unsupported';

        /* Load the image */
        $img = call_user_func(self::IMAGE_HANDLERS[$type]['load'], $this->source);

        /* Get dimensions of image and max thumb size*/
        $srcWidth  = imagesx($img);
        $srcHeight = imagesy($img);
        if ($width)  $this->maxWidth  = $width;
        if ($height) $this->maxHeight = $height;
        
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
