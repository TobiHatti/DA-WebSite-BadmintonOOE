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
//      •StartsWith     (return: bool)                                              *
//      •EndsWith       (return: bool)                                              *
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
//      •MySQLSave      (return: int)                                               *
//      •MySQLPDSave    (return: int)                                               *
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
    // DESCRIPTION:
    // Can be used in the "action"-argument of a <form>-Tag
    // or in combination with the Redirect()-Function
    // Returns the current pagename

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

function StartsWith($haystack, $needle)
{
    // DESCRIPTION:
    // Checks if a string starts with a specific string
    // $haystack    The String that should be searched in
    // $needle      The String that should be searched for

    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function EndsWith($haystack, $needle)
{
    // DESCRIPTION:
    // Checks if a string ends with a specific string
    // $haystack    The String that should be searched in
    // $needle      The String that should be searched for

    $length = strlen($needle);

    return $length === 0 || (substr($haystack, -$length) === $needle);
}

//***********************************************************************************
//**** Property Functions ***********************************************************
//***********************************************************************************

function SetProperty($key,$value)
{
    // DESCRIPTION:
    // Sets a Property saved in the Database
    // If property does not exist, the entry is created in the Database,
    // otherwise it just updates the value
    // $key     Keyword of the property (e.g. keyword="site_name" returns "My Cool Website")
    // $value   The value the property should take

    if(fetch_count("settings","setting",$key) != 0) MySQLNonQuery("UPDATE settings SET value = '$value' WHERE setting = '$key'");
    else MySQLNonQuery("INSERT INTO settings (setting,value) VALUES ('$key','$value')");
}

function GetProperty($key)
{
    // DESCRIPTION:
    // Return a Property saved in the Database
    // If property does not exist, an empty string ("") is returned
    // $key     Keyword of the property (e.g. keyword="site_name" returns "My Cool Website")

    return MySQLSkalar("SELECT value AS x FROM settings WHERE setting = '$key'");
}

function IncProperty($key,$resetLimit = "none")
{
    // DESCRIPTION:
    // Increases the value of a property by 1
    // Order: First the value gets increased, then returned
    // Only works if the property is a numeric value
    // $key         Keyword of the property
    // $resetLimit  Default: none. If the defined value is reached, the value is reset to 0

    if($resetLimit != "none" AND GetProperty($key)>=$resetLimit) SetProperty($key,0);
    SetProperty($key,GetProperty($key) + 1);
    return GetProperty($key);
}

function DecProperty($key)
{
    // DESCRIPTION:
    // Decreases the value of a property by 1
    // Order: First the value gets decreased, then returned
    // Only works if the property is a numeric value
    // $key Keyword of the property

    SetProperty($key,GetProperty($key) - 1);
    return GetProperty($key);
}

//***********************************************************************************
//**** MySQL Functions **************************************************************
//***********************************************************************************

function MySQLNonQuery($strSQL)
{
    // DESCRIPTION:
    // Executes a standard SQL-Query such as UPDATE,DELETE,INSERT, etc.
    // Can be used in combination with "or die("error_msg");"

    require("mysql_connect.php");
    $rs = mysqli_query($link,$strSQL);
    mysqli_close($link);
    return $rs;
}

function MySQLSkalar($strSQL)
{
    // DESCRIPTION:
    // Returns a single value from a Table.
    // Syntax: MySQLSkalar("SELECT name AS x FROM users....");
    // Use "rowname AS x" to get the wanted value

    require("mysql_connect.php");
    $retval = '';
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs)) $retval = $row['x'];
    mysqli_close($link);
    return $retval;
}

function MySQLCount($strSQL)
{
    // DESCRIPTION:
    // Returns the number of rows that fit a specific SQL-Query.
    // Syntax: MySQLCount("SELECT * FROM users WHERE ...");
    // Usualy used for normal counting

    require("mysql_connect.php");
    $rs=mysqli_query($link,$strSQL);
    $retval = mysqli_num_rows($rs);
    mysqli_close($link);
    return $retval;
}

function MySQLExists($strSQL)
{
    // DESCRIPTION:
    // Checks if a specific value or value-combination exists in a table
    // Syntax: MySQLExists("SELECT * FROM users WHERE ...");
    // If at least one result is found, this function returns true, else
    // it returns false
    // Should be used inside an if-Statements, NOT with "or die("error_msg");"

    require("mysql_connect.php");
    $rs=mysqli_query($link,$strSQL);
    $retval = (mysqli_num_rows($rs)!=0) ? true : false ;
    mysqli_close($link);
    return $retval;
}

function Fetch($db,$get,$col,$like)
{
    // DESCRIPTION:
    // Returns a single value from a table
    // Similar to MySQLSkalar(), but here only 1 "WHERE"-Argument is possible
    // $db      Name of the table
    // $get     The column you want to get the value from
    // $col     For "WHERE"-Argument: =Where this column...
    // $like    ...is this value

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
    // DESCRIPTION:
    // Returns the number of rows that fit one condition
    // Similar to MySQLCount(), but here only 1 "WHERE"-Argument is possible
    // $db      Name of the table
    // $col     For "WHERE"-Argument: =Where this column...
    // $like    ...is this value

    require("mysql_connect.php");

    $strSQL = "SELECT * FROM $db WHERE $col LIKE '$like'";
    $rs=mysqli_query($link,$strSQL);
    $retval = mysqli_num_rows($rs);
    mysqli_close($link);
    return $retval;
}

function MySQLSave($buname)
{
    // DESCRIPTION:
    // Creates a Database Backup
    // $buname: The name of the backup

    require("mysql_connect.php");

    $dbhost     = $servername;
    $dbuser     = $username;
    $dbpwd      = $password;
    $dbname     = $database;
    $dbbackup   = 'backup/'.$buname.'.sql';

    error_reporting(0);
    set_time_limit(0);

    // ab hier nichts mehr ändern
    $conn = mysql_connect($dbhost, $dbuser, $dbpwd) or die(mysql_error());
    mysql_select_db($dbname);
    $f = fopen($dbbackup, "w");

    $tables = mysql_list_tables($dbname);
    while ($cells = mysql_fetch_array($tables))
    {
        $table = $cells[0];
        $res = mysql_query("SHOW CREATE TABLE `".$table."`");
        if ($res)
        {
            $create = mysql_fetch_array($res);
            $create[1] .= ";";
            $line = str_replace("\n", "", $create[1]);
            fwrite($f, $line."\n");
            $data = mysql_query("SELECT * FROM `".$table."`");
            $num = mysql_num_fields($data);
            while ($row = mysql_fetch_array($data))
            {
                $line = "INSERT INTO `".$table."` VALUES(";
                for ($i=1;$i<=$num;$i++)
                {
                    $line .= "'".mysql_real_escape_string($row[$i-1])."', ";
                }
                $line = substr($line,0,-2);
                fwrite($f, $line.");\n");
            }
        }
    }
    fclose($f);
}

function MySQLPDSave($period = "d")
{
    // DESCRIPTION:
    // Used for frequent Database Backups
    // $period: Default: "d". the period in which backups are created
    //          d...Daily
    //          w...Weekly
    //          h...Hourly

    switch($period)
    {
        case 'w': $filename = 'dbbu_'.date("\DY-\WW"); break;
        case 'd': $filename = 'dbbu_'.date("\DY-m-d"); break;
        case 'h': $filename = 'dbbu_'.date("\DY-m-d-\HH"); break;
        default : $filename = 'dbbu_'.date("\DY-m-d"); break;
    }

    if(!file_exists('backup/'.$filename.'.sql'))
    {
        MySQLSave($filename);
    }
}


//***********************************************************************************
//**** File Functions ***************************************************************
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
                    MySQLNonQuery(str_replace('FNAME',$name,$sql));
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

?>