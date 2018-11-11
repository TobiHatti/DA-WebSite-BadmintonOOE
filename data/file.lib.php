<?php
//***********************************************************************************
//                            PHP - File-Function Library                           *
//                          Copyright 2018 Tobias Hattinger                         *
//***********************************************************************************
//                                      Contains:                                   *
// File-Functions                                                                   *
//      •FileUpload     (return: void)                                              *
//      •DeleteFolder   (return: void)                                              *
//***********************************************************************************

function FileUpload($path,$formId,$formats="",$limit="",$sql="",$customName="")
{
    // DESCRIPTION:
    // Required for File-Uploads
    // Put this function inside the POST-Part
    // $path        Upload Directory
    // $formId      ID-Property of File-Upload-Element
    // $formats     Allowed file formats, blank if any, Delimiter: ","
    // $limit       Upload Size Limit, xKB, xMB, xGB. Default 10MB
    // $sql         Insert Filename in Database. Filename = FNAME

    $path = (StartsWith($path, '/')) ? ltrim($path,'/') : $path ;

    $format_restriction = ($formats=='') ? false : true;
    $valid_formats = explode(',',$formats);

    if(SubStringFind($limit,'KB')) $max_file_size = 1000 * str_replace('KB','',$limit);
    else if(SubStringFind($limit,'MB')) $max_file_size = 1000 * 1000 * str_replace('MB','',$limit);
    else if(SubStringFind($limit,'GB')) $max_file_size = 1000 * 1000 * 1000 * str_replace('GB','',$limit);
    else $max_file_size = 10 * 1000 * 1000;

    if(!is_dir($path)) mkdir($path, 0750);

    $count=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        foreach (SReplace($_FILES[$formId]['name'],'.') as $f => $name)
        {
            if ($_FILES[$formId]['error'][$f] == 4) continue;
            if ($_FILES[$formId]['error'][$f] == 0)
            {
                if ($_FILES[$formId]['size'][$f] > $max_file_size)
                {
                    $message[] = "$name is too large!.";
                    continue;
                }
                else if($format_restriction AND (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)))
                {
                    $message[] = "$name is not a valid format";
                    continue;
                }
                else
                {
                    if(move_uploaded_file($_FILES[$formId]["tmp_name"][$f], $path.$name))
                    {

                        if($customName!="")
                        {
                            $extension = pathinfo($path.$name, PATHINFO_EXTENSION);
                            rename($path.$name, $path.$customName.'.'.$extension);
                            $name = $customName.'.'.$extension;
                        }
                        $count++;
                    }
                    if($sql != "") SQL::NonQuery(str_replace('FNAME',$name,$sql));
                }
            }
        }
    }
}

function DeleteFolder($path)
{
    // DESCRIPTION
    // Deletes a folder and everything it contains

    $path = (StartsWith($path, '/')) ? ltrim($path,'/') : $path ;

    $files = glob($path.'*');
    foreach($files as $file)
    {
        if(is_file($file)) unlink($file);
    }
    rmdir($path);
}

function Base64toIMG($base64img,$path)
{
    // DESCRIPTION
    // Converts a Base64 string into a file
    // $base64img   String inside the src="" part of an image tag
    // $path        Upload path. e.g. "content/"
    // return       File Path

    $path = (StartsWith($path, '/')) ? ltrim($path,'/') : $path ;

    if(!is_dir($path)) mkdir($path, 0750);

    $base64img = str_replace('data:image/png;base64,', '', $base64img);
    $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
    $base64img = str_replace(' ', '+', $base64img);
    $data = base64_decode($base64img);
    $file = $path . uniqid() . '.png';
    $success = file_put_contents($file, $data);
    //print $success ? $file : 'Unable to save the file.';
    return '/'.$file;
}

function ResizeImage($path,$output,$maxWidth, $maxHeight)
{
    $path = (StartsWith($path, '/')) ? ltrim($path,'/') : $path ;

    list($width, $height) = getimagesize($path);

    if($width > $height) $factor = $width / $maxWidth;
    else $factor = $height / $maxHeight;


    $newwidth = $width / $factor;
    $newheight = $height / $factor;

    if(exif_imagetype($path) == IMAGETYPE_JPEG)
    {
        $source = imagecreatefromjpeg($path);
    }
    else if (exif_imagetype($path) == IMAGETYPE_PNG)
    {
        $source = imagecreatefrompng($path);
    }

    $thumb = imagecreatetruecolor($newwidth, $newheight);


    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    imagejpeg($thumb,$output);

}

function CropImage($path, $output, $width, $height)
{
    $path = (StartsWith($path, '/')) ? ltrim($path,'/') : $path ;

    $x = 0;
    $y = 0;

    list($cwidth, $cheight) = getimagesize($path);

    /*
    // Not possible due to negative values not beeing supported
    if($cwidth < $width) $xoff = ($width - $cwidth) / 2;
    if($height > $cheight) $yoff = (($height - $cheight) / 2);

    $x += $xoff;
    $y += $yoff;
    */

    $im = imagecreatefromjpeg($path);
    $im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);
    if ($im2 !== FALSE)
    {
        imagejpeg($im2, $output);
        imagedestroy($im2);
    }
    imagedestroy($im);
}

function DirectoryListing($path)
{
    $retval='';
    foreach (glob($path.'*.*') as $filename)
    {

        $file_short = str_replace($path,'',$filename);

        if(XSubStringFind($file_short,'.rar','.zip','.gz','.7z')) $retval.= '<i class="fa fa-file-archive-o"></i>';
        else if(XSubStringFind($file_short,'.wav','.mp3','.aif','.m4r','.m4a','.mid')) $retval.= '<i class="fa fa-file-audio-o"></i>';
        else if(XSubStringFind($file_short,'.c','.cpp','.cs','.js','.php','.html','.css','.htm','.exe','.app','.bat','.cmd','.jar','.asp','.accdb','.db','.dbf','.mdb','.pdb','.sql','.apk','.cgi','.com','.gadget','.wsf','.aspx','.cer','.cfm','.csr','.jsp','.rss','.xhtml'.'.crx'.'.plugin')) echo '<i class="fa fa-file-code-o"></i>';
        else if(XSubStringFind($file_short,'.xls','.xlsx','.xlsm','.xlr')) $retval.= '<i class="fa fa-file-excel-o"></i>';
        else if(XSubStringFind($file_short,'.png','.jpg','.jpeg','.gif','.tiff','.bmp','.svg','.tif','.ico')) $retval.= '<i class="fa fa-file-image-o"></i>';
        else if(XSubStringFind($file_short,'.mpg','.mp4','.mov','.wmv','.avi','.rm','.3gp','.aaf')) $retval.= '<i class="fa fa-file-movie-o"></i>';
        else if(XSubStringFind($file_short,'.pdf','.pct','.indd')) $retval.= '<i class="fa fa-file-pdf-o"></i>';
        else if(XSubStringFind($file_short,'.ppt','.pptx','.pptm')) $retval.= '<i class="fa fa-file-powerpoint-o"></i>';
        else if(XSubStringFind($file_short,'.txt','.rtf','.log')) $retval.= '<i class="fa fa-file-text-o"></i>';
        else if(XSubStringFind($file_short,'.doc','.docx','.docm')) $retval.= '<i class="fa fa-file-word-o"></i>';
        else $retval.= '<i class="fa fa-file-o"></i> ';

        $retval.= ' <a href="'.$filename.'" download>'.$file_short.'</a><br>';
    }

    return $retval;
}

function FileList($path2file, $displayname)
{
    $retval='';

    if(XSubStringFind($path2file,'.rar','.zip','.gz','.7z')) $retval.= '<i class="fa fa-file-archive-o"></i>';
    else if(XSubStringFind($path2file,'.wav','.mp3','.aif','.m4r','.m4a','.mid')) $retval.= '<i class="fa fa-file-audio-o"></i>';
    else if(XSubStringFind($path2file,'.c','.cpp','.cs','.js','.php','.html','.css','.htm','.exe','.app','.bat','.cmd','.jar','.asp','.accdb','.db','.dbf','.mdb','.pdb','.sql','.apk','.cgi','.com','.gadget','.wsf','.aspx','.cer','.cfm','.csr','.jsp','.rss','.xhtml'.'.crx'.'.plugin')) echo '<i class="fa fa-file-code-o"></i>';
    else if(XSubStringFind($path2file,'.xls','.xlsx','.xlsm','.xlr','.csv')) $retval.= '<i class="fa fa-file-excel-o"></i>';
    else if(XSubStringFind($path2file,'.png','.jpg','.jpeg','.gif','.tiff','.bmp','.svg','.tif','.ico')) $retval.= '<i class="fa fa-file-image-o"></i>';
    else if(XSubStringFind($path2file,'.mpg','.mp4','.mov','.wmv','.avi','.rm','.3gp','.aaf')) $retval.= '<i class="fa fa-file-movie-o"></i>';
    else if(XSubStringFind($path2file,'.pdf','.pct','.indd')) $retval.= '<i class="fa fa-file-pdf-o"></i>';
    else if(XSubStringFind($path2file,'.ppt','.pptx','.pptm')) $retval.= '<i class="fa fa-file-powerpoint-o"></i>';
    else if(XSubStringFind($path2file,'.txt','.rtf','.log')) $retval.= '<i class="fa fa-file-text-o"></i>';
    else if(XSubStringFind($path2file,'.doc','.docx','.docm')) $retval.= '<i class="fa fa-file-word-o"></i>';
    else $retval.= '<i class="fa fa-file-o"></i> ';

    $retval.= '<a href="'.$path2file.'" download>&nbsp;&nbsp;'.$displayname.'</a>';

    return $retval;
}

?>