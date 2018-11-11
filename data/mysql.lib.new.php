<?php

class SQL
{
##########################################################################################

    private static $sqlConnectionLink;

##########################################################################################

    public static function init()
    {
        require("_mysqlConDat.php");
        self::$sqlConnectionLink = mysqli_connect(getenv("MYSQLDB_SERVER"),getenv("MYSQLDB_USERNAME"),getenv("MYSQLDB_PASSWORD"),getenv("MYSQLDB_DBNAME")) or die("MySQL Error");
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
            if($paramAmt == strlen($paramTypeList)) $mySQLParamTypes = $paramTypeList;
            else die("<b>Nicht gen&uuml;gend Typ-Parameter &uuml;bergeben!</b> <br> <b>&Uuml;bergeben:</b> ".strlen($paramTypeList)." <br><b>Ben&ouml;tigt:</b> $paramAmt");
        }

        return $mySQLParamTypes;
    }

##########################################################################################

    public static function Query($sqlStatement,$parameterTypes="", &...$sqlParameters)
    {

    }

    public static function NonQuery($sqlStatement,$parameterTypes="", &...$sqlParameters)
    {
        // Parameter-Count
        $parameterAmount = func_num_args() - 2;

        // Get Parameter-Type list
        $parameterTypeList = self::GetParamTypeList($parameterTypes,$parameterAmount);

        // Prepare SQL-Query
        $stmt = self::$sqlConnectionLink->prepare($sqlStatement);

        // Bind Parameters to Query
        call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterTypeList), $sqlParameters));

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
        call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterTypeList), $sqlParameters));

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
        call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterTypeList), $sqlParameters));

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
        call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterTypeList), $sqlParameters));

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
        call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterTypeList), $sqlParameters));

        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();

        return $count!=0;
    }

##########################################################################################

    public static function Fetch($table,$getColumn,$whereColumn,$whereColumnValue)
    {

    }

    public static function FetchCount($table,$whereColumn,$whereColumnValue)
    {

    }

    public static function FetchArray($table,$whereColumn,$whereColumnValue)
    {

    }

##########################################################################################

    public static function Save($backUpName)
    {

    }

    public static function PeriodicSave($interval)
    {

    }

##########################################################################################
}
SQL::init();

?>