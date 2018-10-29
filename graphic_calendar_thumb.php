<?php
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    error_reporting(E_ALL ^ E_NOTICE);

    require("data/mysql_connect.php");

    require("data/extension.lib.php");
    require("data/file.lib.php");
    require("data/mysql.lib.php");
    require("data/property.lib.php");
    require("data/string.lib.php");

    require("data/functions.php");

    echo '
        <!DOCTYPE html>
        <html>
            <head>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
                <link rel="stylesheet" type="text/css" href="/css/style.css">
                <meta charset="utf-8">
            </head>
            <body>
                <div class="iframe_content" style="height: 600px;">
    ';



    // date ok flag
    $dateok = false;

    // parse parameter
    if (isset($_GET['day']))
    {
        list($yr, $mo, $da) = explode('-', $_GET['day']);
        $yr = intval($yr);
        $mo = intval($mo);
        $da = intval($da);
        if (checkdate($mo, $da, $yr)) $dateok = true;
    }

    // if invalid date selected then selected date = today
    if (!$dateok)
    {
        $mo = date('m');
        $da = date('d');
        $yr = date('Y');
    }

    $offset = date('w', mktime(0,0,0,$mo,1,$yr));
    // we must have a value in range 1..7
    if ($offset == 0) $offset = 7;

    // days in month
    $nd = date('d', mktime(0,0,0,$mo+1,0,$yr));

    // days array
    $days = array();

    // reset array
    for ($i=0;$i<=42;$i++) $days[$i]['out']= '&nbsp;';

    // fill days array
    // valid days contain data, invalid days are left blank

    $calcDate = $yr.'-'.str_pad($mo,2,0,STR_PAD_LEFT).'-'.str_pad($da,2,0,STR_PAD_LEFT);

    $j=1;
    for ($i=$offset;$i<=($offset+$nd-1);$i++)
    {
        $day = $j++;
        $date = $yr.'-'.str_pad($mo,2,0,STR_PAD_LEFT).'-'.str_pad($day,2,0,STR_PAD_LEFT);
        $days[$i]['out']= '<a target="_parent" href="/kalender/datum/'.$date.'">'.$day.'.</a>';
        $days[$i]['dat']= $date;
    }



    // output table
    echo '
            <table class="calendar_table_s">
            <tr>
                <td colspan="1"><a href="?day='.date('Y-m-d', mktime(0,0,0,$mo,$da,$yr-1)).'">&laquo;</a></td>
                <td colspan="5"><b>'.$yr.'</b></td>
                <td colspan="1"><a href="?day='.date('Y-m-d', mktime(0,0,0,$mo,$da,$yr+1)).'">&raquo;</a></td>
            </tr>
            <tr>
                <td colspan="1"><a href="?day='.date('Y-m-d', mktime(0,0,0,$mo,0,$yr)).'">&laquo;</a></td>
                <td colspan="5"><b>'.str_replace('Mrz','M&auml;rz',SReplace(strftime("%B",strtotime($yr.'-'.$mo.'-'.$da)))).'</b></td>
                <td colspan="1"><a href="?day='.date('Y-m-d', mktime(0,0,0,$mo+1,1,$yr)).'">&raquo;</a></td>
            </tr>
    ';


    $cntr = 1; // day printing counter
    for ($i=1;$i<=6;$i++)
    {
        echo '<tr>';
        for ($j=1;$j<=7;$j++)
        {
            $curr = $cntr++;

            $accent = ($days[$curr]['dat'] == "") ? 'blank' : (($curr % 2 == 0) ? 'accent1' : 'accent2');

            $style = (date("Y-m-d") == $days[$curr]['dat']) ? 'bold' : 'normal';
            $today = (date("Y-m-d") == $days[$curr]['dat']) ? 'today' : '';

            echo '
                <td class="'.$accent.' '.$today.'" style="font-weight: '.$style.'">
                <span>'.$days[$curr]['out'].'</span>
            ';

            $curDate = $days[$curr]['dat'];
            $strSQL = "SELECT id,titel,kategorie,date FROM agenda WHERE date = '$curDate' LIMIT 0,1";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs))
            {
                echo '
                    <a target="_parent" style="text-decoration:none;" href="/kalender/event/AG'.$row['id'].'/'.$row['date'].'">
                        <span style="cursor: help;color: '.(($row['kategorie']!="") ? GetProperty("Color".$row['kategorie']) : '#000000').';" title="'.$row['titel'].'">&#9679;</span>
                    </a>
                ';
            }
            
            if(GetProperty("ShowZAinAG")=='true')
            {
                $strSQL = "SELECT * FROM zentralausschreibungen WHERE date_begin = '$curDate' LIMIT 0,1";
                $rs=mysqli_query($link,$strSQL);
                while($row=mysqli_fetch_assoc($rs))
                {
                    echo '
                        <a target="_parent" style="text-decoration:none;" href="/kalender/event/ZA'.$row['id'].'/'.$row['date_begin'].'">
                            <span style="cursor: help;color: '.(($row['kategorie']!="") ? GetProperty("Color".$row['kategorie']) : '#000000').';" title="'.$row['title_line1'].'">&#9679;</span>
                        </a>
                    ';
                }
            }

            echo'
                </td>
            ';
        }
        echo '</tr>';
    }

    echo '
        </table>
    ';

    echo '
                </div>
            </body>
        </html>
    ';

?>