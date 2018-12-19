<?php

abstract class FileUploaderOutput
{
    const FilenameOnly = '@FUO_FILENAMEONLY';
    const FilenamePlusExtension = '@FUO_FILENAMEPLUSEXTENSION';
    const ExtensionOnly = '@FUO_EXTENSIONONLY';
    const FilepathPlusFilename = '@FUO_FILEPATHPLUSNAME';
    const FilepathOnly = '@FUO_FILEPATHONLY';
}

class FileUploader
{
##########################################################################################

    private static $sqlConnectionLink;

    private $fileFormElementName;
    private $fileUploadDirectory;
    private $fileCustomName;
    private $fileSQLEntry;
    private $fileValidFormats = array();
    private $fileMaxSize;
    private $fileAspectRatio;
    private $fileTargetResolutionX;
    private $fileTargetResolutionY;
    private $fileScaleFactor;
    private $fileOverride;


##########################################################################################

    public function __construct()
    {
        require("upload.lib.config.php");

        $this->fileUploadDirectory = $uploadConfigDefaultUploadDirectory;
        $this->fileCustomName = "";
        $this->fileSQLEntry = "";
        $this->fileAspectRatio = "";
        $this->fileTargetResolutionX = "";
        $this->fileScaleFactor = "";
        $this->fileOverride = false;
        $this->fileMaxSize = 99999999;

    }

    public static function init()
    {
        require("upload.lib.config.php");

        self::$sqlConnectionLink = mysqli_connect($uploadConfigDatabaseHost,$uploadConfigDatabaseUser,$uploadConfigDatabasePass,$uploadConfigDatabaseName) OR die("<br><br><b>Error in upload.lib.php :</b> Could not connect to Database (Code 1)<br><br>");
    }

##########################################################################################

    private function SaveReplace($string)
    {
        // DESCRIPTION:
        // Formats a given string so it is save for URL-names etc.
        // $string  The string that should be formated

        // Replacing "Ä,ä,Ö,ö,Ü,ü,ß" and "-" (HTML-Characters)
        $sstr = str_replace(' ','-',$string);
        $sstr = str_replace('&Auml;','AE',$sstr);
        $sstr = str_replace('&auml;','ae',$sstr);
        $sstr = str_replace('&Ouml;','OE',$sstr);
        $sstr = str_replace('&ouml;','oe',$sstr);
        $sstr = str_replace('&Uuml;','UE',$sstr);
        $sstr = str_replace('&uuml;','ue',$sstr);
        $sstr = str_replace('&szlig;','ss',$sstr);

        // Replacing "Ä,ä,Ö,ö,Ü,ü,ß" (UTF-Characters/Database)
        $sstr = str_replace('Ã„','AE',$sstr);
        $sstr = str_replace('Ã¤','ae',$sstr);
        $sstr = str_replace('Ã–','OE',$sstr);
        $sstr = str_replace('Ã¶','oe',$sstr);
        $sstr = str_replace('Ãœ','UE',$sstr);
        $sstr = str_replace('Ã¼','ue',$sstr);
        $sstr = str_replace('ÃŸ','ss',$sstr);

        // Remove everything but Alphanumeric letters and allowed characters
        $sstr = preg_replace('/[^0-9A-Za-z-_+.\|]/', '', $sstr);

        return $sstr;
    }

    private static function MySQLNonQuery($sqlStatement)
    {
        $stmt = self::$sqlConnectionLink->prepare($sqlStatement);
        $stmt->execute();
        $stmt->close();
    }

    private function SubStringFind($string,$search)
    {
        return (str_replace($search,'',$string)!=$string);
    }

##########################################################################################

    public function SetFileElement($phpFormElementName)
    {
        $this->fileFormElementName = $phpFormElementName;
    }

    public function SetPath($uploadPathDirectory)
    {
        // Remove "/" at beginning of path
        if(mb_substr($uploadPathDirectory, 0, 1) == '/') $uploadPathDirectory = ltrim($uploadPathDirectory,'/');

        // Add "/" at end of path
        if(mb_substr($uploadPathDirectory, -1) != "/") $uploadPathDirectory .= "/";

        $this->fileUploadDirectory = $uploadPathDirectory;
    }

    public function SetName($customFileName)
    {
        $this->fileCustomName = $customFileName;
    }

    public function SetSQLEntry($sqlStatement)
    {
        $this->fileSQLEntry = $sqlStatement;
    }

    public function SetFileTypes(...$fileTypeExtensions)
    {
        foreach($fileTypeExtensions AS $fileType)
        {
            array_push($this->fileValidFormats,strtolower($fileType));
        }
    }

    public function SetMaxFileSize($maximumFileSize)
    {
             if($this->SubStringFind($maximumFileSize,'KB')) $this->fileMaxSize = $maximumFileSize = 1024 * str_replace('KB','',$maximumFileSize);
        else if($this->SubStringFind($maximumFileSize,'MB')) $this->fileMaxSize = $maximumFileSize = 1024 * 1024 * str_replace('MB','',$maximumFileSize);
        else if($this->SubStringFind($maximumFileSize,'GB')) $this->fileMaxSize = $maximumFileSize = 1024 * 1024 * 1024 * str_replace('GB','',$maximumFileSize);
        else $this->fileMaxSize = $maximumFileSize;

    }

    public function SetTargetAspectRatio($aspectRatio)
    {
        $this->fileAspectRatio = $aspectRatio;
    }

    public function SetTargetResolution($resolutionX,$resolutionY)
    {
        $this->fileTargetResolutionX = $resolutionX;
        $this->fileTargetResolutionY = $resolutionY;
    }

    public function SetScaleFactor($scaleFactor)
    {
        $this->fileScaleFactor = $scaleFactor;
    }

    public function OverrideDuplicates($override)
    {
        $this->fileOverride = $override;
    }


##########################################################################################

    public function Upload()
    {
        // Create directory if not existing
        if(!is_dir($this->fileUploadDirectory)) mkdir($this->fileUploadDirectory, 0750);

        // Upload Files to Server
        $count=0;

        foreach ($this->SaveReplace($_FILES[$this->fileFormElementName]['name']) AS $file => $name)
        {
            // Skip if no file is selected
            if ($_FILES[$this->fileFormElementName]['error'][$file] == 4) continue;

            // Continue if no errors occure
            if ($_FILES[$this->fileFormElementName]['error'][$file] == 0)
            {
                // Error: File to big
                if ($_FILES[$this->fileFormElementName]['size'][$file] > $this->fileMaxSize)
                {
                    throw new Exception("***** WARNING: The file you are trying to upload ($name) is to large! Maximum filesize set in MaxFileSize() *****");
                }

                // Error: Forbidden filetype
                else if(!empty($this->fileValidFormats) AND !in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), $this->fileValidFormats))
                {
                    throw new Exception("***** WARNING: The file-extension of $name (".pathinfo($name, PATHINFO_EXTENSION).") is not a valid format set in SetFileTypes()! *****");
                }

                // Upload if all checkpoints have been passed
                else
                {
                    $tempFileName = $_FILES[$this->fileFormElementName]["tmp_name"][$file];
                    $fileExtension = pathinfo($this->fileUploadDirectory.$name, PATHINFO_EXTENSION);

                    $fileName = $this->fileUploadDirectory.$name;

                    if($this->fileCustomName != "") $fileName = $this->fileUploadDirectory.$this->fileCustomName.'.'.$fileExtension;

                    if(!$this->fileOverride)
                    {
                        $i = 2;
                        $dupFilename = $fileName;
                        while(file_exists($dupFilename))
                        {
                            $dupFilename = $this->fileUploadDirectory.$this->fileCustomName.'('.$i++.').'.$fileExtension;
                        }
                        $fileName = $dupFilename;
                    }

                    if(move_uploaded_file($tempFileName, $fileName))
                    {
                        if($this->fileAspectRatio != "")
                        {
                            $ratioParts = explode(':',$this->fileAspectRatio);
                            $aspectRatioX = $ratioParts[0];
                            $aspectRatioY = $ratioParts[1];

                            // Calculate Aspect Ratio of old image
                            list($width, $height, $type, $attr) = getimagesize($fileName);

                            $oldRatioX = $width / $height;
                            $oldRatioY = 1;

                            $resizeFactorX = $oldRatioX * $aspectRatioX;
                            $resizeFactorY = $oldRatioY * $aspectRatioY;

                            $newWidth = $width * $resizeFactorX;
                            $newHeight = $height * $resizeFactorY;

                            // Create new image
                            $newImage = imagecreatetruecolor($newWidth, $newHeight);

                            imagealphablending($newImage, false);
                            imagesavealpha($newImage,true);
                            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);

                            switch(exif_imagetype($fileName))
                            {
                                case IMAGETYPE_GIF: $oldImage = imagecreatefromgif($fileName); break;
                                case IMAGETYPE_JPEG: $oldImage = imagecreatefromjpeg($fileName); break;
                                case IMAGETYPE_PNG: $oldImage = imagecreatefrompng($fileName); break;
                                case IMAGETYPE_BMP:  $oldImage = imagecreatefrombmp($fileName); break;
                                case IMAGETYPE_WEBP: $oldImage = imagecreatefromwebp($fileName); break;
                                default: throw new Exception("Imagetype not supported!");
                            }

                            imagecopyresized($newImage, $oldImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                            switch(exif_imagetype($fileName))
                            {
                                case IMAGETYPE_GIF: imagegif($newImage,$fileName); break;
                                case IMAGETYPE_JPEG: imagejpeg($newImage,$fileName); break;
                                case IMAGETYPE_PNG: imagepng($newImage,$fileName); break;
                                case IMAGETYPE_BMP:  imagebmp($newImage,$fileName); break;
                                case IMAGETYPE_WEBP: imagewebp($newImage,$fileName); break;
                                default: throw new Exception("Imagetype not supported!");
                            }
                        }

                        if($this->fileTargetResolutionX != "")
                        {
                            list($width, $height, $type, $attr) = getimagesize($fileName);

                            if($width > $height) $scalingFactor = $this->fileTargetResolutionX / $width;
                            if($width <= $height) $scalingFactor = $this->fileTargetResolutionY / $height;

                            $newWidth = $width * $scalingFactor;
                            $newHeight = $height * $scalingFactor;

                            // Create new image
                            $newImage = imagecreatetruecolor($newWidth, $newHeight);

                            imagealphablending($newImage, false);
                            imagesavealpha($newImage,true);
                            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);

                            switch(exif_imagetype($fileName))
                            {
                                case IMAGETYPE_GIF: $oldImage = imagecreatefromgif($fileName); break;
                                case IMAGETYPE_JPEG: $oldImage = imagecreatefromjpeg($fileName); break;
                                case IMAGETYPE_PNG: $oldImage = imagecreatefrompng($fileName); break;
                                case IMAGETYPE_BMP:  $oldImage = imagecreatefrombmp($fileName); break;
                                case IMAGETYPE_WEBP: $oldImage = imagecreatefromwebp($fileName); break;
                                default: throw new Exception("Imagetype not supported!");
                            }

                            imagecopyresized($newImage, $oldImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                            switch(exif_imagetype($fileName))
                            {
                                case IMAGETYPE_GIF: imagegif($newImage,$fileName); break;
                                case IMAGETYPE_JPEG: imagejpeg($newImage,$fileName); break;
                                case IMAGETYPE_PNG: imagepng($newImage,$fileName); break;
                                case IMAGETYPE_BMP:  imagebmp($newImage,$fileName); break;
                                case IMAGETYPE_WEBP: imagewebp($newImage,$fileName); break;
                                default: throw new Exception("Imagetype not supported!");
                            }
                        }

                        if($this->fileScaleFactor != "")
                        {
                            list($width, $height, $type, $attr) = getimagesize($fileName);

                            $newWidth = $width * $this->fileScaleFactor;
                            $newHeight = $height * $this->fileScaleFactor;

                            // Create new image
                            $newImage = imagecreatetruecolor($newWidth, $newHeight);

                            imagealphablending($newImage, false);
                            imagesavealpha($newImage,true);
                            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);

                            switch(exif_imagetype($fileName))
                            {
                                case IMAGETYPE_GIF: $oldImage = imagecreatefromgif($fileName); break;
                                case IMAGETYPE_JPEG: $oldImage = imagecreatefromjpeg($fileName); break;
                                case IMAGETYPE_PNG: $oldImage = imagecreatefrompng($fileName); break;
                                case IMAGETYPE_BMP:  $oldImage = imagecreatefrombmp($fileName); break;
                                case IMAGETYPE_WEBP: $oldImage = imagecreatefromwebp($fileName); break;
                                default: throw new Exception("Imagetype not supported!");
                            }

                            imagecopyresized($newImage, $oldImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                            switch(exif_imagetype($fileName))
                            {
                                case IMAGETYPE_GIF: imagegif($newImage,$fileName); break;
                                case IMAGETYPE_JPEG: imagejpeg($newImage,$fileName); break;
                                case IMAGETYPE_PNG: imagepng($newImage,$fileName); break;
                                case IMAGETYPE_BMP:  imagebmp($newImage,$fileName); break;
                                case IMAGETYPE_WEBP: imagewebp($newImage,$fileName); break;
                                default: throw new Exception("Imagetype not supported!");
                            }
                        }


                        $count++;
                    }
                    if($this->fileSQLEntry != "")
                    {
                        $fileSQLStatement = $this->fileSQLEntry;

                        $fileSQLStatement = str_replace(FileUploaderOutput::FilenameOnly,str_replace(pathinfo($fileName, PATHINFO_EXTENSION).'.','',$fileName),$fileSQLStatement);
                        $fileSQLStatement = str_replace(FileUploaderOutput::FilenamePlusExtension,$fileName,$fileSQLStatement);
                        $fileSQLStatement = str_replace(FileUploaderOutput::ExtensionOnly,pathinfo($fileName, PATHINFO_EXTENSION),$fileSQLStatement);
                        $fileSQLStatement = str_replace(FileUploaderOutput::FilepathPlusFilename,$this->fileUploadDirectory.$fileName,$fileSQLStatement);
                        $fileSQLStatement = str_replace(FileUploaderOutput::FilepathOnly,$this->fileUploadDirectory,$fileSQLStatement);

                        self::MySQLNonQuery($fileSQLStatement);
                    }
                }
            }
        }
    }
}
FileUploader::init();

?>