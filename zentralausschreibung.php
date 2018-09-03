<?php
    require("header.php");
    PageTitle("Zentralausschreibungen");

    echo '<h1 class="stagfade1">Zentralausschreibungen</h1>';


    $monthNames = array(
        "J&auml;nner", "Februar", "M&auml;rz",
        "April", "Mai", "Juni", "Juli",
        "August", "September", "Oktober",
        "November", "Dezember"
    );

    $monthNamesS = array(
        "Jan", "Feb", "M&auml;r",
        "Apr", "Mai", "Jun", "Jul",
        "Aug", "Sep", "Okt",
        "Nov", "Dez"
    );

    $dayNames = array(
        "Sonntag","Montag","Dienstag","Mittwoch",
        "Donnerstag","Freitag","Samstag"
    );

    $dayNamesS = array(
        "So","Mo","Di","Mi","Do","Fr","Sa"
    );

    $strSQL = "SELECT * FROM zentralausschreibungen ORDER BY date_begin ASC";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        $dayIndex1 = date("w",strtotime($row['date_begin']));
        $dayIndex2 = date("w",strtotime($row['date_end']));
        $day1 = date("d",strtotime($row['date_begin']));
        $day2 = date("d",strtotime($row['date_end']));
        $monthIndex1 = date("n",strtotime($row['date_begin'])) - 1;
        $monthIndex2 = date("n",strtotime($row['date_end'])) - 1;
        $year = date("Y",strtotime($row['date_begin']));

        if($row['act_timespan'])
        {
            if($day1 == $day2-1) $dateStr = $dayNamesS[$dayIndex1].'/'.$dayNamesS[$dayIndex2].', '.$day1.'./'.$day2.'. '.$monthNames[$monthIndex1].' '.$year;
            else
            {
                if($monthIndex1 == $monthIndex2) $dateStr = $dayNamesS[$dayIndex1].' '.$day1.'. - '.$dayNamesS[$dayIndex2].' '.$day2.'. '.$monthNames[$monthIndex1].' '.$year;
                else $dateStr = $dayNamesS[$dayIndex1].' '.$day1.'. '.$monthNames[$monthIndex1].' - '.$dayNamesS[$dayIndex2].' '.$day2.'. '.$monthNames[$monthIndex2].' '.$year;
            }
        }
        else $dateStr = $dayNames[$dayIndex1].', '.$day1.'. '.$monthNames[$monthIndex1].' '.$year;

        echo '
            <div class="za_box">
                <div class="za_title">
                    <h1 style="color: '.GetProperty("Color".$row['kategorie']).'">'.$row['title_line1'].'<br>'.$row['title_line2'].'</h1>
                    <h2><span style="color: #000000">'.$dateStr.'</span></h2>
                </div>
                <div class="za_data">
                    <table>
        ';

        if($row['act_verein'])
        {
            echo '
                <tr>
                    <td class="ta_r"><b>Verein:</b></td>
                    <td class="ta_l"><b>'.$row['verein'].'</b></td>
                </tr>
            ';
        }
        if($row['act_uhrzeit'])
        {
            echo '
                <tr>
                    <td class="ta_r">Uhrzeit:</td>
                    <td class="ta_l">'.$row['uhrzeit'].'</td>
                </tr>
            ';
        }
        if($row['act_auslosung'])
        {
            echo '
                <tr>
                    <td class="ta_r">Auslosung:</td>
                    <td class="ta_l">'.$row['auslosung'].'</td>
                </tr>
            ';
        }
        if($row['act_hallenname'])
        {
            echo '
                <tr>
                    <td class="ta_r">Hallenname:</td>
                    <td class="ta_l">'.$row['hallenname'].'</td>
                </tr>
            ';
        }
        if($row['act_anschrift_halle'])
        {
            echo '
                <tr>
                    <td class="ta_r">Anschrift Halle:</td>
                    <td class="ta_l">'.$row['anschrift_halle'].'</td>
                </tr>
            ';
        }
        if($row['act_anzahl_felder'])
        {
            echo '
                <tr>
                    <td class="ta_r">Anzahl Felder:</td>
                    <td class="ta_l">'.$row['anzahl_felder'].'</td>
                </tr>
            ';
        }
        if($row['act_turnierverantwortlicher'])
        {
            echo '
                <tr>
                    <td class="ta_r">Turnierverantwortlicher:</td>
                    <td class="ta_l">'.$row['turnierverantwortlicher'].'</td>
                </tr>
            ';
        }
        if($row['act_oberschiedsrichter'])
        {
            echo '
                <tr>
                    <td class="ta_r">Oberschiedsrichter:</td>
                    <td class="ta_l">'.$row['oberschiedsrichter'].'</td>
                </tr>
            ';
        }
        if($row['act_telefon'])
        {
            echo '
                <tr>
                    <td class="ta_r">Telefon:</td>
                    <td class="ta_l">'.$row['telefon'].'</td>
                </tr>
            ';
        }
        if($row['act_anmeldung_online'])
        {
            echo '
                <tr>
                    <td class="ta_r">Anmeldung Online:</td>
                    <td class="ta_l"><a href="'.$row['anmeldung_online'].'" target="_blank"><b><i>Online-Anmeldung</i></b></a></td>
                </tr>
            ';
        }
        if($row['act_anmeldung_email'])
        {
            echo '
                <tr>
                    <td class="ta_r">Anmeldung E-Mail:</td>
                    <td class="ta_l"><a href="mailto: '.$row['anmeldung_email'].'">'.$row['anmeldung_email'].'</a></td>
                </tr>
            ';
        }
        if($row['act_nennungen_email'])
        {
            echo '
                <tr>
                    <td class="ta_r">Nennungen E-Mail:</td>
                    <td class="ta_l"><a href="mailto: '.$row['nennungen_email'].'">'.$row['nennungen_email'].'</a></td>
                </tr>
            ';
        }
        if($row['act_nennschluss'])
        {
            echo '
                <tr>
                    <td class="ta_r">Nennschluss:</td>
                    <td class="ta_l">'.str_replace('ä','&auml;',strftime("%A, %d. %B %Y",strtotime($row['nennschluss']))).'</td>
                </tr>
            ';
        }
        if($row['act_zusatzangaben'])
        {
            echo '
                <tr>
                    <td class="ta_r">Zusatzangaben:</td>
                    <td class="ta_l">'.$row['zusatzangaben'].'</td>
                </tr>
            ';
        }

        echo '
                    </table>
                </div>
            </div>
        ';
    }








    echo '

    <br>
    <p>
       <a href="#september">SEPTEMBER 2018</a>
       |
       <a href="#oktober">OKTOBER 2018</a>
       |
       <a href="#november">NOVEMEBER 2018</a>
       |
       <a href="#dezember">DEZEMBER 2018</a>
       |
       <a href="#jaenner">J&Auml;NNER 2019</a>
       |
       <a href="#februar">FEBRUAR 2019</a>
       |
       <a href="#maerz">M&Auml;RZ 2019</a>
       |
       <a href="#april">APRIL 2019</a>
       |
       <a href="#mai">MAI 2019</a>
       |
       <a href="#juni">JUNI 2019</a>
       |
    </p>

    <br>
    <hr>
    ';

    include("footer.php");
?>