<?php
//***********************************************************************************
//                          PHP - Strings-Functions Library                         *
//                          Copyright 2018 Tobias Hattinger                         *
//***********************************************************************************
//                                      Contains:                                   *
// String-Functions                                                                 *
//      •SubStringFind  (return: bool)                                              *
//      •XSubStringFind (return: bool)                                              *
//      •StartsWith     (return: bool)                                              *
//      •EndsWith       (return: bool)                                              *
//      •SReplace       (return: string)                                            *
//***********************************************************************************

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

function SReplace($string)
{
    // DESCRIPTION:
    // Formats a given string so it is save for URL-names etc.
    // $string  The string that should be formated

    // Replacing "Ä,ä,Ö,ö,Ü,ü,ß" and "-" (HTML-Characters)
    $sstr = str_replace(' ','-',$string);
    $sstr = str_replace('&Auml;','AE',$sstr);
    $sstr = str_replace('&auml;','ae',$sstr);
    $sstr = str_replace('&Ouml;','OE',$sstr);
    $sstr = str_replace('&ouml;','oe',$sstr);
    $sstr = str_replace('&Uuml;','UE',$sstr);
    $sstr = str_replace('&uuml;','ue',$sstr);
    $sstr = str_replace('&szlig;','ss',$sstr);

    // Replacing "Ä,ä,Ö,ö,Ü,ü,ß" (UTF-Characters/Database)
    $sstr = str_replace('Ã„','AE',$sstr);
    $sstr = str_replace('Ã¤','ae',$sstr);
    $sstr = str_replace('Ã–','OE',$sstr);
    $sstr = str_replace('Ã¶','oe',$sstr);
    $sstr = str_replace('Ãœ','UE',$sstr);
    $sstr = str_replace('Ã¼','ue',$sstr);
    $sstr = str_replace('ÃŸ','ss',$sstr);

    // Remove everything but Alphanumeric letters and numbers and "-"
    $sstr = preg_replace('/[^0-9A-Za-z-\|]/', '', $sstr);

    return $sstr;
}

function TrimText($input, $length, $ellipses = true, $strip_html = true)
{
    //strip tags, if desired
    if ($strip_html)
    {
        $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length)
    {
        return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);

    //add ellipses (...)
    if ($ellipses)
    {
        $trimmed_text .= '...';
    }

    return $trimmed_text;
}

function str_replace_first($search, $replace, $subject)
{
    $search = '/'.preg_quote($search, '/').'/';

    return preg_replace($search, $replace, $subject, 1);
}

?>