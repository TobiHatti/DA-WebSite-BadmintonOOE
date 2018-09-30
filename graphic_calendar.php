<?php
    session_start();
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
    for ($i=1;$i<=6;$i++)
    {
        echo '<tr>';
        for ($j=1;$j<=7;$j++)
        {
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
            $strSQL = "SELECT * FROM agenda WHERE date = '$curDate'";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs))
            {
                echo '
                    <a href="#calenderInfoAG'.$row['id'].'" onclick="SelectGalleryImage('.$i.');" style="text-decoration:none;">
                        <div style="color: '.(($row['kategorie']!="") ? GetProperty("Color".$row['kategorie']) : '#000000').';">&#9679; '.$row['titel'].'</div>
                    </a>
                ';
            }


            $strSQL = "SELECT * FROM zentralausschreibungen WHERE date_begin = '$curDate'";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs))
            {
                echo '
                    <a href="#calenderInfoZA'.$row['id'].'" onclick="SelectGalleryImage('.$i.');" style="text-decoration:none;">
                        <div style="color: '.(($row['kategorie']!="") ? GetProperty("Color".$row['kategorie']) : '#000000').';">&#9679; '.$row['title_line1'].'</div>
                    </a>
                ';
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

    $datePart = $yr.'-'.str_pad($mo,2,0,STR_PAD_LEFT).'-';

    $strSQL = "SELECT * FROM agenda WHERE date LIKE '$datePart%'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {

        echo '
            <div class="calender_info_wrapper" id="calenderInfoAG'.$row['id'].'">
                <a href="#c">
                    <div class="calender_info_bg"></div>
                </a>
                <div class="info_container">
                    <a href="#c"><img src="/content/cross2.png" alt="" class="close_cross"/></a>
                    <div style="border-left: 3px solid '.(($row['kategorie']!="") ? GetProperty("Color".$row['kategorie']) : '').'; padding-left: 5px;">
                        ';

                        if(isset($_GET['edit']) AND $_GET['edit']==$row['id'] AND CheckPermission("EditDate"))
                        {
                            echo '
                                <h2>Termin bearbeiten</h2>
                                <hr>


                                <form action="/kalender" method="post" accept-charset="utf-8" enctype="multipart/form-data" target="_top" class="stagfade2">
                                    <table>
                                        <tr>
                                            <td class="ta_r">Titel</td>
                                            <td><input value="'.$row['titel'].'" type="text" class="cel_l" placeholder="Titel" name="termin_titel" required/></td>
                                        </tr>
                                        <tr>
                                            <td class="ta_r">Beschreibung</td>
                                            <td><textarea class="cel_l" name="description_date" placeholder="Beschreibung" style="resize: vertical;">'.$row['description'].'</textarea></td>
                                        </tr>
                                        <tr>
                                            <td class="ta_r">Datum</td>
                                            <td><input value="'.$row['date'].'" type="date" class="cel_l" name="date_termin" required/></td>
                                        </tr>
                                        <tr>
                                            <td class="ta_r">Ort</td>
                                            <td><input value="'.$row['place'].'" type="text" class="cel_l" placeholder="Ort" name="place"/></td>
                                        </tr>
                                        <tr>
                                            <td class="ta_r">Uhrzeit</td>
                                            <td><input value="'.$row['time'].'" type="time" class="cel_l" name="time" required/></td>
                                        </tr>
                                        <tr>
                                            <td class="ta_r">Kategorie</td>
                                            <td>
                                            <select class="cel_l" name="kategorie" id="classKat">
                                                <option '.(($row['kategorie']=="Anderes") ? 'selected' : '').' value="">Anderes</option>
                                                <option '.(($row['kategorie']=="Landesmeisterschaft") ? 'selected' : '').' value="Landesmeisterschaft" style="color: '.GetProperty("ColorLandesmeisterschaft").'">Landesmeisterschaft</option>
                                                <option '.(($row['kategorie']=="Doppelturnier") ? 'selected' : '').' value="Doppelturnier" style="color: '.GetProperty("ColorDoppelturnier").'">Doppelturnier</option>
                                                <option '.(($row['kategorie']=="Nachwuchs") ? 'selected' : '').' value="Nachwuchs" style="color: '.GetProperty("ColorNachwuchs").'">Nachwuchs</option>
                                                <option '.(($row['kategorie']=="SchuelerJugend") ? 'selected' : '').' value="SchuelerJugend" style="color: '.GetProperty("ColorSchuelerJugend").'">Sch&uuml;ler/Jugend</option>
                                                <option '.(($row['kategorie']=="Senioren") ? 'selected' : '').' value="Senioren" style="color: '.GetProperty("ColorSenioren").'">Senioren</option>
                                                <option '.(($row['kategorie']=="Training") ? 'selected' : '').' value="Training" style="color: '.GetProperty("ColorTraining").'">Training</option>
                                            </select>
                                            </td>
                                        </tr>
                                    </table>

                                    <br>
                                    <br>
                                    <button type="submit" name="update_termin" value="'.$row['id'].'" class="stagfade3">Termin aktualisieren</button>

                                </form>
                            ';
                        }
                        else
                        {
                            echo '
                            <a href="#exportAG'.$row['id'].'"><button style="float: right; margin-right: 30px;"><i class="fas fa-file-export"></i> Exportieren</button></a>
                            <h2><u>'.$row['titel'].'</u></h2>
                            <h4>'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['date']))).'</h4>
                            <h4>'.date_format(date_create($row['time']),"H:i").' Uhr</h4>
                            <p>
                                '.$row['description'].'
                            </p>

                            ';

                            if(CheckPermission("EditDate"))
                            {
                                echo '<span> '.EditButton("/kalender/event/AG".$row['id']."/".$row['date']."?editSC=".$row['id'],false,true).' </span>';
                            }

                            if(CheckPermission("DeleteDate"))
                            {
                                echo '<span> '.DeleteButton("Date","agenda",$row['id'],false,true).' </span>';
                            }
                        }

                        echo '

                    </div>
                </div>
            </div>
        ';

        echo '
            <div class="calender_export_wrapper" id="exportAG'.$row['id'].'">
                <a href="#c">
                    <div class="calender_info_bg"></div>
                </a>
                <div class="info_container">
                    <h3>Exportieren</h3>
                    <center>
                        <form action="/kalender" method="post" accept-charset="utf-8" enctype="multipart/form-data" target="_top">
                            <button type="submit" class="cel_m" name="export_csv" value="AG'.$row['id'].'"><i class="fa fa-file-excel-o" style="float: left;"></i> Als .csv Exportieren</button>
                            <br>
                            <button type="submit" class="cel_m" name="export_ics" value="AG'.$row['id'].'"><i class="fas fa-file" style="float: left;"></i>Als .ics Exportieren</button>
                        </form>
                    </center>
                </div>
            </div>

        ';
    }

    $strSQL = "SELECT * FROM zentralausschreibungen WHERE date_begin LIKE '$datePart%'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {

        echo '
            <div class="modal_wrapper" id="calenderInfoZA'.$row['id'].'">
                <a href="#c">
                    <div class="modal_bg"></div>
                </a>
                <div class="modal_container" style="width: 50%; height: 60%;">
                    <a href="#c"><img src="/content/cross2.png" alt="" class="close_cross"/></a>
                    <div style="border-left: 3px solid '.(($row['kategorie']!="") ? GetProperty("Color".$row['kategorie']) : '').'; padding-left: 5px;">
                        <a href="#exportZA'.$row['id'].'"><button style="float: right; margin-right: 30px;"><i class="fas fa-file-export"></i> Exportieren</button></a>
                        <span style="color: #696969"><i>Zentralausschreibung</i></span>
                        <h2><u>'.$row['title_line1'].'<br>'.$row['title_line2'].'</u></h2>
                        <h4>'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['date_begin']))).'</h4>
                        <h4>'.$row['uhrzeit'].'</h4>
                        <p>
                        ';

                        if($row['size']=='full')
                        {
                            echo '<div class="za_data">';
                            echo ShowZATable($row['id']);
                            echo '</div>';
                        }
                        else
                        {
                            echo '
                                <div class="za_data">
                                    <table>
                                        <tr>
                                            <td>Ort:</td>
                                            <td>'.$row['location'].'</td>
                                        </tr>
                                    </table>
                                </div>
                            ';
                        }

                        echo '
                        </p>
                    </div>
                </div>
            </div>
        ';


        echo '
            <div class="modal_wrapper" id="exportZA'.$row['id'].'">
                <a href="#c">
                    <div class="modal_bg"></div>
                </a>
                <div class="modal_container" style="width: 200px; height: 100px;">
                    <h3>Exportieren</h3>
                    <center>
                        <form action="/kalender" method="post" accept-charset="utf-8" enctype="multipart/form-data" target="_top">
                            <button type="submit" class="cel_m" name="export_csv" value="ZA'.$row['id'].'"><i class="fa fa-file-excel-o" style="float: left;"></i> Als .csv Exportieren</button>
                            <br>
                            <button type="submit" class="cel_m" name="export_ics" value="ZA'.$row['id'].'"><i class="fas fa-file" style="float: left;"></i>Als .ics Exportieren</button>
                        </form>
                    </center>
                </div>
            </div>

        ';
    }

    echo '
                </div>
            </body>
        </html>
    ';

?>