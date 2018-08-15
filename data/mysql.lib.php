<?php
//***********************************************************************************
//                           PHP - MySQL-Functions Library                          *
//                          Copyright 2018 Tobias Hattinger                         *
//***********************************************************************************
//                                      Contains:                                   *
// MySQL-Functions                                                                  *
//      •MySQLNonQuery  (return: bool)                                              *
//      •MySQLSkalar    (return: string)                                            *
//      •MySQLCount     (return: int)                                               *
//      •MySQLExists    (return: bool)                                              *
//      •Fetch          (return: string)                                            *
//      •FetchCount     (return: int)                                               *
//      •MySQLSave      (return: int)                                               *
//      •MySQLPDSave    (return: int)                                               *
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

?>