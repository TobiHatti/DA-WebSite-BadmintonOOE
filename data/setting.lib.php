<?php

class Setting
{
##########################################################################################

    private static $sqlConnectionLink;

##########################################################################################


    public static function init()
    {

    }


##########################################################################################

    public static function SetSQLLink($link)
    {
        self::$sqlConnectionLink = $link;
    }

    public static function Get($setting)
    {

        $stmt = self::$sqlConnectionLink->prepare("SELECT value FROM settings WHERE setting = ?");
        $stmt->bind_param('s', $setting);
        $stmt->execute();
        $result = $stmt->get_result();
        $value = $result->fetch_array();
        $stmt->close();

        switch($value[0])
        {
            case 'true':    return true;
            case 'false':   return false;
            default:        return $value[0];
        }
    }

    public static function Set($setting,$value)
    {
        $stmt = self::$sqlConnectionLink->prepare("SELECT value FROM settings WHERE setting = ?");
        $stmt->bind_param('s', $setting);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();

        if($count!=0) $stmt = self::$sqlConnectionLink->prepare("UPDATE settings SET value = ? WHERE setting = ?");
        else $stmt = self::$sqlConnectionLink->prepare("INSERT INTO settings (value,setting) VALUES (?,?)");

        $stmt->bind_param('ss', $value, $setting);
        $stmt->execute();
        $stmt->close();
    }

    public static function Increment($setting,$resetLimit = "")
    {
        if($resetLimit != "" AND self::Get($setting)>=$resetLimit) self::Set($setting,0);

        $valueBeforeIncrement = self::Get($setting);
        self::Set($setting,$valueBeforeIncrement + 1);

        return $valueBeforeIncrement + 1;
    }

    public static function Decrement($setting)
    {
        $valueBeforeDecrement = self::Get($setting);

        self::Set($setting,$valueBeforeDecrement - 1);
        return $valueBeforeDecrement - 1;
    }

##########################################################################################
}
Setting::init();

?>