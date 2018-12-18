<?php

class MySQL
{
##########################################################################################

    private static $sqlConnectionLink;

    private static $databaseHost;
    private static $databaseUser;
    private static $databasePass;
    private static $databaseName;

    private static $databaseBackupPath;

##########################################################################################

    public static function init()
    {
        require("mysql.lib.config.php");

        self::$databaseHost = $sqlConfigDatabaseHost;
        self::$databaseUser = $sqlConfigDatabaseUser;
        self::$databasePass = $sqlConfigDatabasePass;
        self::$databaseName = $sqlConfigDatabaseName;

        self::$databaseBackupPath = $sqlConfigBackupPath;

        self::$sqlConnectionLink = mysqli_connect($sqlConfigDatabaseHost,$sqlConfigDatabaseUser,$sqlConfigDatabasePass,$sqlConfigDatabaseName) OR die("<br><br><b>Error in mysql.lib.php :</b> Could not connect to Database (Code 1)<br><br>");
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
            else die("<b>Not enought parameters provided!</b> <br> <b>Provided: </b> ".strlen($paramTypeList)." <br><b>Required:</b> $paramAmt");
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

    public static function Cluster($sqlStatement,$parameterTypes="", &...$sqlParameters)
    {
        $rowArray = array();

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

        while($row = $result->fetch_assoc()) array_push($rowArray,$row);

        $stmt->close();

        return $rowArray;
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

    public static function Save($backUpName)
    {
        $host = self::$databaseHost;
        $user = self::$databaseUser;
        $pass = self::$databasePass;
        $name = self::$databaseName;

        $return = '';

        $tables = '*';

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
            $result = self::$mysqli->query('SELECT * FROM '.$table);
            $num_fields = $result->field_count;

            $return.= 'DROP TABLE '.$table.';';

            $rs2 = self::$mysqli->query('SHOW CREATE TABLE '.$table);
            $row2 = $rs2->fetch_row();

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
        $handle = fopen(self::$databaseBackupPath.$backUpName.'.sql','w+');
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

        if(!file_exists(self::$databaseBackupPath.$filename.'.sql'))
        {
            self::Save($filename);
        }
    }

##########################################################################################
}
MySQL::init();

?>