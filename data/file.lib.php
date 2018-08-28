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

function FileUpload($path,$formId,$formats="",$limit="",$sql="")
{
    // DESCRIPTION:
    // Required for File-Uploads
    // Put this function inside the POST-Part
    // $path        Upload Directory
    // $formId      ID-Property of File-Upload-Element
    // $formats     Allowed file formats, blank if any, Delimiter: ","
    // $limit       Upload Size Limit, xKB, xMB, xGB. Default 10MB
    // $sql         Insert Filename in Database. Filename = FNAME

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
        foreach ($_FILES[$formId]['name'] as $f => $name)
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
                    if(move_uploaded_file($_FILES[$formId]["tmp_name"][$f], $path.$name)) $count++;
                    if($sql != "") MySQLNonQuery(str_replace('FNAME',$name,$sql));
                }
            }
        }
    }
}

function DeleteFolder($path)
{
    // DESCRIPTION
    // Deletes a folder and everything it contains

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

?>