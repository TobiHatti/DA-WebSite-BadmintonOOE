<?php
    session_start();
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    error_reporting(E_ALL ^ E_NOTICE);

    require("headerincludes.php");

    echo '
        <!DOCTYPE html>
        <html>
            <head>
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
                <link rel="stylesheet" type="text/css" href="/css/style.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

    if(isset($_GET['datum']))
    {
        list($yr, $mo, $da) = explode('-', $_GET['datum']);
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
    $j=1;
    for ($i=$offset;$i<=($offset+$nd-1);$i++)
    {
        $day = $j++;
        $date = $yr.'-'.str_pad($mo,2,0,STR_PAD_LEFT).'-'.str_pad($day,2,0,STR_PAD_LEFT);
        $days[$i]['out']= $day.'.';
        $days[$i]['dat']= $date;
    }

    $calcDate = $yr.'-'.str_pad($mo,2,0,STR_PAD_LEFT).'-'.str_pad($da,2,0,STR_PAD_LEFT);

    // output table
    echo '
        <center>
            <table class="calendar_table">
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

            <tr>
                <td>Montag</td>
                <td>Dienstag</td>
                <td>Mittwoch</td>
                <td>Donnerstag</td>
                <td>Freitag</td>
                <td>Samstag</td>
                <td>Sonntag</td>
            </tr>
    ';


    $cntr = 1; // day printing counter

// FETCHING DATA FROM DB TO IMPROVE RUNTIME
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

    $permissionEditDate = CheckPermission("EditDate");
    $permissionDeleteDate = CheckPermission("DeleteDate");

    $dateModalInfos = '';

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

//  Determine the amount of needed Layers to save space
    for($lctr = 0 ; $lctr < 5 ; $lctr++)
    {
        for($ddg = 0 ; $ddg < 31 ; $ddg++) if($designDataGrid[$ddg][$lctr] != NULL) $lastLayer =  $lctr;
    }


    for ($i=1;$i<=6;$i++)
    {
//========================================================================================
// START OF ROW
//========================================================================================

        $listSlotManager = array(0);

        echo '<tr>';
        for ($j=1;$j<=7;$j++)
        {
//========================================================================================
// START OF CELL (GENERAL)
//========================================================================================
            $curr = $cntr++;

            $accent = ($days[$curr]['dat'] == "") ? 'blank' : (($curr % 2 == 0) ? 'accent1' : 'accent2');

            $style = (date("Y-m-d") == $days[$curr]['dat']) ? 'bold' : 'normal';
            $today = (date("Y-m-d") == $days[$curr]['dat']) ? 'today' : '';

            $selectStyle = (isset($_GET['datum']) AND $_GET['datum']==$days[$curr]['dat']) ? 'selected' : '';

            echo '
                <td class="'.$selectStyle.' '.$accent.' '.$today.'" style="font-weight: '.$style.'">
                <span>'.$days[$curr]['out'].' '.((date("Y-m-d") == $days[$curr]['dat']) ? ' - Heute' : '').'</span>
            ';

            $curDate = $days[$curr]['dat'];

//========================================================================================
// START OF CELL (DATA)
//========================================================================================

            $dayNow = intval(str_replace('.','',$days[$curr]['out']));

            echo '<table class="cellContentData">';
            // Run through all necessary layers
            for($dctr = 0 ; $dctr <= $lastLayer ; $dctr++)
            {
                $dataParts = explode("-",$designDataGrid[$dayNow - 1][$dctr]);
                $dateModalInfo = '';

                // Check if entry exists for layer
                if($dataParts[0] == "ZA" OR $dataParts[0] == "AG")
                {
//========================================================================================
                    // DATA & MODAL FOR ZA ===============================================
//========================================================================================
                    if($dataParts[0] == "ZA")
                    {
                        $calData = MySQL::Row("SELECT *,CONCAT_WS(' ',title_line1,title_line2) AS displayTitle FROM zentralausschreibungen WHERE id = ?",'i',$dataParts[1]);
                        $displayTitle = '';

                        // MODAL =========================================================
                        $dateModalInfo = '
                            <div class="modal_wrapper" id="calenderInfo'.$designDataGrid[$dayNow - 1][$dctr].'">
                                <a href="#c"><div class="modal_bg"></div></a>
                                <div class="modal_container" style="width: 50%; height: 60%;">
                                    <a href="#c"><img src="/content/cross2.png" alt="" class="close_cross"/></a>


                                     <div style="border-left: 3px solid '.(($calData['category']!="") ? $categoryColor[$calData['category']] : '000000').'; padding-left: 5px;">
                                        <a href="#exportZA'.$calData['id'].'"><button style="float: right; margin-right: 30px;"><i class="fas fa-file-export"></i> Exportieren</button></a>
                                        <span style="color: #696969"><i>Zentralausschreibung</i></span>
                                        <h2><u>'.$calData['title_line1'].'<br>'.$calData['title_line2'].'</u></h2>
                                        <h4>'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($calData['date_begin']))).'</h4>
                                        <h4>'.$calData['uhrzeit'].'</h4>
                                        <p>
                                        ';

                                        if($calData['size']=='full')  $dateModalInfo .= '<div class="za_data">'.ShowZATable($calData['id']).'</div>';
                                        else
                                        {
                                            $dateModalInfo .= '
                                                <div class="za_data">
                                                    <table>
                                                        <tr>
                                                            <td>Ort:</td>
                                                            <td>'.$calData['location'].'</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            ';
                                        }

                                        $dateModalInfo .= '
                                        </p>
                                    </div>
                                </div>
                            </div>



                            <div class="modal_wrapper" id="exportZA'.$calData['id'].'">
                                <a href="#c">
                                    <div class="modal_bg"></div>
                                </a>
                                <div class="modal_container" style="width: 400px; height: 200px;">
                                    <h3>Exportieren</h3>
                                    <center>
                                        <form action="/kalender" method="post" accept-charset="utf-8" enctype="multipart/form-data" target="_top">
                                            <button type="submit" class="cel_m" name="export_csv" value="ZA'.$calData['id'].'"><i class="fa fa-file-excel-o" style="float: left;"></i> Als .csv Exportieren</button>
                                            <br>
                                            <button type="submit" class="cel_m" name="export_ics" value="ZA'.$calData['id'].'"><i class="fas fa-file" style="float: left;"></i>Als .ics Exportieren</button>
                                        </form>
                                    </center>
                                </div>
                            </div>
                        ';

                        // Determine Cell-Style and Text =================================
                        if($calData['act_timespan'])
                        {
                            if($curDate == $calData['date_begin'])
                            {
                                $cellStyle = "timespanStart";
                                $displayTitle = $calData['displayTitle'];

                            }
                            else if($curDate == $calData['date_end'])
                            {
                                $cellStyle = "timespanEnd";
                                $dateModalInfo = '';
                            }
                            else
                            {
                                $cellStyle = "timespanMiddle";
                                $dateModalInfo = '';
                            }

                            if($j == 1) $displayTitle = $calData['displayTitle'];
                        }
                        else
                        {
                            $cellStyle = "timespanSingle";
                            $displayTitle = $calData['displayTitle'];
                        }
                    }

//========================================================================================
                    // DATA & MODAL FOR AG ===============================================
//========================================================================================

                    if($dataParts[0] == "AG")
                    {
                        $calData = MySQL::Row("SELECT *,title AS displayTitle FROM agenda WHERE id = ?",'i',$dataParts[1]);
                        $displayTitle = '';

                        // MODAL =========================================================
                        $dateModalInfo = '
                            <div class="modal_wrapper" id="calenderInfo'.$designDataGrid[$dayNow - 1][$dctr].'">
                                <a href="#c"><div class="modal_bg"></div></a>
                                <div class="modal_container" style="width: 50%; height: 60%;">
                                    <a href="#c"><img src="/content/cross2.png" alt="" class="close_cross"/></a>


                                    ';


                                        if(isset($_GET['edit']) AND $_GET['edit']==$calData['id'] AND $permissionEditDate)
                                        {
                                            $dateModalInfo .= '
                                                <h2>Termin bearbeiten</h2>
                                                <hr>
                                                <form action="/kalender" method="post" accept-charset="utf-8" enctype="multipart/form-data" target="_top" class="stagfade2">
                                                    <table>
                                                        <tr>
                                                            <td class="ta_r">Titel</td>
                                                            <td><input value="'.$calData['title'].'" type="text" class="cel_l" placeholder="Titel" name="termin_titel" required/></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ta_r">Beschreibung</td>
                                                            <td><textarea class="cel_l" name="description_date" placeholder="Beschreibung" style="resize: vertical;">'.$calData['description'].'</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ta_r">Datum</td>
                                                            <td><input value="'.$calData['date_begin'].'" type="date" class="cel_l" name="date_termin" required/></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ta_r">Ort</td>
                                                            <td><input value="'.$calData['location'].'" type="text" class="cel_l" placeholder="Ort" name="place"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ta_r">Uhrzeit</td>
                                                            <td><input value="'.$calData['time_start'].'" type="time" class="cel_l" name="time" required/></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ta_r">Kategorie</td>
                                                            <td>
                                                            <select class="cel_l" name="kategorie" id="classKat">
                                                                <option '.(($calData['category']=="Anderes") ? 'selected' : '').' value="">Anderes</option>
                                                                <option '.(($calData['category']=="Landesmeisterschaft") ? 'selected' : '').' value="Landesmeisterschaft" style="color: '.$categoryColor["Landesmeisterschaft"].'">Landesmeisterschaft</option>
                                                                <option '.(($calData['category']=="Doppelturnier") ? 'selected' : '').' value="Doppelturnier" style="color: '.$categoryColor["Doppelturnier"].'">Doppelturnier</option>
                                                                <option '.(($calData['category']=="Nachwuchs") ? 'selected' : '').' value="Nachwuchs" style="color: '.$categoryColor["Nachwuchs"].'">Nachwuchs</option>
                                                                <option '.(($calData['category']=="SchuelerJugend") ? 'selected' : '').' value="SchuelerJugend" style="color: '.$categoryColor["SchuelerJugend"].'">Sch&uuml;ler/Jugend</option>
                                                                <option '.(($calData['category']=="Senioren") ? 'selected' : '').' value="Senioren" style="color: '.$categoryColor["Senioren"].'">Senioren</option>
                                                                <option '.(($calData['category']=="Training") ? 'selected' : '').' value="Training" style="color: '.$categoryColor["Training"].'">Training</option>
                                                            </select>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <br>
                                                    <br>
                                                    <button type="submit" name="update_termin" value="'.$calData['id'].'" class="stagfade3">Termin aktualisieren</button>

                                                </form>
                                            ';
                                        }
                                        else
                                        {
                                            $dateModalInfo .= '
                                            <a href="#exportAG'.$calData['id'].'"><button style="float: right; margin-right: 30px;"><i class="fas fa-file-export"></i> Exportieren</button></a>
                                            <h2><u>'.$calData['titel'].'</u></h2>
                                            <h4>'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($calData['date_begin']))).'</h4>
                                            <h4>'.date_format(date_create($calData['time']),"H:i").' Uhr</h4>
                                            <p>
                                                '.$calData['description'].'
                                            </p>

                                            ';

                                            if($permissionEditDate) $dateModalInfo .= '<span> '.EditButton("/kalender/event/AG".$calData['id']."/".$calData['date_begin']."?editSC=".$calData['id'],false,true).' </span>';
                                            if($permissionDeleteDate)  $dateModalInfo .= '<span> '.DeleteButton("Date","agenda",$calData['id'],false,true).' </span>';
                                        }


                                    $dateModalInfo .= '
                                </div>
                            </div>

                            <div class="modal_wrapper" id="exportAG'.$calData['id'].'">
                                <a href="#c">
                                    <div class="modal_bg"></div>
                                </a>
                                <div class="modal_container" style="width: 400px; height: 200px;">
                                    <h3>Exportieren</h3>
                                    <center>
                                        <form action="/kalender" method="post" accept-charset="utf-8" enctype="multipart/form-data" target="_top">
                                            <button type="submit" class="cel_m" name="export_csv" value="AG'.$calData['id'].'"><i class="fa fa-file-excel-o" style="float: left;"></i> Als .csv Exportieren</button>
                                            <br>
                                            <button type="submit" class="cel_m" name="export_ics" value="AG'.$calData['id'].'"><i class="fas fa-file" style="float: left;"></i>Als .ics Exportieren</button>
                                        </form>
                                    </center>
                                </div>
                            </div>
                        ';

                        // Determine Cell-Style and Text =================================
                        if($calData['isTimespan'])
                        {
                            if($curDate == $calData['date_begin'])
                            {
                                $cellStyle = "timespanStart";
                                $displayTitle = $calData['displayTitle'];

                            }
                            else if($curDate == $calData['date_end'])
                            {
                                $cellStyle = "timespanEnd";
                                $dateModalInfo = '';
                            }
                            else
                            {
                                $cellStyle = "timespanMiddle";
                                $dateModalInfo = '';
                            }

                            if($j == 1) $displayTitle = $calData['displayTitle'];
                        }
                        else
                        {
                            $cellStyle = "timespanSingle";
                            $displayTitle = $calData['displayTitle'];
                        }
                    }

                    // OUTPUT TO PAGE ====================================================
                    echo '
                        <tr>
                            <td>
                                <a href="#calenderInfo'.$designDataGrid[$dayNow - 1][$dctr].'" style="text-decoration:none;">
                                    <div id="'.$cellStyle.'" style="color: '.(($calData['category']!="") ? $categoryColor[$calData['category']] : '#000000').';">'.$displayTitle.'</div>
                                </a>
                            </td>
                        </tr>
                    ';

                    // Add Modal to other Modals
                    $dateModalInfos .= $dateModalInfo;

                }
                else echo '<tr><td></td></tr>';
            }
            echo '</table>';

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
        </center>
    ';

    // Output Modals
    echo $dateModalInfos;


    echo '
                </div>
            </body>
        </html>
    ';

?>