<?php
//***********************************************************************************
//                          PHP - Property-Functions Library                        *
//                          Copyright 2018 Tobias Hattinger                         *
//***********************************************************************************
//                                      Contains:                                   *
// Property-Functions                                                               *
//      •SetProperty    (return: void)                                              *
//      •GetProperty    (return: string)                                            *
//      •IncProperty    (return: int)                                               *
//      •DecProperty    (return: int)                                               *
//***********************************************************************************

function SetProperty($key,$value)
{
    // DESCRIPTION:
    // Sets a Property saved in the Database
    // If property does not exist, the entry is created in the Database,
    // otherwise it just updates the value
    // $key     Keyword of the property (e.g. keyword="site_name" returns "My Cool Website")
    // $value   The value the property should take

    if(FetchCount("settings","setting",$key) != 0) MySQLNonQuery("UPDATE settings SET value = '$value' WHERE setting = '$key'");
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

?>