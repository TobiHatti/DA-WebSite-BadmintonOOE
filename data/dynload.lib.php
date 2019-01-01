<?php

class DynLoad
{
    private static $sqlConnectionLink;
    private static $libDirectory;

//########################################################################################

    public static function init()
    {
        self::$libDirectory = ltrim(str_replace('\\','',str_replace(getcwd(),'',__DIR__)),'/');
    }

//########################################################################################

    public static function SetSQLLink($link)
    {
        self::$sqlConnectionLink = $link;

    }

    public static function Link()
    {
        return '<script src="/'.self::$libDirectory.'/dynload-source/dynload.lib.js"></script>';
    }

    public static function Start($frameIDCtr)
    {
        return '<iframe hidden src="/'.self::$libDirectory.'/dynload-source/dynloadFrame.php" id="dynloadFrame'.$frameIDCtr.'"></iframe>';
    }

    public static function Scalar($sqlStatement)
    {
        $stmt = self::$sqlConnectionLink->prepare($sqlStatement);
        $stmt->execute();
        $result = $stmt->get_result();
        $value = $result->fetch_array();
        $stmt->close();

        return $value[0];
    }

    public static function Count($sqlStatement)
    {
        $stmt = self::$sqlConnectionLink->prepare($sqlStatement);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();

        return $count;
    }

    public static function Cluster($sqlStatement)
    {
        $rowArray = array();
        $stmt = self::$sqlConnectionLink->prepare($sqlStatement);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) array_push($rowArray,$row);
        $stmt->close();

        return $rowArray;
    }

    public static function Exist($sqlStatement)
    {

        $stmt = self::$sqlConnectionLink->prepare($sqlStatement);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();

        return $count!=0;
    }
    
}
DynLoad::init();

?>