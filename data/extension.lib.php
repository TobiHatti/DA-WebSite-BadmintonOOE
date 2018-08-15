<?php
//***********************************************************************************
//                             PHP - Extension-Functions                            *
//                          Copyright 2018 Tobias Hattinger                         *
//***********************************************************************************
//                                      Contains:                                   *
// PHP-Extension Functions                                                          *
//      •Redirect       (return: void)                                              *
//      •ThisPage       (return: string)                                            *
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

?>