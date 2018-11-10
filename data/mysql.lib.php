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

function MySQLNonQuery($strSQL,$dataTypes="", &...$mySQLParamValues)
{
    // DESCRIPTION:
    // Executes a standard SQL-Query such as UPDATE,DELETE,INSERT, etc.
    // Can be used in combination with "or die("error_msg");"

    // FOR PARAMETERIZED VERSION:
    // $dataTypes   Set datatype of the value ([i]int, [s]string, [f]float)
    //              Example:    "ssiiis"
    //              OR:         "@s" to set every parameter to "s"
    // Example-Usage:
    // MySQLNonQuery("INSERT INTO test (id,value,value2) VALUES ('',?,?)","@s",$value,$value2);


    require("mysql_connect.php");

    if($dataTypes == "")
    {
        // Old version without parameterized queries
        // Kept for compatibility

        $rs = mysqli_query($link,$strSQL);
        mysqli_close($link);
        return $rs;
    }
    else
    {
        // New version with parameterized queries
        $paramAmt = func_num_args() - 2;

        if(StartsWith($dataTypes,"@"))
        {
            $broadCastType = str_replace("@","",$dataTypes);
            $mySQLParamTypes = '';

            for($i=0;$i<$paramAmt;$i++) $mySQLParamTypes .= $broadCastType;
        }
        else
        {
            if($paramAmt == strlen($dataTypes)) $mySQLParamTypes = $dataTypes;
            else die("<b>Nicht gen&uuml;gend Typ-Parameter &uuml;bergeben!</b> <br> <b>&Uuml;bergeben:</b> ".strlen($dataTypes)." <br><b>Ben&ouml;tigt:</b> $paramAmt");
        }

        $stmt = $link->prepare($strSQL);

        call_user_func_array(array($stmt, "bind_param"), array_merge(array($mySQLParamTypes), $mySQLParamValues));

        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
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

function MySQLGetRow($strSQLBase)
{
    // DESCRIPTION:
    // Returns the row of a table as an array
    // behaves like a standart SQL-SELECT-Query
    // Use with the same keys as in the database:
    // e.g: $values['id']


    require("mysql_connect.php");

    $fields=array();
    $vals=array();

    $posOfFrom = strpos($strSQLBase,'FROM ') + strlen('FROM ');
    $posOfWhere = strpos($strSQLBase,' WHERE');
    $db = substr($strSQLBase,$posOfFrom,$posOfWhere-$posOfFrom);

    $strSQL = "SHOW COLUMNS FROM $db";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs)) array_push($fields,$row['Field']);

    $strSQL = $strSQLBase;
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        foreach($fields as $field)  array_push($vals,$row[$field]);
    }

    return array_combine($fields,$vals);
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
    $strSQL = "SELECT $get FROM $db WHERE $col LIKE '$like'";
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

    $strSQL = "SELECT $col FROM $db WHERE $col LIKE '$like'";
    $rs=mysqli_query($link,$strSQL);
    $retval = mysqli_num_rows($rs);
    mysqli_close($link);
    return $retval;
}

function FetchArray($db,$col,$like)
{
    // DESCRIPTION:
    // Returns the row of a table as an array
    // behaves like a standart SQL-SELECT-Query
    // Use with the same keys as in the database:
    // e.g: $values['id']
    // $db      Name of the table
    // $col     For "WHERE"-Argument: =Where this column...
    // $like    ...is this value

    require("mysql_connect.php");

    $fields=array();
    $vals=array();

    $strSQL = "SHOW COLUMNS FROM $db";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs)) array_push($fields,$row['Field']);

    $strSQL = "SELECT * FROM $db WHERE $col LIKE '$like'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        foreach($fields as $field)  array_push($vals,$row[$field]);
    }

    return array_combine($fields,$vals);
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