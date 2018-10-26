<?php
    require("header.php");

    $year = $_GET['year'];
    $club = $_GET['club'];

    $accentColor1 = '#FFB152';
    $accentColor2 = '#9E0000';
    $highlightColor = '#FFFF00';

    $lastChange = '26.10.2018';

    echo '<h1 class="stagfade1">Spielerrangliste '.$_GET['year'].'</h1>';

    echo '
        <hr>
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
                                    $strSQL = "SELECT DISTINCT club FROM reihung WHERE year = '$year'";
                                    $rs=mysqli_query($link,$strSQL);
                                    while($row=mysqli_fetch_assoc($rs)) echo '<option '.(($_GET['club']==$row['club']) ? 'selected' : '').' value="'.$row['club'].'">'.Fetch("vereine","verein","kennzahl",$row['club']).' '.Fetch("vereine","ort","kennzahl",$row['club']).'</option>';
                                echo '
                                </optgroup>
                            </select>
                        </td>
                    </tr>
                    <tr id="clubToggleList" style="display: none">
                        <td colspan=2>
                        ';

                        $strSQL = "SELECT DISTINCT club FROM reihung WHERE year = '$year'";
                        $rs=mysqli_query($link,$strSQL);
                        while($row=mysqli_fetch_assoc($rs))
                        {
                            $clubVals = FetchArray("vereine","kennzahl",$row['club']);
                            echo '<div>'.Tickbox("",$row['club'],$clubVals['verein'].' '.$clubVals['ort'],false, 'UpdateClubList(this, \''.$row['club'].'\');').'</div>';
                        }

                        echo '

                        <input type="" id="customList"/>
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
                    <td colspan=8 style="background: '.$accentColor1.'; font-size: 12pt;"><b>6. - 10. Meisterschaftsrunde - R&uuml;ckrunde</b></td>
                    <td colspan=1 class="ta_r" rowspan=3 style="background: '.$accentColor1.'; border-bottom: 6px solid '.$accentColor2.'"><img src="/content/ooebv.png" alt="" style="height:100px"/></td>
                </tr>
                <tr>
                    <td colspan=4 style="background: '.$accentColor1.'; font-size: 14pt;"><b>S P I E L E R R A N G L I S T E</b></td>
                    <td colspan=4 style="background: '.$accentColor1.'; font-size: 14pt;"><b>Stand per '.$lastChange.'</b></td>
                </tr>
                <tr><td colspan=8 style="background: '.$accentColor1.'; border-bottom: 6px solid '.$accentColor2.'"><br></td></tr>
    ';


    if($club == "alle") $strSQLc = "SELECT DISTINCT club FROM reihung WHERE year = '$year'";
    else if(StartsWith($club,"M"))
    {
        $selectedClubs = str_replace('M','',$club);
        $clubArray = explode('-',$selectedClubs);

        $first = true;
        foreach($clubArray AS $club)
        {
            if($first) $sqlClubExtension = "club = '$club'";
            else $sqlClubExtension .= " OR club = '$club'";

            $first = false;
        }

        $strSQLc = "SELECT DISTINCT club FROM reihung WHERE year = '$year' AND ($sqlClubExtension)";
    }
    else $strSQLc = "SELECT DISTINCT club FROM reihung WHERE year = '$year' AND club = '$club'";



    $rsc=mysqli_query($link,$strSQLc);
    while($rowc=mysqli_fetch_assoc($rsc))
    {
        $club = $rowc['club'];
        $clubVals = FetchArray("vereine","kennzahl",$club);


        echo '
            <tr><td>&nbsp;</td></tr>
            <tr>
                <th class="ta_l" colspan=5 style="background: '.$accentColor1.'; font-size: 14pt; border-bottom: 6px solid '.$accentColor2.'">'.$clubVals['verein'].' '.$clubVals['ort'].'</th>
                <th style="background: '.$accentColor1.'; font-size: 14pt; border-bottom: 6px solid '.$accentColor2.'">'.$club.'</th>
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
        $strSQL = "SELECT * FROM reihung INNER JOIN members ON reihung.member = members.number WHERE reihung.type='M' AND reihung.club = '$club' AND reihung.year = '$year' ORDER BY reihung.position ASC";
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
                    <td class="ta_c">'.$row['number'].'</td>
                    <td class="ta_c">'.$row['team'].'</td>
                    <td class="ta_c">'.$row['club'].'</td>
                    <td class="ta_c" '.($highlight ? ('style="background: '.$highlightColor.';"') : '').'><b>'.$row['mf'].'</b></td>
                    <td class="ta_c">'.$row['mobile_nr'].'</td>
                    <td class="ta_c">'.$row['email'].'</td>
                </tr>
            ';
        }

        $i=1;
        echo '<tr><td colspan=9><b><br>Damen:</b></td></tr>';
        $strSQL = "SELECT * FROM reihung INNER JOIN members ON reihung.member = members.number WHERE reihung.type='W' AND reihung.club = '$club' AND reihung.year = '$year' ORDER BY reihung.position ASC";
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
                    <td class="ta_c">'.$row['number'].'</td>
                    <td class="ta_c">'.$row['team'].'</td>
                    <td class="ta_c">'.$row['club'].'</td>
                    <td class="ta_c" '.($highlight ? ('style="background: '.$highlightColor.';"') : '').'><b>'.$row['mf'].'</b></td>
                    <td class="ta_c">'.$row['mobile_nr'].'</td>
                    <td class="ta_c">'.$row['email'].'</td>
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