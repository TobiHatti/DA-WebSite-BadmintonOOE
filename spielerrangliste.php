<?php
    require("header.php");

    $year = $_GET['year'];
    $club = $_GET['club'];

    $accentColor1Sett = "Y".$year."ColorA";
    $accentColor2Sett = "Y".$year."ColorB";
    $highlightColorSett = "HighlightColor";
    $headerSubtitleSett = "Y".$year."HeaderSubtitle";
    $lastUpdateSett = "Y".$year."LastUpdate";

    $accentColor1 = '#'.MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$accentColor1Sett);
    $accentColor2 = '#'.MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$accentColor2Sett);
    $highlightColor = '#'.MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$highlightColorSett);

    $lastChangeDate = MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$lastUpdateSett);
    $lastChange = str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($lastChangeDate)));

    echo '<h1 class="stagfade1">Spielerrangliste '.$_GET['year'].'</h1>';

    if(CheckPermission("EditSpielerrangliste")) echo '<a href="/spielerrangliste/einstellungen/'.$_GET['year'].'">&#x270E; Spielerranglisten-Einstellungen</a>';
    if(CheckPermission("EditSpielerrangliste")) echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/spielerrangliste/reihungen/'.$_GET['year'].'/bearbeiten">&#x270E; Reihungen Bearbeiten</a>';

    echo '
        <hr>
            <a href="#export"><button type="button" style="float: right;"><i class="fas fa-save"></i> Datei Speichern...</button></a>

            <div class="modal_wrapper" id="export">
            <a href="#c"><div class="modal_bg"></div></a>
                <div class="modal_container" style="width: 370px; height: 160px; overflow-y: hidden">
                    <center>
                        <a target="_blank" href="/spielerrangliste/'.$_GET['year'].'/'.$_GET['club'].'/pdf"><button type="button"><span style="font-size: 60pt; color: #FFFFFF;"><i class="fas fa-file-pdf"></i></span><br>PDF</button></a>
                        <a href="/spielerrangliste/'.$_GET['year'].'/'.$_GET['club'].'/xls"><button type="button"><span style="font-size: 60pt; color: #FFFFFF;"><i class="fas fa-file-excel"></i></span><br>Excel</button></a>
                        <a href="/spielerrangliste/'.$_GET['year'].'/'.$_GET['club'].'/csv"><button type="button"><span style="font-size: 60pt; color: #FFFFFF;"><i class="fas fa-file-csv"></i></span><br>CSV</button></a>
                    </center>
                </div>
            </div>

            <center>
                <table style="width: 400px">
                    <tr>
                        <td class="ta_r">Anzeigen:</td>
                        <td class="ta_l">
                            <select name="" id="clubList" class="cel_l" onchange="RedirectSelectBoxSpielerrangliste(this,\'/spielerrangliste/'.$year.'/\')">
                                <optgroup label="Mehrfachauswahl">
                                    <option '.(($_GET['club']=='alle') ? 'selected' : '').' value="alle">Alle anzeigen</option>
                                    <option '.((StartsWith($_GET['club'],'M')) ? 'selected' : '').' value="multi">Ausw&auml;hlen...</option>
                                </optgroup>
                                <optgroup label="Einzelauswahl">
                                ';
                                    $strSQL = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' GROUP BY members.clubID";
                                    $reihungClubList = MySQL::Cluster($strSQL);
                                    foreach($reihungClubList as $clubData)  echo '<option '.(($_GET['club']==$clubData['clubID']) ? 'selected' : '').' value="'.$clubData['clubID'].'">'.$clubData['verein'].' '.$clubData['ort'].'</option>';
                                echo '
                                </optgroup>
                            </select>
                        </td>
                    </tr>
                    <tr id="clubToggleList" style="display: none">
                        <td colspan=2>
                        ';

                        foreach($reihungClubList as $clubData) echo '<div>'.Tickbox("",$clubData['clubID'],$clubData['verein'].' '.$clubData['ort'],false, 'UpdateClubList(this, \''.$clubData['clubID'].'\');').'</div>';

                        echo '
                        <input type="hidden" id="customList"/>
                        <button type="button" onclick="RedirectCustomClubList(\'/spielerrangliste/'.$year.'/\')">Anzeigen</button>
                        </td>
                    </tr>
                </table>


            </center>
        <hr>
    ';


    echo '
        <br>
        <center>
            <table class="spielerranglisteTable" style="border-collapse: collapse; border-spacing:0">
                <tr>
                    <td colspan=8 style="background: '.$accentColor1.'; font-size: 16pt;"><b>O&Ouml;BV - MANNSCHAFTSMEISTERSCHAFT</b></td>
                    <td colspan=1 class="ta_r" style="background: '.$accentColor1.'; font-size: 14pt;"><b>'.$year.'</b></td>
                </tr>
                <tr>
                    <td colspan=8 style="background: '.$accentColor1.'; font-size: 12pt;"><b>'.MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$headerSubtitleSett).'</b></td>
                    <td colspan=1 class="ta_r" rowspan=3 style="background: '.$accentColor1.'; border-bottom: 6px solid '.$accentColor2.'"><img src="/content/ooebv.png" alt="" style="height:100px"/></td>
                </tr>
                <tr>
                    <td colspan=4 style="background: '.$accentColor1.'; font-size: 14pt;"><b>S P I E L E R R A N G L I S T E</b></td>
                    <td colspan=4 style="background: '.$accentColor1.'; font-size: 14pt;"><b>Stand per '.$lastChange.'</b></td>
                </tr>
                <tr><td colspan=8 style="background: '.$accentColor1.'; border-bottom: 6px solid '.$accentColor2.'"><br></td></tr>
    ';


    if($club == "alle") $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' GROUP BY members.clubID";
    else if(StartsWith($club,"M"))
    {
        $selectedClubs = str_replace('M','',$club);
        $clubArray = explode('-',$selectedClubs);

        $first = true;
        foreach($clubArray AS $sClub)
        {
            if($first) $sqlClubExtension = "members.clubID = '$sClub'";
            else $sqlClubExtension .= " OR members.clubID = '$sClub'";

            $first = false;
        }

        $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' AND ($sqlClubExtension) GROUP BY members.clubID";
    }
    else $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' AND members.clubID = '$club' GROUP BY members.clubID";



    $rsc=mysqli_query($link,$strSQLc);
    while($rowc=mysqli_fetch_assoc($rsc))
    {
        $club = $rowc['clubID'];
        echo '
            <tr><td>&nbsp;</td></tr>
            <tr>
                <th class="ta_l" colspan=5 style="background: '.$accentColor1.'; font-size: 14pt; border-bottom: 6px solid '.$accentColor2.'">'.$rowc['verein'].' '.$rowc['ort'].'</th>
                <th style="background: '.$accentColor1.'; font-size: 14pt; border-bottom: 6px solid '.$accentColor2.'">'.$rowc['clubID'].'</th>
                <th style="background: '.$accentColor1.'; font-size: 10pt; width: 90px; border-bottom: 6px solid '.$accentColor2.'">&Auml;nderungen/<br>Mannschaftsf.</th>
                <th style="background: '.$accentColor1.'; font-size: 14pt; width: 130px; border-bottom: 6px solid '.$accentColor2.'">Handy-Nr.</th>
                <th style="background: '.$accentColor1.'; font-size: 14pt; width: 200px; border-bottom: 6px solid '.$accentColor2.'">E-Mail</th>
            </tr>
            <tr><td colspan=9 style=""></td></tr>

            <tr>
                <th style="width: 25px"></th>
                <th style="width: 160px">Nachname</th>
                <th style="width: 100px">Vorname</th>
                <th style="width: 65px">Mitgl. Nr.</th>
                <th style="width: 40px">Team</th>
                <th style="width: 80px">Vereins-Nr.</th>
            </tr>
        ';


        $i=1;
        echo '<tr><td colspan=9><b>Herren:</b></td></tr>';
        $strSQL = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.gender = 'M' AND members.clubID = '$club' AND members_spielerranglisten.year = '$year' ORDER BY members_spielerranglisten.position ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $highlight = ($row['mf']!='' AND !(SubStringFind($row['mf'],'MF')));
            $focus = (SubStringFind($row['mf'],'MF'));

            echo '
                <tr '.($focus ? ('style="background: '.$accentColor2.'; font-weight: bold;"') : '').'>
                    <td class="ta_r">'.$i++.'.</td>
                    <td>'.$row['lastname'].'</td>
                    <td>'.$row['firstname'].'</td>
                    <td class="ta_c">'.$row['playerID'].'</td>
                    <td class="ta_c">'.$row['team'].'</td>
                    <td class="ta_c">'.$row['clubID'].'</td>
                    <td class="ta_c" '.($highlight ? ('style="background: '.$highlightColor.';"') : '').'><b>'.$row['mf'].'</b></td>
                    <td class="ta_c">'.($row['mf']!="" ? $row['mobileNr'] : '').'</td>
                    <td class="ta_c">'.($row['mf']!="" ? $row['email'] : '').'</td>
                </tr>
            ';
        }

        $i=1;
        echo '<tr><td colspan=9><b><br>Damen:</b></td></tr>';
        $strSQL = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.gender = 'F' AND members.clubID = '$club' AND members_spielerranglisten.year = '$year' ORDER BY members_spielerranglisten.position ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $highlight = ($row['mf']!='' AND !(SubStringFind($row['mf'],'MF')));
            $focus = (SubStringFind($row['mf'],'MF'));

            echo '
                <tr '.($focus ? ('style="background: '.$accentColor2.'; font-weight: bold;"') : '').'>
                    <td class="ta_r">'.$i++.'.</td>
                    <td>'.$row['lastname'].'</td>
                    <td>'.$row['firstname'].'</td>
                    <td class="ta_c">'.$row['playerID'].'</td>
                    <td class="ta_c">'.$row['team'].'</td>
                    <td class="ta_c">'.$row['clubID'].'</td>
                    <td class="ta_c" '.($highlight ? ('style="background: '.$highlightColor.';"') : '').'><b>'.$row['mf'].'</b></td>
                    <td class="ta_c">'.($row['mf']!="" ? $row['mobileNr'] : '').'</td>
                    <td class="ta_c">'.($row['mf']!="" ? $row['email'] : '').'</td>
                </tr>
            ';
        }

    }

    echo '
            </table>
        </center>
    ';

    include("footer.php");
?>