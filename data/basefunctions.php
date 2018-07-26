<?php
//***********************************************************************************
//                           Basic PHP-Extension-Functions                          *
//                          Copyright 2018 Tobias Hattinger                         *
//***********************************************************************************
//                                      Contains:                                   *
// Basic Functions                                                                  *
//      •Redirect       (return: void)                                              *
//      •ThisPage       (return: string)                                            *
//      •SubStringFind  (return: bool)                                              *
//      •XSubStringFind (return: bool)                                              *
// Property-Functions                                                               *
//      •SetProperty    (return: void)                                              *
//      •GetProperty    (return: string)                                            *
//      •IncProperty    (return: int)                                               *
//      •DecProperty    (return: int)                                               *
// MySQL-Functions                                                                  *
//      •MySQLNonQuery  (return: bool)                                              *
//      •MySQLSkalar    (return: string)                                            *
//      •MySQLCount     (return: int)                                               *
//      •MySQLExists    (return: bool)                                              *
//      •Fetch          (return: string)                                            *
//      •FetchCount     (return: int)                                               *
// File-Uploads                                                                     *
//      •FileUpload     (return: void)                                              *
//      •DeleteFolder   (return: void)                                              *
//***********************************************************************************

//***********************************************************************************
//**** Basic Functions **************************************************************
//***********************************************************************************

function Redirect($path,$delay=0)
{
    // DESCRIPTION:
    // Use Redirect() to change to another page.
    // Usefull afer POST-Instructions with SQL-Querys like: INSERT, DELETE, etc.
    // $path    relative or absolute path: "/testpage", "google.at"
    // $delay   time afer which the redirect is executed. default: 0s
    echo '<meta http-equiv="refresh" content="'.$delay.'; url='.$path.'" />';
}

function ThisPage()
{
    return  basename($_SERVER["REQUEST_URI"], '.php');
}


function SubStringFind($string,$search)
{
    // DESCRIPTION:
    // Returns true/false when a string is found inside another string
    // $string  the string that needs to be examined
    // $search  the string you want to search for

    if(str_replace($search,'',$string)==$string) return false;
    else return true;
}

function XSubStringFind()
{
    // DESCRIPTION:
    // = SubstringFind for infinite search-strings
    // Returns true/false when one of the given strings is found inside the main string
    // 1st Arg  String to search in
    // nth Arg  Strings to be found in main string

    $amt = func_num_args();
    $search = func_get_args();
    $string = strtolower($search[0]);
    $retval=false;

    for($i=1;$i<$amt;$i++) if(str_replace(strtolower($search[$i]),'',$string)!=$string) $retval = true;
    return $retval;
}

//***********************************************************************************
//**** Property Functions ***********************************************************
//***********************************************************************************

function GetProperty($key)
{
    // DESCRIPTION:
    // Return a Property saved in the Database
    // If property does not exist, an empty string ("") is returned
    // $key     Keyword of the property (e.g. keyword="site_name" returns "My Cool Website")

    return MySQLSkalar("SELECT value AS x FROM settings WHERE setting = '$key'");
}

function SetProperty($key,$value)
{
    if(fetch_count("settings","setting",$key) != 0) MySQLNonQuery("UPDATE settings SET value = '$value' WHERE setting = '$key'");
    else MySQLNonQuery("INSERT INTO settings (setting,value) VALUES ('$key','$value')");
}

function IncProperty($key,$resetLimit = "none")
{
    if($resetLimit != "none" AND GetProperty($key)>=$resetLimit) SetProperty($key,0);
    SetProperty($key,GetProperty($key) + 1);
    return GetProperty($key);
}

function DecProperty($key)
{
    SetProperty($key,GetProperty($key) - 1);
    return GetProperty($key);
}

//***********************************************************************************
//**** MySQL Functions **************************************************************
//***********************************************************************************

function MySQLNonQuery($strSQL)
{
    require("mysql_connect.php");
    $rs = mysqli_query($link,$strSQL);
    mysqli_close($link);
    return $rs;
}

function MySQLSkalar($strSQL)
{
    require("mysql_connect.php");
    $retval = '';
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs)) $retval = $row['x'];
    mysqli_close($link);
    return $retval;
}

function MySQLExists($strSQL)
{
    require("mysql_connect.php");
    $rs=mysqli_query($link,$strSQL);
    $retval = (mysqli_num_rows($rs)!=0) ? true : false ;
    mysqli_close($link);
    return $retval;
}

function MySQLCount($strSQL)
{
    require("mysql_connect.php");
    $rs=mysqli_query($link,$strSQL);
    $retval = mysqli_num_rows($rs);
    mysqli_close($link);
    return $retval;
}

function Fetch($db,$get,$col,$like)
{
    require("mysql_connect.php");

    $retval = '';
    $strSQL = "SELECT * FROM $db WHERE $col LIKE '$like'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs)) $retval = $row[$get];
    mysqli_close($link);
    return $retval;
}

function FetchCount($db,$col,$like)
{
    require("mysql_connect.php");

    $strSQL = "SELECT * FROM $db WHERE $col LIKE '$like'";
    $rs=mysqli_query($link,$strSQL);
    $retval = mysqli_num_rows($rs);
    mysqli_close($link);
    return $retval;
}

//***********************************************************************************
//**** File Functions ***************************************************************
//***********************************************************************************

function MultiFileUpload($path,$formId,$formats="",$limit="",$sql="")
{
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
        foreach ($_FILES['files']['name'] as $f => $name)
        {
            if ($_FILES['files']['error'][$f] == 4) continue;
            if ($_FILES['files']['error'][$f] == 0)
            {
                if ($_FILES['files']['size'][$f] > $max_file_size)
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
                    if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $path.$name)) $count++;
                    MySQLNonQuery(str_replace('FNAME',$name,$sql));
                }
            }
        }
    }
}

function DeleteFolder($path)
{
    $files = glob($path.'*');
    foreach($files as $file)
    {
        if(is_file($file)) unlink($file);
    }
    rmdir($path);
}

?>