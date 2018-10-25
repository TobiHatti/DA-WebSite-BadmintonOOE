<?php
    require("header.php");

    if(isset($_GET['club']))
    {

    }
    if(isset($_GET['year']))
    {
        $year = $_GET['year'];

        $accentColor1 = '#1E90FF';
        $accentColor2 = '#FFD700';
        $highlightColor = '#FFFF00';

        echo '<h1 class="stagfade1">Spielerrangliste '.$_GET['year'].'</h1>';

        echo '
            <br>
            <center>
                <table class="" style="border-collapse: collapse; border-spacing:0">
                    <tr>
                        <td colspan=8 style="background: '.$accentColor1.'; font-size: 16pt;"><b>O&Ouml;BV - MANNSCHAFTSMEISTERSCHAFT</b></td>
                        <td colspan=1 class="ta_r" style="background: '.$accentColor1.'; font-size: 14pt;"><b>'.$year.'</b></td>
                    </tr>
                    <tr>
                        <td colspan=8 style="background: '.$accentColor1.'; font-size: 12pt;"><b>6. - 10. Meisterschaftsrunde - R&uuml;ckrunde</b></td>
                        <td colspan=1 class="ta_r" rowspan=3 style="background: '.$accentColor1.';"><img src="/content/ooebv.png" alt="" style="height:100px"/></td>
                    </tr>
                    <tr>
                        <td colspan=4 style="background: '.$accentColor1.'; font-size: 14pt;"><b>S P I E L E R R A N G L I S T E</b></td>
                        <td colspan=4 style="background: '.$accentColor1.'; font-size: 14pt;"><b>Stand per 11.02.2017</b></td>
                    </tr>
                    <tr><td colspan=8 style="background: '.$accentColor1.';"><br></td></tr>
                    <tr><td colspan=9 style="background: '.$accentColor2.';"><br></td></tr>

                    <tr><td>&nbsp;</td></tr>

                    <tr>
                        <th class="ta_l" colspan=5 style="background: '.$accentColor1.'; font-size: 14pt;">ATV Andorf</th>
                        <th style="background: '.$accentColor1.'; font-size: 14pt;">301</th>
                        <th style="background: '.$accentColor1.'; font-size: 10pt; width: 90px">&Auml;nderungen/<br>Mannschaftsf.</th>
                        <th style="background: '.$accentColor1.'; font-size: 14pt; width: 100px">Handy-Nr.</th>
                        <th style="background: '.$accentColor1.'; font-size: 14pt; width: 200px">E-Mail</th>
                    </tr>
                    <tr><td colspan=9 style="background: '.$accentColor2.';"><br></td></tr>

                    <tr>
                        <th style="width: 25px"></th>
                        <th style="width: 160px">Nachname</th>
                        <th style="width: 100px">Vorname</th>
                        <th style="width: 65px">Mitgl. Nr.</th>
                        <th style="width: 40px">Team</th>
                        <th style="width: 80px">Vereins-Nr.</th>
                    </tr>

                    <tr>
                        <td colspan=9><b>Herren:</b></td>
                    </tr>

        ';

        $club = 314;
        $i=1;

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

        echo '
                </table>
            </center>
        ';


    }

    include("footer.php");
?>