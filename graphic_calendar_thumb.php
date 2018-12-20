<?php
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    error_reporting(E_ALL ^ E_NOTICE);

    require("headerincludes.php");

    echo '
        <!DOCTYPE html>
        <html>
            <head>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
                <link rel="stylesheet" type="text/css" href="/css/style.css">
                <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
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

    $showZAinAG = Setting::Get("ShowZAinAG");

    $thisMontAndYear = $yr.'-'.str_pad($mo,2,0,STR_PAD_LEFT).'-%';

    $zaTSMonthCluster = MySQL::Cluster("SELECT * FROM zentralausschreibungen WHERE act_timespan = '1' AND (date_begin LIKE ? OR date_end LIKE ?)",'ss',$thisMontAndYear,$thisMontAndYear);
    $zaSiMonthCluster = MySQL::Cluster("SELECT * FROM zentralausschreibungen WHERE act_timespan = '0' AND date_begin LIKE ?",'s',$thisMontAndYear);

    $agTSMonthCluster = MySQL::Cluster("SELECT * FROM agenda WHERE isTimespan = '1' AND (date_begin LIKE ? OR date_end LIKE ?)",'ss',$thisMontAndYear,$thisMontAndYear);
    $agSiMonthCluster = MySQL::Cluster("SELECT * FROM agenda WHERE isTimespan = '0' AND date_begin LIKE ?",'s',$thisMontAndYear);

    $categoryColor = array(
    "Landesmeisterschaft" => Setting::Get("ColorLandesmeisterschaft"),
    "Doppelturnier" => Setting::Get("ColorDoppelturnier"),
    "Nachwuchs" => Setting::Get("ColorNachwuchs"),
    "SchuelerJugend" => Setting::Get("ColorSchuelerJugend"),
    "Senioren" => Setting::Get("ColorSenioren"),
    "Training" => Setting::Get("ColorTraining"));

//========================================================================================
// Preparing Layout-Array
//========================================================================================


    $designDataGrid = array_fill(0,31,array(NULL,NULL,NULL,NULL,NULL,NULL));

    for($ddg = 0 ; $ddg < 31 ; $ddg++)
    {
//========================================================================================

        // Set Positions for Multi-Day ZA's
        foreach($zaTSMonthCluster AS $dateData)
        {
            // Get date-parts
            $datePartsBegin = explode('-',$dateData['date_begin']);
            $datePartsEnd = explode('-',$dateData['date_end']);

            // Check if date maches day-slot
            if($datePartsBegin[2] == $ddg + 1)
            {
                //calculate timespan
                $timespan =  $datePartsEnd[2] - $datePartsBegin[2] + 1;

                // Check avaiable layers from bottom up
                for($lctr = 5 ; $lctr >= 0 ; $lctr--)
                {
                    $layerValid = true;

                    for($ldctr = 0 ; $ldctr < $timespan ; $ldctr++) if($designDataGrid[$ddg + $ldctr][$lctr] != NULL) $layerValid = false;
                    if($layerValid) $layer = $lctr;
                }

                // Fill designdatagrid with identifiers
                for($dctr = 0 ; $dctr < $timespan ; $dctr++) $designDataGrid[$ddg + $dctr][$layer] = 'ZA-'.$dateData['id'];
            }
        }

//========================================================================================

        // Set Positions for Multi-Day AG's
        foreach($agTSMonthCluster AS $dateData)
        {
            // Get date-parts
            $datePartsBegin = explode('-',$dateData['date_begin']);
            $datePartsEnd = explode('-',$dateData['date_end']);

            // Check if date maches day-slot
            if($datePartsBegin[2] == $ddg + 1)
            {
                //calculate timespan
                $timespan =  $datePartsEnd[2] - $datePartsBegin[2] + 1;

                // Check avaiable layers from bottom up
                for($lctr = 5 ; $lctr >= 0 ; $lctr--)
                {
                    $layerValid = true;

                    for($ldctr = 0 ; $ldctr < $timespan ; $ldctr++) if($designDataGrid[$ddg + $ldctr][$lctr] != NULL) $layerValid = false;
                    if($layerValid) $layer = $lctr;

                }


                // Fill designdatagrid with identifiers
                for($dctr = 0 ; $dctr < $timespan ; $dctr++) $designDataGrid[$ddg + $dctr][$layer] = 'AG-'.$dateData['id'];
            }
        }

//========================================================================================

        // Set Positions for Single-Day ZA's
        foreach($zaSiMonthCluster AS $dateData)
        {
            // Get date-parts
            $datePartsBegin = explode('-',$dateData['date_begin']);

            // Check if date maches day-slot
            if($datePartsBegin[2] == $ddg + 1)
            {

                // Check avaiable layers from bottom up
                for($lctr = 5 ; $lctr >= 0 ; $lctr--)
                {
                    $layerValid = true;
                    if($designDataGrid[$ddg][$lctr] != NULL) $layerValid = false;

                    if($layerValid) $layer = $lctr;
                }

                // Fill designdatagrid with identifiers
                $designDataGrid[$ddg][$layer] = 'ZA-'.$dateData['id'];
            }
        }

//========================================================================================

        // Set Positions for Single-Day AG's
        foreach($agSiMonthCluster AS $dateData)
        {
            // Get date-parts
            $datePartsBegin = explode('-',$dateData['date_begin']);

            // Check if date maches day-slot
            if($datePartsBegin[2] == $ddg + 1)
            {

                // Check avaiable layers from bottom up
                for($lctr = 5 ; $lctr >= 0 ; $lctr--)
                {
                    $layerValid = true;
                    if($designDataGrid[$ddg][$lctr] != NULL) $layerValid = false;

                    if($layerValid) $layer = $lctr;
                }

                // Fill designdatagrid with identifiers
                $designDataGrid[$ddg][$layer] = 'AG-'.$dateData['id'];
            }
        }

//========================================================================================
    }

//========================================================================================
//========================================================================================
//========================================================================================

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



//========================================================================================
// START OF CELL (DATA)
//========================================================================================

            $dayParts = explode('-',$days[$curr]['dat']);
            $day = intval($dayParts[2]);

            // Run through all necessary layers
            for($dctr = 0 ; $dctr <  6 ; $dctr++)
            {
                $dataParts = explode("-",$designDataGrid[$day - 1][$dctr]);



                // Check if entry exists for layer
                if($dataParts[0] == "ZA" OR $dataParts[0] == "AG")
                {

                    if($dataParts[0] == "ZA")
                    {
                        $calData = MySQL::Row("SELECT *,CONCAT_WS(' ',title_line1,title_line2) AS displayTitle FROM zentralausschreibungen WHERE id = ?",'i',$dataParts[1]);
                        $displayTitle = $calData['displayTitle'];
                    }

                    if($dataParts[0] == "AG")
                    {
                        $calData = MySQL::Row("SELECT *,title AS displayTitle FROM agenda WHERE id = ?",'i',$dataParts[1]);
                        $displayTitle = $calData['displayTitle'];
                    }

                    echo '
                        <a target="_parent" style="text-decoration:none;" href="/kalender/event/'.$dataParts[0].'-'.$calData['id'].'/'.$calData['date_begin'].'">
                            <span style="cursor: help;color: '.(($calData['category']!="") ? $categoryColor[$calData['category']] : '#000000').';" title="'.$calData['displayTitle'].'">&#9679;</span>
                        </a>
                    ';
                }
            }

//========================================================================================
// END OF CELL
//========================================================================================

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