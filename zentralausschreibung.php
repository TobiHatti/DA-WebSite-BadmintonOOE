<?php
    require("header.php");
    PageTitle("Zentralausschreibungen");

    if(isset($_POST['updateZA']))
    {
        $kategorie = $_POST['kategorie'];

        $title1 = $_POST['title1'];
        $title2 = $_POST['title2'];

        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];
        $chTimespan = (isset($_POST['ch_timespan']) ? 1 : 0 );

        $chVerein = (isset($_POST['ch_verein']) ? 1 : 0 );
        $chUhrzeit = (isset($_POST['ch_uhrzeit']) ? 1 : 0 );
        $chAuslosung = (isset($_POST['ch_auslosung']) ? 1 : 0 );
        $chHallenname = (isset($_POST['ch_hallenname']) ? 1 : 0 );
        $chAnschriftHalle = (isset($_POST['ch_anschrift_halle']) ? 1 : 0 );
        $chAnzahlFelder = (isset($_POST['ch_anzahl_felder']) ? 1 : 0 );
        $chTurnierverantwortlicher = (isset($_POST['ch_turnierverantwortlicher']) ? 1 : 0 );
        $chOberschiedsrichter = (isset($_POST['ch_oberschiedsrichter']) ? 1 : 0 );
        $chTelefon = (isset($_POST['ch_telefon']) ? 1 : 0 );
        $chAnmeldungOnline = (isset($_POST['ch_anmeldung_online']) ? 1 : 0 );
        $chAnmeldungEmail = (isset($_POST['ch_anmeldung_email']) ? 1 : 0 );
        $chNennungenEmail = (isset($_POST['ch_nennungen_email']) ? 1 : 0 );
        $chNennschluss = (isset($_POST['ch_nennschluss']) ? 1 : 0 );
        $chZusatzangaben = (isset($_POST['ch_zusatzangaben']) ? 1 : 0 );

        $Verein = $_POST['verein'];
        $Uhrzeit = $_POST['uhrzeit'];
        $Auslosung = $_POST['auslosung'];
        $Hallenname = $_POST['hallenname'];
        $AnschriftHalle = $_POST['anschrift_halle'];
        $AnzahlFelder = $_POST['anzahl_felder'];
        $Turnierverantwortlicher = $_POST['turnierverantwortlicher'];
        $Oberschiedsrichter = $_POST['oberschiedsrichter'];
        $Telefon = $_POST['telefon'];
        $AnmeldungOnline = $_POST['anmeldung_online'];
        $AnmeldungEmail = $_POST['anmeldung_email'];
        $NennungenEmail = $_POST['nennungen_email'];
        $Nennschluss = $_POST['nennschluss'];
        $Zusatzangaben = $_POST['zusatzangaben'];

        if($kategorie=='Training') $size='';
        else $size='full';

        $Location = $_POST['location'];

        if($_POST['postType']=="new")
        {
            MySQL::NonQuery("INSERT INTO zentralausschreibungen (id,kategorie) VALUES ('','newfield')");
            $zaID = MySQL::Scalar("SELECT id FROM zentralausschreibungen WHERE kategorie = 'newfield'");
        }
        else $zaID = $_POST['updateZA'];

        $updateSQL = "
            UPDATE zentralausschreibungen SET
            size = ?,
            kategorie = ?,
            title_line1 = ?,
            title_line2 = ?,
            date_begin = ?,
            date_end = ?,
            act_timespan = ?,
            act_verein = ?,
            verein = ?,
            act_uhrzeit = ?,
            uhrzeit = ?,
            act_auslosung = ?,
            auslosung = ?,
            act_hallenname = ?,
            hallenname = ?,
            act_anschrift_halle = ?,
            anschrift_halle = ?,
            act_anzahl_felder = ?,
            anzahl_felder = ?,
            act_turnierverantwortlicher = ?,
            turnierverantwortlicher = ?,
            act_oberschiedsrichter = ?,
            oberschiedsrichter = ?,
            act_telefon = ?,
            telefon = ?,
            act_anmeldung_online = ?,
            anmeldung_online = ?,
            act_anmeldung_email = ?,
            anmeldung_email = ?,
            act_nennungen_email = ?,
            nennungen_email = ?,
            act_nennschluss = ?,
            nennschluss = ?,
            act_zusatzangaben = ?,
            zusatzangaben = ?,
            location = ?
            WHERE id = ?;
        ";

        MySQL::NonQuery($updateSQL,'@s',$size,$kategorie,$title1,$title2,$date1,$date2,$chTimespan,$chVerein,$Verein,$chUhrzeit,$Uhrzeit,$chAuslosung,$Auslosung,$chHallenname,
        $Hallenname,$chAnschriftHalle,$AnschriftHalle, $chAnzahlFelder,$AnzahlFelder,$chTurnierverantwortlicher,$Turnierverantwortlicher,$chOberschiedsrichter,$Oberschiedsrichter,
        $chTelefon,$Telefon,$chAnmeldungOnline,$AnmeldungOnline,$chAnmeldungEmail,$AnmeldungEmail,$chNennungenEmail,$NennungenEmail,$chNennschluss,$Nennschluss,$chZusatzangaben,
        $Zusatzangaben,$Location,$zaID);

        Redirect(ThisPage());
        die();
    }


    if(isset($_GET['neu']))
    {
        if(CheckPermission("AddZA"))
        {
            echo EditZA();
        }
    }
    else
    {
        echo '<h1 class="stagfade1">Zentralausschreibungen</h1>';

        if(CheckPermission("AddZA"))
        {
            echo AddButton(ThisPage("!editSC","!#edit","+neu"));
        }


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

        echo '<br><center>';
        $first = true;
        $today=date("Y-m-d");  
        $strSQL = "SELECT DISTINCT SUBSTRING(date_begin, 1, 7) AS date FROM zentralausschreibungen WHERE date_begin > '$today' ORDER BY date_begin ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            if($first) echo '<a href="#'.$row['date'].'">'.str_replace('ä','&auml;',strftime("%B %Y",strtotime($row['date']))).'</a>';
            else echo ' | <a href="#'.$row['date'].'">'.str_replace('ä','&auml;',strftime("%B %Y",strtotime($row['date']))).'</a>';
            $first=false;
        }
        echo '</center><br>';


        echo PageContent("1",CheckPermission("ChangeContent"));



        $strSQLt = "SELECT DISTINCT SUBSTRING(date_begin, 1, 7) AS date FROM zentralausschreibungen WHERE date_begin > '$today' ORDER BY date_begin ASC";
        $rst=mysqli_query($link,$strSQLt);
        while($rowt=mysqli_fetch_assoc($rst))
        {
            $date = $rowt['date'];
            echo '<a name="'.$date.'">&nbsp;</a>';

            $strSQL = "SELECT * FROM zentralausschreibungen WHERE date_begin LIKE '$date%' AND date_begin > '$today' ORDER BY date_begin ASC";
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

                echo '<a name="'.SReplace($row['title_line1'].' '.$row['title_line2']).'">&nbsp;</a>';

                if(isset($_GET['editSC']) AND $_GET['editSC']==$row['id'] AND CheckPermission("EditZA"))
                {
                    echo '<a name="edit">&nbsp;</a>';
                    echo EditZA($row['id']);
                }
                else
                {
                    if($row['size']=='full')
                    {
                        echo '
                            <div class="za_box">
                                <div class="za_title">
                                    <h1 style="color: '.Setting::Get("Color".$row['kategorie']).'">'.$row['title_line1'].'<br>'.$row['title_line2'].'</h1>
                                    <h2><span style="color: #000000">'.$dateStr.'</span></h2>
                                </div>
                                <div class="za_data">
                        ';

                        echo ShowZATable($row['id']);

                        if(CheckPermission("EditZA"))
                        {
                            echo '<span style="float: right;"> '.EditButton(ThisPage("!editContent","!editSC","+editSC=".$row['id'],"#edit")).' </span>';
                        }

                        if(CheckPermission("DeleteZA"))
                        {
                            echo '<span style="float: right;"> '.DeleteButton("ZA","zentralausschreibungen",$row['id']).' </span>';
                        }

                        echo '
                                </div>
                            </div>
                        ';

                    }
                    else
                    {
                        echo '
                            <div class="za_box">
                                <div class="za_title">
                                    <h3 style="color: '.Setting::Get("Color".$row['kategorie']).'">'.$row['title_line1'].'</h3>

                                </div>
                                <div class="za_data">
                                    <table>
                                        <tr>
                                            <td>Datum:</td>
                                            <td>'.$dateStr.'</td>
                                        </tr>
                                        <tr>
                                            <td>Ort:</td>
                                            <td>'.$row['location'].'</td>
                                        </tr>
                                    </table>
                                    ';

                                    if(CheckPermission("EditZA"))
                                    {
                                        echo '<span style="float: right;"> '.EditButton(ThisPage("!editContent","!editSC","+editSC=".$row['id'],"#edit")).' </span>';
                                    }

                                    if(CheckPermission("DeleteZA"))
                                    {
                                        echo '<span style="float: right;"> '.DeleteButton("ZA","zentralausschreibungen",$row['id']).' </span>';
                                    }

                                     echo '
                                </div>
                            </div>
                        ';
                    }
                }
            }
        }
    }



    include("footer.php");
?>