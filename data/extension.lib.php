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

function ThisPage($urlex = '')
{
    // DESCRIPTION:
    // Can be used in the "action"-argument of a <form>-Tag
    // or in combination with the Redirect()-Function
    // $urlex   Add or remove a part of the URL
    //          "+name=value"   Adds something to the URL
    //          "-name=value"   Removes something from the URL
    //          "!name"         Removes the Get-Query that starts with "name"
    // Returns the current pagename

    $thisPage = basename($_SERVER["REQUEST_URI"], '.php');

    if($urlex != '')
    {
        if(StartsWith($urlex, '+'))
        {
            $nUrl = ltrim($urlex,'+');
            if(SubStringFind($thisPage,'?'))
            {
                $thisPage .= "&".$nUrl;
            }
            else
            {
                $thisPage .= "?".$nUrl;
            }
        }
        if(StartsWith($urlex, '-'))
        {
            $nUrl = ltrim($urlex,'-');
            $pageParts = explode('?',$thisPage);

            if(isset($pageParts[1]))
            {
                $getParts = explode('&',$pageParts[1]);
                $newPage = $pageParts[0];

                $firstAddition = true;
                foreach ($getParts as $g)
                {
                    if($g != $nUrl)
                    {
                        if($firstAddition)
                        {
                            $newPage .= '?'.$g;
                            $firstAddition = false;
                        }
                        else $newPage .= '&'.$g;
                    }
                }
                $thisPage = $newPage;
            }
        }
        if(StartsWith($urlex, '!'))
        {
            $nUrl = ltrim($urlex,'!');
            $pageParts = explode('?',$thisPage);

            if(isset($pageParts[1]))
            {
                $getParts = explode('&',$pageParts[1]);
                $newPage = $pageParts[0];

                $firstAddition = true;
                foreach ($getParts as $g)
                {
                    if(!SubStringFind($g,$nUrl))
                    {
                        if($firstAddition)
                        {
                            $newPage .= '?'.$g;
                            $firstAddition = false;
                        }
                        else $newPage .= '&'.$g;
                    }
                }
                $thisPage = $newPage;
            }
        }
    }

    return  $thisPage;
}

function GDate($format,$date="")
{
    // DESCRIPTION:
    // Re-Formats date() functions and outputs them with strftime
    // to display Months, Days etc. in German

    $format = str_replace('d','%d',$format);
    $format = str_replace('D','%a',$format);
    $format = str_replace('j','%e',$format);
    $format = str_replace('l','%A',$format);
    $format = str_replace('N','%u',$format);
    $format = str_replace('w','%w',$format);
    $format = str_replace('z','%j',$format);
    $format = str_replace('W','%V',$format);
    $format = str_replace('F','%B',$format);
    $format = str_replace('m','%m',$format);
    $format = str_replace('M','%h',$format);
    $format = str_replace('o','%G',$format);
    $format = str_replace('Y','%Y',$format);
    $format = str_replace('y','%y',$format);
    $format = str_replace('a','%P',$format);
    $format = str_replace('A','%p',$format);
    $format = str_replace('g','%l',$format);
    $format = str_replace('G','%k',$format);
    $format = str_replace('h','%I',$format);
    $format = str_replace('H','%H',$format);
    $format = str_replace('i','%M',$format);
    $format = str_replace('s','%S',$format);
    $format = str_replace('O','%z',$format);
    $format = str_replace('T','%Z',$format);

    setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
    $ndate = ($date=="") ? strtotime(date("Y-m-d H:i:s")) : $date;
    return strftime($format, $ndate);
}

?>