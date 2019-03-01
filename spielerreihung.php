<?php
    require("header.php");

    if(isset($_POST['updateListM']) OR isset($_POST['updateListW']))
    {
        $selectedMembers = array();

        $year = $_POST['year'];

        $club = $_POST['clubID'];
        if(isset($_POST['updateListM'])) $type='M';
        if(isset($_POST['updateListW'])) $type='F';
        $strSQL = "SELECT * FROM members WHERE members.clubID = '$club' AND members.gender = '$type'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            if(isset($_POST["member".$row['playerID']]))
            {
                array_push($selectedMembers,$row['id']);
            }
        }

        // Remove existing values from Database
        MySQL::NonQuery("DELETE members_spielerranglisten FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.gender = ? AND members.clubID = ? AND members_spielerranglisten.year = ?",'@s',$type,$club,$year);



        $i=1;
        foreach($selectedMembers as $member)
        {
            $uid = uniqid();
            MySQL::NonQuery("INSERT INTO members_spielerranglisten (memberID,position,year,team) VALUES (?,'".$i++."',?,'1')",'@s',$member,$year);
        }

        // Update last edit-Date
        $paramLatestUpdate = 'Y'.$year.'LastUpdate';
        $latestDate = date("Y-m-d");
        if(!MySQL::Exist("SELECT * FROM ranglisten_settings WHERE setting = ?",'s',$paramLatestUpdate))
        {
            MySQL::NonQuery("INSERT INTO ranglisten_settings (setting,value) VALUES (?,'')",'s',$paramLatestUpdate);
        }
        MySQL::NonQuery("UPDATE ranglisten_settings SET value = ? WHERE setting = ?",'ss',$latestDate,$paramLatestUpdate);

        Redirect(ThisPage());
        die();
    }

    if(isset($_POST['updateReihung']))
    {
        $reihungComboM = $_POST['reihungM'];
        $reihungComboW = $_POST['reihungW'];

        $clubID = $_POST['clubID'];

        $year = $_POST['year'];

        $reihungPartsW = explode('||',$reihungComboW);

        foreach(explode('||',$reihungComboM) as $rp)
        {
            $rp = explode('##',$rp);
            if(!isset($rp[1])) continue;

            $team = $_POST['team_'.$rp[1]];
            $mf = $_POST['mf_'.$rp[1]];
            $mobile = $_POST['mobile_'.$rp[1]];
            $email = $_POST['email_'.$rp[1]];

            $memberID = MySQL::Scalar("SELECT id FROM members WHERE playerID = ?",'s',$rp[1]);

            MySQL::NonQuery("UPDATE members_spielerranglisten SET currentClubID = ?, assignedClubID = ?, team = ?, mf = ?, position = ? WHERE memberID = ? AND year = ?",'@s',$clubID,$clubID,$team,$mf,$rp[0],$memberID,$year);
            MySQL::NonQuery("UPDATE members SET email = ?, mobileNr = ? WHERE id = ?",'@s',$email,$mobile,$memberID);
        }

        foreach(explode('||',$reihungComboW) as $rp)
        {
            $rp = explode('##',$rp);
            if(!isset($rp[1])) continue;

            $team = $_POST['team_'.$rp[1]];
            $mf = $_POST['mf_'.$rp[1]];
            $mobile = $_POST['mobile_'.$rp[1]];
            $email = $_POST['email_'.$rp[1]];

            $memberID = MySQL::Scalar("SELECT id FROM members WHERE playerID = ?",'s',$rp[1]);

            MySQL::NonQuery("UPDATE members_spielerranglisten SET currentClubID = ?, assignedClubID = ?, team = ?, mf = ?, position = ? WHERE memberID = ? AND year = ?",'@s',$clubID,$clubID,$team,$mf,$rp[0],$memberID,$year);
            MySQL::NonQuery("UPDATE members SET email = ?, mobileNr = ? WHERE id = ?",'@s',$email,$mobile,$memberID);
        }

        // Update last edit-Date
        $paramLatestUpdate = 'Y'.$year.'LastUpdate';
        $latestDate = date("Y-m-d");
        if(!MySQL::Exist("SELECT * FROM ranglisten_settings WHERE setting = ?",'s',$paramLatestUpdate))
        {
            MySQL::NonQuery("INSERT INTO ranglisten_settings (setting,value) VALUES (?,'')",'s',$paramLatestUpdate);
        }
        MySQL::NonQuery("UPDATE ranglisten_settings SET value = ? WHERE setting = ?",'ss',$latestDate,$paramLatestUpdate);

        if($_POST['returnToSamePage'] == 1) Redirect(ThisPage());
        else Redirect("/spielerreihung");
        die();
    }

//========================================================================================
//      /\  POST-SECTION
//========================================================================================
//========================================================================================
//      \/  VISUAL-SECTION
//========================================================================================

    if(isset($_SESSION['userID']) AND (CheckRank() == "clubmanager" OR CheckRank() == "administrative"))
    {
        if(!isset($_GET['jahr']))
        {
            Redirect(ThisPage("+jahr=".(date("Y")-1)."-".date("Y")));
            die();
        }

        if(isset($_GET['bearbeiten']))
        {

            $year = $_GET['jahr'];

            echo '
                <h1>Reihung bearbeiten ('.$year.')</h1>

            ';

            if(CheckRank()=="clubmanager")
            {
                $returnToSamePage = 0;
                $club = MySQL::Scalar("SELECT club FROM users WHERE id = ?",'i',$_SESSION['userID']);

                echo '
                    <div style="float: right; margin-top: -30px;">
                        Jahr ausw&auml;hlen:
                        <select onchange="RedirectSelectBox(this,\'/spielerreihung/bearbeiten?jahr=\');">
                        ';
                        for($i=date("Y");$i>=2011;$i--)
                        {
                            if($i == intval(date("Y")))
                            {
                                // Saisonwechsel mit 1. September
                                if(intval(date("m"))>= 9) echo '<option value="'.$i.'-'.($i+1).'" '.(($year==$i.'-'.($i+1)) ? 'selected' : '').' '.(SRLIsLocked($i.'-'.($i+1)) ? 'disabled' : '').'>'.$i.'-'.($i+1).'</option>';
                            }
                            else echo '<option value="'.$i.'-'.($i+1).'" '.(($year==$i.'-'.($i+1)) ? 'selected' : '').' '.(SRLIsLocked($i.'-'.($i+1)) ? 'disabled' : '').'>'.$i.'-'.($i+1).'</option>';
                        }
                        echo '
                        </select>
                    </div>
                ';
            }

            if(CheckRank()=="administrative")
            {
                $returnToSamePage = 1;
                if(isset($_GET['clubID'])) $club = $_GET['clubID'];
                else $club="";

                echo '
                    <div style="float: right; margin-top: -30px;">
                        Jahr ausw&auml;hlen:
                        <select onchange="RedirectSelectBoxParam(this,\'/spielerrangliste/reihungen/??/bearbeiten\');">
                        ';
                        for($i=date("Y");$i>=2011;$i--)
                        {
                            if($i == intval(date("Y")))
                            {
                                // Saisonwechsel mit 1. September
                                if(intval(date("m"))>= 9) echo '<option value="'.$i.'-'.($i+1).'" '.(($year==$i.'-'.($i+1)) ? 'selected' : '').'  '.(SRLIsLocked($i.'-'.($i+1)) ? 'disabled' : '').'>'.$i.'-'.($i+1).'</option>';
                            }
                            else echo '<option value="'.$i.'-'.($i+1).'" '.(($year==$i.'-'.($i+1)) ? 'selected' : '').' '.(SRLIsLocked($i.'-'.($i+1)) ? 'disabled' : '').'>'.$i.'-'.($i+1).'</option>';
                        }
                        echo '
                        </select>
                        &nbsp;&nbsp;&nbsp;&nbsp;Verein ausw&auml;hlen
                        <select class="cel_m" onchange="RedirectSelectBoxParam(this,\'/spielerrangliste/reihungen/'.$_GET['jahr'].'/bearbeiten/??\');">
                            <option value="" selected disabled>&#9660; Verein ausw&auml;hlen</option>
                        ';
                        $clubList = MySQL::Cluster("SELECT * FROM vereine WHERE isOOEClub = '1' ORDER BY ort, verein ASC");
                        foreach($clubList as $clubData) echo '<option '.($club == $clubData['kennzahl'] ? 'selected' : '').' value="'.$clubData['kennzahl'].'">'.$clubData['kennzahl'].' - '.$clubData['verein'].' '.$clubData['ort'].'</option>';
                        echo '
                        </select>

                    </div>
                ';
            }

            echo '

                <hr>
                <script>
                    $( function() {
                        $( "#sortListM" ).sortable();
                        $( "#sortListM" ).disableSelection();

                        $( "#sortListW" ).sortable();
                        $( "#sortListW" ).disableSelection();

                    } );

                    window.setInterval(function(){
                        CheckSortableListState("sortListM","outputM");
                        CheckSortableListState("sortListW","outputW");
                    }, 100);


                </script>
            ';

            if(CheckRank() == "administrative" AND $club == "")
            {
                echo '<br><br><center><h3>Bitte Verein und Jahr ausw&auml;hlen</h3></center>';
            }
            else if(SRLIsLocked($year))
            {
                echo '<br><br><center><h3>Bearbeitung f&uuml;r diese Rangliste ist bereits gesperrt!</h3></center>';
            }
            else
            {
                echo '
                    <p>
                        Spieler mit der Maus ziehen.
                    </p>
                    <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data" novalidate >
                        <input type="hidden" name="clubID" value="'.$club.'"/>
                        <input type="hidden" name="returnToSamePage" value="'.$returnToSamePage.'"/>
                        <div class="double_container">
                            <div style="overflow: visible;">
                                <center>
                                    <h3>Reihung Herren</h3>

                                    <a href="#spielerM"><button type="button">Spieler ausw&auml;hlen</button></a>
                                    <br>

                                    <ul class="dragSortList_posNumbers">
                                    ';
                                        $listedMembersAmt = MySQL::Scalar("SELECT position FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.gender = 'M' AND members.clubID = ? AND members_spielerranglisten.year = ? ORDER BY members_spielerranglisten.position DESC LIMIT 0,1",'@s',$club,$year);
                                        for($i=1;$i<$listedMembersAmt+1; $i++) echo '<li>'.$i.'</li>';
                                    echo '
                                    </ul>
                                    <ul class="dragSortList_values" id="sortListM">
                                    ';

                                        $currentSelectedMembersM = array();                                                                                                               // UPDATE 01.03.2019: Replaced members.clubID with members_spielerranglisten.assignedClubID
                                        $strSQL = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.gender='M' AND members_spielerranglisten.assignedClubID = '$club' AND members_spielerranglisten.year = '$year' ORDER BY members_spielerranglisten.position ASC";
                                        $rs=mysqli_query($link,$strSQL);
                                        while($row=mysqli_fetch_assoc($rs))
                                        {

                                            echo '
                                            <li value="'.$row['playerID'].'">
                                                '.$row['playerID'].' - '.$row['firstname'].' '.$row['lastname'].'
                                                <span style="float: right; font-size: 8pt; padding-right: 4px;" class="reihungFold">
                                                Mehr &raquo;
                                                <div class="infoContainer">
                                                    <table>
                                                        <tr>
                                                            <td class="ta_r">Team: </td>
                                                            <td><input name="team_'.$row['playerID'].'" type="number" class="cel_s sampleField" value="'.$row['team'].'" min="1" max="10"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ta_r">&Auml;nderungen/<br>Manschaftsf.:</td>
                                                            <td><input name="mf_'.$row['playerID'].'" type="text" class="cel_s sampleField"  value="'.$row['mf'].'"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ta_r">Handy-Nr.:</td>
                                                            <td><input name="mobile_'.$row['playerID'].'" type="text" class="cel_s sampleField" value="'.$row['mobileNr'].'"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ta_r">E-Mail:</td>
                                                            <td><input name="email_'.$row['playerID'].'" type="text" class="cel_s sampleField" value="'.$row['email'].'"/></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                </span>
                                            </li>';

                                            // Needed later for test if user is already in list or not
                                            array_push($currentSelectedMembersM,$row['playerID']);
                                        }
                                    echo '
                                    </ul>
                                    <br>
                                    <input type="hidden" id="outputM" name="reihungM"/>
                               </center>
                            </div>
                            <div style="overflow: visible;">
                                <center>
                                    <h3>Reihung Damen</h3>

                                    <a href="#spielerW"><button type="button">Spieler ausw&auml;hlen</button></a>
                                    <br>
                                    <ul class="dragSortList_posNumbers">
                                    ';
                                        $listedMembersAmt = MySQL::Scalar("SELECT position FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.gender = 'F' AND members.clubID = ? AND members_spielerranglisten.year = ? ORDER BY members_spielerranglisten.position DESC LIMIT 0,1",'@s',$club,$year);
                                        for($i=1;$i<$listedMembersAmt+1; $i++) echo '<li>'.$i.'</li>';
                                    echo '
                                    </ul>
                                    <ul class="dragSortList_values" id="sortListW">
                                    ';
                                        $currentSelectedMembersW = array();                                                                                                              // UPDATE 01.03.2019: Replaced members.clubID with members_spielerranglisten.assignedClubID    
                                        $strSQL = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.gender='F' AND members_spielerranglisten.assignedClubID = '$club' AND members_spielerranglisten.year = '$year' ORDER BY members_spielerranglisten.position ASC";
                                        $rs=mysqli_query($link,$strSQL);
                                        while($row=mysqli_fetch_assoc($rs))
                                        {
                                            echo '
                                            <li value="'.$row['playerID'].'">
                                                '.$row['playerID'].' - '.$row['firstname'].' '.$row['lastname'].'
                                                <span style="float: right; font-size: 8pt; padding-right: 4px;" class="reihungFold">
                                                Mehr &raquo;
                                                <div class="infoContainer">
                                                    <table>
                                                        <tr>
                                                            <td class="ta_r">Team: </td>
                                                            <td><input name="team_'.$row['playerID'].'" type="number" class="cel_s sampleField" value="'.$row['team'].'" min="1" max="10"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ta_r">&Auml;nderungen/<br>Manschaftsf.:</td>
                                                            <td><input name="mf_'.$row['playerID'].'" type="text" class="cel_s sampleField"  value="'.$row['mf'].'"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ta_r">Handy-Nr.:</td>
                                                            <td><input name="mobile_'.$row['playerID'].'" type="text" class="cel_s sampleField" value="'.$row['mobileNr'].'"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ta_r">E-Mail:</td>
                                                            <td><input name="email_'.$row['playerID'].'" type="text" class="cel_s sampleField" value="'.$row['email'].'"/></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                </span>
                                            </li>';

                                            // Needed later for test if user is already in list or not
                                            array_push($currentSelectedMembersW,$row['playerID']);
                                        }
                                    echo '
                                    </ul>
                                    <br>
                                    <input type="hidden" id="outputW" name="reihungW"/>
                                </center>
                            </div>
                            <br><br>
                            <button type="submit" name="updateReihung">Reihung aktualisieren</button>
                        </div>



                        <div class="modal_wrapper" id="spielerM">
                            <a href="#c">
                                <div class="modal_bg"></div>
                            </a>
                            <div class="modal_container" style="width: 50%; height: 40%;">
                                <h3>Spieler ausw&auml;hlen (Herren)</h3>
                                ';

                                $strSQL = "SELECT * FROM members WHERE clubID = '$club' AND gender = 'M' AND SUBSTRING(playerID,1,3) != 'TMP'";
                                $rs=mysqli_query($link,$strSQL);
                                while($row=mysqli_fetch_assoc($rs))
                                {
                                    $checked = in_array($row['playerID'],$currentSelectedMembersM) ? true : false;
                                    echo Tickbox("member".$row['playerID'],"member".$row['playerID'],$row['playerID'].' - '.$row['firstname'].' '.$row['lastname'],$checked);
                                }

                                echo '
                                <br><br>
                                <button type="submit" name="updateListM">Aktualisieren</button>
                            </div>
                        </div>


                        <div class="modal_wrapper" id="spielerW">
                            <a href="#c">
                                <div class="modal_bg"></div>
                            </a>
                            <div class="modal_container" style="width: 50%; height: 40%;">
                                <h3>Spieler ausw&auml;hlen (Damen)</h3>
                                ';

                                $strSQL = "SELECT * FROM members WHERE clubID = '$club' AND gender = 'F' AND SUBSTRING(playerID,1,3) != 'TMP'";
                                $rs=mysqli_query($link,$strSQL);
                                while($row=mysqli_fetch_assoc($rs))
                                {
                                    $checked = in_array($row['playerID'],$currentSelectedMembersW) ? true : false;
                                    echo Tickbox("member".$row['playerID'],"member".$row['playerID'],$row['playerID'].' - '.$row['firstname'].' '.$row['lastname'],$checked);
                                }

                                echo '
                                <br><br>

                                <input type="hidden" value="'.$year.'" name="year"/>

                                <button type="submit" name="updateListW">Aktualisieren</button>
                            </div>
                        </div>
                    </form>
                ';
            }

        }
        else
        {
            $club = MySQL::Scalar("SELECT club FROM users WHERE id = ?",'i',$_SESSION['userID']);  
            $year = $_GET['jahr'];

            echo '
                <h1>Spielerreihung</h1>
                <div style="float: right; margin-top: -30px;">
                    Jahr ausw&auml;hlen:
                    <select onchange="RedirectSelectBox(this,\'/spielerreihung?jahr=\');">
                    ';
                        for($i=date("Y");$i>=2011;$i--)
                        {
                            if($i == intval(date("Y")))
                            {
                                // Saisonwechsel mit 1. September
                                if(intval(date("m"))>= 9) echo '<option value="'.$i.'-'.($i+1).'" '.(($year==$i.'-'.($i+1)) ? 'selected' : '').'>'.$i.'-'.($i+1).'</option>';
                            }
                            else echo '<option value="'.$i.'-'.($i+1).'" '.(($year==$i.'-'.($i+1)) ? 'selected' : '').'>'.$i.'-'.($i+1).'</option>';
                        }
                        echo '
                    </select>
                </div>
                <hr>
                <h3>Aktuelle Reihung</h3>

                <div class="double_container">
                    <div style="overflow: visible;">
                        <center>
                            <h3>Herren</h3>

                            <br>
                            <ul class="dragSortList_posNumbers">
                            ';
                                $strSQL = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.gender = 'M' AND members_spielerranglisten.year = '$year' AND members_spielerranglisten.assignedClubID = '$club' ORDER BY members_spielerranglisten.position ASC";
                                $listedMembersAmt = MySQL::Count($strSQL);

                                for($i=1;$i<$listedMembersAmt+1; $i++) echo '<li>'.$i.'</li>';
                            echo '
                            </ul>
                            <ul class="dragSortListStatic_values" id="sortListW">
                            ';
                                $rs=mysqli_query($link,$strSQL);
                                while($row=mysqli_fetch_assoc($rs))
                                {
                                    echo '
                                    <li>
                                        '.$row['playerID'].' - '.$row['firstname'].' '.$row['lastname'].'
                                        <span style="float: right; font-size: 8pt; padding-right: 4px;" class="reihungFold">
                                            Mehr &raquo;
                                            <div class="infoContainerSmall">
                                                <table>
                                                    <tr>
                                                        <td class="ta_r">Team: </td>
                                                        <td>'.$row['team'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ta_r">&Auml;nderungen/<br>Manschaftsf.:</td>
                                                        <td>'.$row['mf'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ta_r">Handy-Nr.:</td>
                                                        <td>'.$row['mobileNr'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ta_r">E-Mail:</td>
                                                        <td>'.$row['email'].'</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </span>
                                    </li>
                                    ';
                                }
                            echo '
                            </ul>
                        </center>
                    </div>
                    <div style="overflow: visible;">
                        <center>
                            <h3>Damen</h3>

                            <br>
                            <ul class="dragSortList_posNumbers">
                            ';
                                $strSQL = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.gender = 'F' AND members_spielerranglisten.year = '$year' AND members_spielerranglisten.assignedClubID = '$club' ORDER BY members_spielerranglisten.position ASC";
                                $listedMembersAmt = MySQL::Count($strSQL);

                                for($i=1;$i<$listedMembersAmt+1; $i++) echo '<li>'.$i.'</li>';
                            echo '
                            </ul>
                            <ul class="dragSortListStatic_values" id="sortListW">
                            ';
                                $rs=mysqli_query($link,$strSQL);
                                while($row=mysqli_fetch_assoc($rs))
                                {
                                    echo '
                                    <li>
                                        '.$row['playerID'].' - '.$row['firstname'].' '.$row['lastname'].'
                                        <span style="float: right; font-size: 8pt; padding-right: 4px;" class="reihungFold">
                                            Mehr &raquo;
                                            <div class="infoContainerSmall">
                                                <table>
                                                    <tr>
                                                        <td class="ta_r">Team: </td>
                                                        <td>'.$row['team'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ta_r">&Auml;nderungen/<br>Manschaftsf.:</td>
                                                        <td>'.$row['mf'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ta_r">Handy-Nr.:</td>
                                                        <td>'.$row['mobileNr'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ta_r">E-Mail:</td>
                                                        <td>'.$row['email'].'</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </span>
                                    </li>
                                    ';
                                }
                            echo '
                            </ul>
                        </center>
                    </div>
                </div>
            ';

            echo '<br><br><a href="/spielerreihung/bearbeiten?jahr='.$_GET['jahr'].'">Reihung bearbeiten</a>';
        }
    }


    include("footer.php");
?>