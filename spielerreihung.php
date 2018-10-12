<?php
    require("header.php");

    if(isset($_POST['updateListM']) OR isset($_POST['updateListW']))
    {
        $selectedMembers = array();

        $club = Fetch("users","club","id",$_SESSION['userID']);
        if(isset($_POST['updateListM'])) $type='M';
        if(isset($_POST['updateListW'])) $type='W';
        $strSQL = "SELECT * FROM members WHERE club = '$club' AND gender = '$type'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            if(isset($_POST["member".$row['number']]))
            {
                array_push($selectedMembers,$row['number']);
            }
        }

        // Remove existing values from Database
        MySQLNonQuery("DELETE FROM reihung WHERE type = '$type' AND club = '$club'");

        $i=1;
        foreach($selectedMembers as $member)
        {
            $uid = uniqid();
            MySQLNonQuery("INSERT INTO reihung (id,type,member,club,position) VALUES ('$uid','$type','$member','$club','".$i++."')");
        }

        Redirect(ThisPage());
        die();
    }

    if(isset($_POST['updateReihung']))
    {
        $club = Fetch("users","club","id",$_SESSION['userID']);
        $reihungComboM = $_POST['reihungM'];
        $reihungComboW = $_POST['reihungW'];

        $reihungPartsW = explode('||',$reihungComboW);

        foreach(explode('||',$reihungComboM) as $rp)
        {
            $rp = explode('##',$rp);
            if(!isset($rp[1])) continue;

            MySQLNonQuery("UPDATE reihung SET position = '".$rp[0]."' WHERE member = '".$rp[1]."' AND club = '".$club."'");
        }

        foreach(explode('||',$reihungComboW) as $rp)
        {
            $rp = explode('##',$rp);
            if(!isset($rp[1])) continue;

            MySQLNonQuery("UPDATE reihung SET position = '".$rp[0]."' WHERE member = '".$rp[1]."' AND club = '".$club."'");
        }

        Redirect("/spielerreihung");
        die();
    }

    if(isset($_GET['bearbeiten']))
    {
        $club = Fetch("users","club","id",$_SESSION['userID']);

        echo '
            <h1>Reihung bearbeiten</h1>
            <hr>
            <p>
                Spieler mit der Maus ziehen.
            </p>

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

            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="double_container">
                    <div>
                        <center>
                            <h3>Reihung Herren</h3>

                            <a href="#spielerM"><button type="button">Spieler ausw&auml;hlen</button></a>
                            <br>

                            <ul class="dragSortList_posNumbers">
                            ';
                                $listedMembersAmt = MySQLSkalar("SELECT position AS x FROM reihung WHERE type = 'M' AND club = '$club' ORDER BY position DESC LIMIT 0,1");
                                for($i=1;$i<$listedMembersAmt+1; $i++) echo '<li>'.$i.'</li>';
                            echo '
                            </ul>
                            <ul class="dragSortList_values" id="sortListM">
                            ';
                                $currentSelectedMembersM = array();
                                $strSQL = "SELECT * FROM reihung INNER JOIN members ON reihung.member = members.number WHERE reihung.type='M' AND reihung.club = '$club' ORDER BY reihung.position ASC";
                                $rs=mysqli_query($link,$strSQL);
                                while($row=mysqli_fetch_assoc($rs))
                                {
                                    echo '<li value="'.$row['number'].'">'.$row['number'].' - '.$row['firstname'].' '.$row['lastname'].'</li>';

                                    // Needed later for test if user is already in list or not
                                    array_push($currentSelectedMembersM,$row['number']);
                                }
                            echo '
                            </ul>
                            <br>
                            <input type="hidden" id="outputM" name="reihungM"/>
                       </center>
                    </div>
                    <div>
                        <center>
                            <h3>Reihung Damen</h3>

                            <a href="#spielerW"><button type="button">Spieler ausw&auml;hlen</button></a>
                            <br>
                            <ul class="dragSortList_posNumbers">
                            ';
                                $listedMembersAmt = MySQLSkalar("SELECT position AS x FROM reihung WHERE type = 'W' AND club = '$club' ORDER BY position DESC LIMIT 0,1");
                                for($i=1;$i<$listedMembersAmt+1; $i++) echo '<li>'.$i.'</li>';
                            echo '
                            </ul>
                            <ul class="dragSortList_values" id="sortListW">
                            ';
                                $currentSelectedMembersW = array();
                                $strSQL = "SELECT * FROM reihung INNER JOIN members ON reihung.member = members.number WHERE reihung.type='W' AND reihung.club = '$club' ORDER BY reihung.position ASC";
                                $rs=mysqli_query($link,$strSQL);
                                while($row=mysqli_fetch_assoc($rs))
                                {
                                    echo '<li value="'.$row['number'].'">'.$row['number'].' - '.$row['firstname'].' '.$row['lastname'].'</li>';

                                    // Needed later for test if user is already in list or not
                                    array_push($currentSelectedMembersW,$row['number']);
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
                        <h3>Spieler ausw&auml;hlen</h3>
                        ';

                        $strSQL = "SELECT * FROM members WHERE club = '$club' AND gender = 'M'";
                        $rs=mysqli_query($link,$strSQL);
                        while($row=mysqli_fetch_assoc($rs))
                        {
                            $checked = in_array($row['number'],$currentSelectedMembersM) ? true : false;
                            echo Tickbox("member".$row['number'],"member".$row['number'],$row['number'].' - '.$row['firstname'].' '.$row['lastname'],$checked);
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
                        <h3>Spieler ausw&auml;hlen</h3>
                        ';

                        $strSQL = "SELECT * FROM members WHERE club = '$club' AND gender = 'W'";
                        $rs=mysqli_query($link,$strSQL);
                        while($row=mysqli_fetch_assoc($rs))
                        {
                            $checked = in_array($row['number'],$currentSelectedMembersW) ? true : false;
                            echo Tickbox("member".$row['number'],"member".$row['number'],$row['number'].' - '.$row['firstname'].' '.$row['lastname'],$checked);
                        }

                        echo '
                        <br><br>
                        <button type="submit" name="updateListW">Aktualisieren</button>
                    </div>
                </div>
            </form>
        ';

    }
    else
    {
        $club = Fetch("users","club","id",$_SESSION['userID']);

        echo '
            <h1>Spielerreihung</h1>
            <hr>
            <h3>Aktuelle Reihung</h3>

            <div class="double_container">
                <div>
                    <center>
                        <h3>Herren</h3>

                        <br>
                        <ul class="dragSortList_posNumbers">
                        ';
                            $listedMembersAmt = MySQLSkalar("SELECT position AS x FROM reihung WHERE type = 'M' AND club = '$club' ORDER BY position DESC LIMIT 0,1");
                            for($i=1;$i<$listedMembersAmt+1; $i++) echo '<li>'.$i.'</li>';
                        echo '
                        </ul>
                        <ul class="dragSortListStatic_values" id="sortListW">
                        ';
                            $strSQL = "SELECT * FROM reihung INNER JOIN members ON reihung.member = members.number WHERE reihung.type='M' AND reihung.club = '$club' ORDER BY reihung.position ASC";
                            $rs=mysqli_query($link,$strSQL);
                            while($row=mysqli_fetch_assoc($rs)) echo '<li>'.$row['number'].' - '.$row['firstname'].' '.$row['lastname'].'</li>';
                        echo '
                        </ul>
                    </center>
                </div>
                <div>
                    <center>
                        <h3>Damen</h3>

                        <br>
                        <ul class="dragSortList_posNumbers">
                        ';
                            $listedMembersAmt = MySQLSkalar("SELECT position AS x FROM reihung WHERE type = 'W' AND club = '$club' ORDER BY position DESC LIMIT 0,1");
                            for($i=1;$i<$listedMembersAmt+1; $i++) echo '<li>'.$i.'</li>';
                        echo '
                        </ul>
                        <ul class="dragSortListStatic_values" id="sortListW">
                        ';
                            $strSQL = "SELECT * FROM reihung INNER JOIN members ON reihung.member = members.number WHERE reihung.type='W' AND reihung.club = '$club' ORDER BY reihung.position ASC";
                            $rs=mysqli_query($link,$strSQL);
                            while($row=mysqli_fetch_assoc($rs)) echo '<li>'.$row['number'].' - '.$row['firstname'].' '.$row['lastname'].'</li>';
                        echo '
                        </ul>
                    </center>
                </div>
            </div>
        ';

        echo '<br><br><a href="/spielerreihung/bearbeiten">Reihung bearbeiten</a>';
    }


    include("footer.php");
?>