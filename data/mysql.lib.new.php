<?php

class SQL
{
##########################################################################################

    private static $sqlConnectionLink;
    private static $mysqli;

    private static $databaseHost;
    private static $databaseUser;
    private static $databasePass;
    private static $databaseName;

##########################################################################################

    public static function init()
    {
        require("_mysqlConDat.php");
        self::$sqlConnectionLink = mysqli_connect(getenv("MYSQLDB_SERVER"),getenv("MYSQLDB_USERNAME"),getenv("MYSQLDB_PASSWORD"),getenv("MYSQLDB_DBNAME")) or die("MySQL Error");

        self::$mysqli = new mysqli(getenv("MYSQLDB_SERVER"),getenv("MYSQLDB_USERNAME"),getenv("MYSQLDB_PASSWORD"),getenv("MYSQLDB_DBNAME")) or die("MySQL Error");

        self::$databaseHost = getenv("MYSQLDB_SERVER");
        self::$databaseUser = getenv("MYSQLDB_USERNAME");
        self::$databasePass = getenv("MYSQLDB_PASSWORD");
        self::$databaseName = getenv("MYSQLDB_DBNAME");
    }

    public static function Close()
    {
        self::$mysqli->close();
    }

##########################################################################################

    private static function GetParamTypeList($paramTypeList,$paramAmt)
    {
        if(substr($paramTypeList,0,1) == "@")
        {
            $broadcastType = str_replace("@","",$paramTypeList);
            $mySQLParamTypes = '';

            for($i=0;$i<$paramAmt;$i++) $mySQLParamTypes .= $broadcastType;
        }
        else
        {
            if($paramAmt == strlen($paramTypeList) OR ($paramTypeList == "" AND $paramAmt == -1)) $mySQLParamTypes = $paramTypeList;
            else die("<b>Nicht gen&uuml;gend Typ-Parameter &uuml;bergeben!</b> <br> <b>&Uuml;bergeben:</b> ".strlen($paramTypeList)." <br><b>Ben&ouml;tigt:</b> $paramAmt");
        }

        return $mySQLParamTypes;
    }

##########################################################################################

    public static function NonQuery($sqlStatement,$parameterTypes="", &...$sqlParameters)
    {
        // Parameter-Count
        $parameterAmount = func_num_args() - 2;

        // Get Parameter-Type list
        $parameterTypeList = self::GetParamTypeList($parameterTypes,$parameterAmount);

        // Prepare SQL-Query
        $stmt = self::$sqlConnectionLink->prepare($sqlStatement);

        // Bind Parameters to Query
        if($parameterTypes != "") call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterTypeList), $sqlParameters));

        $stmt->execute();
        $stmt->close();
    }

    public static function Scalar($sqlStatement,$parameterTypes="", &...$sqlParameters)
    {
        // Parameter-Count
        $parameterAmount = func_num_args() - 2;

        // Get Parameter-Type list
        $parameterTypeList = self::GetParamTypeList($parameterTypes,$parameterAmount);

        // Prepare SQL-Query
        $stmt = self::$sqlConnectionLink->prepare($sqlStatement);

        // Bind Parameters to Query
        if($parameterTypes != "") call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterTypeList), $sqlParameters));

        $stmt->execute();
        $result = $stmt->get_result();
        $value = $result->fetch_array();
        $stmt->close();

        return $value[0];
    }

    public static function Count($sqlStatement,$parameterTypes="", &...$sqlParameters)
    {
        // Parameter-Count
        $parameterAmount = func_num_args() - 2;

        // Get Parameter-Type list
        $parameterTypeList = self::GetParamTypeList($parameterTypes,$parameterAmount);

        // Prepare SQL-Query
        $stmt = self::$sqlConnectionLink->prepare($sqlStatement);

        // Bind Parameters to Query
        if($parameterTypes != "") call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterTypeList), $sqlParameters));

        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();

        return $count;
    }

    public static function Row($sqlStatement,$parameterTypes="", &...$sqlParameters)
    {
        // Parameter-Count
        $parameterAmount = func_num_args() - 2;

        // Get Parameter-Type list
        $parameterTypeList = self::GetParamTypeList($parameterTypes,$parameterAmount);

        // Prepare SQL-Query
        $stmt = self::$sqlConnectionLink->prepare($sqlStatement);

        // Bind Parameters to Query
        if($parameterTypes != "") call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterTypeList), $sqlParameters));

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array();
        $stmt->close();

        return $row;
    }

    public static function Exist($sqlStatement,$parameterTypes="", &...$sqlParameters)
    {
        // Parameter-Count
        $parameterAmount = func_num_args() - 2;

        // Get Parameter-Type list
        $parameterTypeList = self::GetParamTypeList($parameterTypes,$parameterAmount);

        // Prepare SQL-Query
        $stmt = self::$sqlConnectionLink->prepare($sqlStatement);

        // Bind Parameters to Query
        if($parameterTypes != "") call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterTypeList), $sqlParameters));

        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();

        return $count!=0;
    }

##########################################################################################

    public static function Fetch($table,$getColumn,$whereColumn,$whereColumnValue)
    {
        $sqlStatement = "SELECT $getColumn FROM $table WHERE $whereColumn LIKE '$whereColumnValue'";

        $result = self::$mysqli->query($sqlStatement);
        $row = $result->fetch_assoc();



        return $row[$getColumn];
    }

    public static function FetchCount($table,$whereColumn,$whereColumnValue)
    {
        $sqlStatement = "SELECT * FROM $table WHERE $whereColumn LIKE '$whereColumnValue'";

        $result = self::$mysqli->query($sqlStatement);
        $count = $result->num_rows;

        return $count;
    }

    public static function FetchRow($table,$whereColumn,$whereColumnValue)
    {
        $sqlStatement = "SELECT * FROM $table WHERE $whereColumn LIKE '$whereColumnValue'";

        $result = self::$mysqli->query($sqlStatement);
        $row = $result->fetch_assoc();

        return $row;
    }

##########################################################################################

    public static function Save($backUpName)
    {
        $host = self::$databaseHost;
        $user = self::$databaseUser;
        $pass = self::$databasePass;
        $name = self::$databaseName;

        $path = "backup/";

        $return = '';

        $tables = '*';

        //$link = mysql_connect($host,$user,$pass);
        //mysql_select_db($name,$link);

        self::$mysqli->select_db($name);

        //get all of the tables
        if($tables == '*')
        {
            $tables = array();
            $result = self::$mysqli->query('SHOW TABLES');
            while($row = $result->fetch_row())
            {
                $tables[] = $row[0];
            }
        }
        else
        {
            $tables = is_array($tables) ? $tables : explode(',',$tables);
        }

        //cycle through
        foreach($tables as $table)
        {
            //$result = mysql_query('SELECT * FROM '.$table);
            $result = self::$mysqli->query('SELECT * FROM '.$table);
            //$num_fields = mysql_num_fields($result);
            $num_fields = $result->field_count;

            $return.= 'DROP TABLE '.$table.';';

            $rs2 = self::$mysqli->query('SHOW CREATE TABLE '.$table);
            $row2 = $rs2->fetch_row();

            //$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
            $return.= "\n\n".$row2[1].";\n\n";

            for ($i = 0; $i < $num_fields; $i++)
            {
                while($row = $result->fetch_row())
                {
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                    for($j=0; $j < $num_fields; $j++)
                    {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = preg_replace("/\n/","/\\n/",$row[$j]);
                        if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                        if ($j < ($num_fields-1)) { $return.= ','; }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }

        //save file
        $handle = fopen($path.$backUpName.'.sql','w+');
        fwrite($handle,$return);
        fclose($handle);
    }


    public static function PeriodicSave($period = "d")
    {
        switch($period)
        {
            case 'w': $filename = 'dbbu_'.date("\DY-\WW"); break;
            case 'd': $filename = 'dbbu_'.date("\DY-m-d"); break;
            case 'h': $filename = 'dbbu_'.date("\DY-m-d-\HH"); break;
            default : $filename = 'dbbu_'.date("\DY-m-d"); break;
        }

        if(!file_exists('backup/'.$filename.'.sql'))
        {
            self::Save($filename);
        }
    }

##########################################################################################
}
SQL::init();

?>