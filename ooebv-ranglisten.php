<?php
    require("header.php");
    PageTitle("O\u00d6BV-Ranglisten");

    if(isset($_POST['createNewList']))
    {
        $listName = $_POST['name'];
        $footnote = $_POST['footnote'];
        $listFilename = SReplace($listName);

        MySQL::NonQuery("INSERT INTO ooebvrl_lists (id, listName, listFilename, listFootnote) VALUES ('',?,?,?)",'sss',$listName,$listFilename,$footnote);

        Redirect("/ooebv-ranglisten");
        die();
    }

    if(isset($_POST['editList']))
    {
        $listID = $_POST['id'];
        $listName = $_POST['name'];
        $footnote = $_POST['footnote'];
        $listFilename = SReplace($listName);

        MySQL::NonQuery("UPDATE ooebvrl_lists SET listName = ?, listFilename = ?, listFootnote = ? WHERE id = ?",'ssss',$listName,$listFilename,$footnote,$listID);

        Redirect("/ooebv-ranglisten");
        die();
    }

    if(isset($_POST['createNewTable']))
    {
        $tableName = $_POST['name'];
        $headerColor = $_POST['headerColor'];
        $roundCount = $_POST['roundCount'];
        $tableFilename = SReplace($tableName);
        $showLastEdit = (isset($_POST['showLastEdit']) ? 1 : 0);
        $lastEdit = date("Y-m-d");

        $listID = MySQL::Scalar("SELECT id FROM ooebvrl_lists WHERE listFilename = ?",'s',$_GET['list']);

        MySQL::NonQuery("INSERT INTO ooebvrl_tables (id, listID, tableName, tableFilename, headerColor, showLastEdit, lastEdit, roundCount) VALUES ('',?,?,?,?,?,?,?)",'sssssss',$listID,$tableName,$tableFilename,$headerColor,$showLastEdit,$lastEdit,$roundCount);

        Redirect("/ooebv-ranglisten");
        die();
    }

    if(isset($_POST['editTable']))
    {
        $tableID = $_POST['id'];
        $tableName = $_POST['name'];
        $headerColor = $_POST['headerColor'];
        $roundCount = $_POST['roundCount'];
        $tableFilename = SReplace($tableName);
        $showLastEdit = (isset($_POST['showLastEdit']) ? 1 : 0);
        $lastEdit = date("Y-m-d");

        MySQL::NonQuery("UPDATE ooebvrl_tables SET tableName = ?, tableFilename = ?, headerColor = ?, showLastEdit = ?, lastEdit = ?, roundCount = ? WHERE id = ?",'sssssss',$tableName,$tableFilename,$headerColor,$showLastEdit,$lastEdit,$roundCount,$tableID);

        Redirect("/ooebv-ranglisten");
        die();
    }

    if(isset($_POST['createNewSection']))
    {
        $sectionName = $_POST['name'];
        $sectionInfoLeft = $_POST['infoLeft'];
        $sectionInfoRight = $_POST['infoRight'];
        $sectionFilename = SReplace($sectionName);

        $listID = MySQL::Scalar("SELECT id FROM ooebvrl_lists WHERE listFilename = ?",'s',$_GET['list']);
        $tableID = MySQL::Scalar("SELECT id FROM ooebvrl_tables WHERE tableFilename = ?",'s',$_GET['table']);

        MySQL::NonQuery("INSERT INTO ooebvrl_sections (id, listID, tableID, sectionName, sectionFilename, sectionInfoLeft, sectionInfoRight) VALUES ('',?,?,?,?,?,?)",'ssssss',$listID,$tableID,$sectionName,$sectionFilename,$sectionInfoLeft,$sectionInfoRight);

        Redirect('/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table']);
        die();
    }

    if(isset($_POST['editSection']))
    {
        $sectionID = $_POST['id'];
        $sectionName = $_POST['name'];
        $sectionInfoLeft = $_POST['infoLeft'];
        $sectionInfoRight = $_POST['infoRight'];
        $sectionFilename = SReplace($sectionName);

        MySQL::NonQuery("UPDATE ooebvrl_sections SET sectionName = ?, sectionFilename = ?, sectionInfoLeft = ?, sectionInfoRight = ? WHERE id = ?",'sssss',$sectionName,$sectionFilename,$sectionInfoLeft,$sectionInfoRight,$sectionID);

        Redirect('/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table']);
        die();
    }

    if(isset($_POST['updatePlayerList']))
    {
        // Set last Updates
        $date = date("Y-m-d");
        MySQL::NonQuery("UPDATE ooebvrl_tables SET lastEdit = ? WHERE tableFilename = ?",'ss',$date,$_GET['table']);


        $sectionID = MySQL::Scalar("SELECT id FROM ooebvrl_sections WHERE sectionFilename = ?",'s',$_GET['section']);

        // Remove all existing entries from table
        MySQL::NonQuery("DELETE FROM members_ooebvrl WHERE sectionID = ?",'s',$sectionID);

        $rankArray = array();
        $rankList = $_POST['rankList'];
        $rankParts = explode('||',$rankList);

        $i = 1;
        while(isset($_POST['playerID'.$i]) AND ($_POST['playerID'.$i] != "" OR $_POST['name'.$i] != "" OR $_POST['clubID'.$i] != "" OR $_POST['jg'.$i] != "" OR $_POST['str'.$i] != "" OR $_POST['ges'.$i] != "" ) OR (isset($_POST['isSep'.$i]) AND $_POST['isSep'.$i] == "1"))
        {
            // Determine Rank
            foreach($rankParts AS $rankSegment)
            {
                $rankP = explode('##',$rankSegment);
                if(!isset($rankP[1])) continue;
                if($i == $rankP[1]) $rank = $rankP[0];
            }

            if(!isset($_POST['isSep'.$i]))
            {
                $id = uniqid();
                $playerID = $_POST['playerID'.$i];
                $name = $_POST['name'.$i];
                $clubID = $_POST['clubID'.$i];
                $jg = $_POST['jg'.$i];
                $str = $_POST['str'.$i];
                $ges = $_POST['ges'.$i];


                if($clubID != "")
                {
                    // Check if member exists. If not create new member in Members Table.
                    // If no Player-ID is given, create a new member with a Temp-ID
                    if($playerID != "" AND MySQL::Exist("SELECT * FROM members WHERE playerID = ?",'s',$playerID))
                    {
                        $memberID = MySQL::Scalar("SELECT id FROM members WHERE playerID = ?",'s',$playerID);
                    }
                    else if(isset($_POST['tempPlayerID'.$i]) AND $_POST['tempPlayerID'.$i]=="" AND MySQL::Exist("SELECT * FROM members WHERE playerID = ?",'s',$_POST['tempPlayerID'.$i]))
                    {
                        $memberID = MySQL::Scalar("SELECT id FROM members WHERE playerID = ?",'s',$_POST['tempPlayerID'.$i]);
                    }
                    else
                    {
                        $memberID = uniqid();
                        $nameParts = explode(' ',$name);
                        $lastname = rtrim(ltrim($nameParts[count($nameParts)-1],' '),' ');
                        $firstname = rtrim(ltrim(str_replace($lastname,'',$name),' '),' ');
                        $birthdate = "20".str_pad($jg, 2, "0", STR_PAD_LEFT)."-01-01";

                        if($playerID == "") $playerID = "TMP".uniqid();

                        MySQL::NonQuery("INSERT INTO members (id,clubID,playerID,firstname, lastname, birthdate) VALUES (?,?,?,?,?,?)",'@s',$memberID,$clubID, $playerID,$firstname,$lastname,$birthdate);
                    }

                    MySQL::NonQuery("INSERT INTO members_ooebvrl (id, memberID, sectionID, rank, str, ges) VALUES (?,?,?,?,?,?)",'ssssss',$id,$memberID,$sectionID,$rank,$str,$ges);

                    $j = 1;
                    while(isset($_POST[$j.'rd'.$i]))
                    {
                        MySQL::NonQuery("UPDATE members_ooebvrl SET round".$j." = ? WHERE id = ?",'ss',$_POST[$j.'rd'.$i],$id);
                        $j++;
                    }
                }
            }
            else
            {
                $sepText = $_POST['sepText'.$i];
                $elementID = uniqid();

                MySQL::NonQuery("INSERT INTO members_ooebvrl (id, sectionID, sepText,rank, isSep) VALUES (?,?,?,?,'1')",'ssss',$elementID,$sectionID,$sepText,$rank);
            }

            $i++;
        }

        Redirect('/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table']);
        die();
    }

    if(isset($_GET['sepparate']))
    {
        $id = uniqid();
        $sectionID = MySQL::Scalar("SELECT id FROM ooebvrl_sections WHERE sectionFilename = ?",'s',$_GET['section']);

        MySQL::NonQuery("INSERT INTO members_ooebvrl (id, sectionID, rank, IsSep) VALUES (?,?,?,'1')",'sss',$id,$sectionID,$_GET['lastRank']);

        Redirect('/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table'].'/'.$_GET['section'].'/spieler');
        die();
    }

//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//  /\  POST        MAIN-PAGE \/
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////

    echo '<h1 class="stagfade1">O&Ouml;BV-Ranglisten</h1>';

    if(isset($_GET['new']))
    {
        if(isset($_GET['newList']))
        {
            echo '<h2>Neue Liste erstellen</h2>';

            echo '
                <br><br>
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Listen-Name:</td>
                            <td><input type="text" placeholder="Listen-Name..." name="name"/></td>
                        </tr>
                        <tr>
                            <td>Fu&szlig;note:</td>
                            <td><input type="text" placeholder="Fu&szlig;note..." name="footnote"/></td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <br>
                                <center><button type="submit" name="createNewList">Liste erstellen</button><center>
                            </td>
                        </tr>
                    </table>
                </form>
            ';
        }

        if(isset($_GET['newTable']))
        {
            echo '<h2>Neue Tabelle erstellen</h2>';

            echo '
                <br><br>
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Tabellen-Name:</td>
                            <td><input class="cel_100" type="text" placeholder="Tabellen-Name..." name="name"/></td>
                        </tr>
                        <tr>
                            <td>Rundenanzahl:</td>
                            <td><input class="cel_100" type="number" min="1" max="9" placeholder="Rundenanzahl..." name="roundCount"/></td>
                        </tr>
                        <tr>
                            <td>Kopfzeilen-<br>farbe:</td>
                            <td>'.ColorPicker("headerColor","headerColor","Farbe w&auml;hlen","#1E90FF").'</td>
                        </tr>
                        <tr>
                            <td>Letzte Bearbeitung<br>anzeigen:</td>
                            <td><center>'.Tickbox("showLastEdit","showLastEdit","",true).'</center></td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <br>
                                <center><button type="submit" name="createNewTable">Tabelle erstellen</button><center>
                            </td>
                        </tr>
                    </table>
                </form>
            ';
        }

        if(isset($_GET['newSection']))
        {
            echo '<h2>Neue Tabellen-Sektion erstellen</h2>';

            echo '
                <br><br>
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Sektions-Name:</td>
                            <td><textarea name="name" placeholder="Sektions-Name..."></textarea></td>
                        </tr>
                        <tr>
                            <td>Sektions-Info<br>Links:</td>
                            <td><textarea name="infoLeft" placeholder="Sektions-Info..."></textarea></td>
                        </tr>
                        <tr>
                            <td>Sektions-Info<br>Rechts:</td>
                            <td><textarea name="infoRight" placeholder="Sektions-Info..."></textarea></td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <br>
                                <center><button type="submit" name="createNewSection">Sektion erstellen</button><center>
                            </td>
                        </tr>
                    </table>
                </form>
            ';
        }

        if(isset($_GET['addPlayers']))
        {
            $list = $_GET['list'];
            $table = $_GET['table'];
        }
    }
    else if(isset($_GET['edit']))
    {
        if(isset($_GET['editList']))
        {
            $listData = MySQL::Row("SELECT * FROM ooebvrl_lists WHERE listFilename = ?",'s',$_GET['list']);

            echo '<h2>Liste bearbeiten</h2>';

            echo '
                <br><br>
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Listen-Name:</td>
                            <td><input type="text" placeholder="Listen-Name..." name="name" value="'.$listData['listName'].'"/></td>
                        </tr>
                        <tr>
                            <td>Fu&szlig;note:</td>
                            <td><input type="text" placeholder="Fu&szlig;note..." name="footnote" value="'.$listData['listFootnote'].'"/></td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <br>
                                <input type="hidden" name="id" value="'.$listData['id'].'"/>
                                <center><button type="submit" name="editList">Liste aktualisieren</button><center>
                            </td>
                        </tr>
                    </table>
                </form>
            ';
        }

        if(isset($_GET['editTable']))
        {
            $tableData = MySQL::Row("SELECT * FROM ooebvrl_tables WHERE tableFilename = ?",'s',$_GET['table']);

            echo '<h2>Tabelle bearbeiten</h2>';

            echo '
                <br><br>
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Tabellen-Name:</td>
                            <td><input class="cel_100" type="text" placeholder="Tabellen-Name..." name="name" value="'.$tableData['tableName'].'"/></td>
                        </tr>
                        <tr>
                            <td>Rundenanzahl:</td>
                            <td><input class="cel_100" type="number" min="1" max="9" placeholder="Rundenanzahl..." name="roundCount" value="'.$tableData['roundCount'].'"/></td>
                        </tr>
                        <tr>
                            <td>Kopfzeilen-<br>farbe:</td>
                            <td>'.ColorPicker("headerColor","headerColor","Farbe w&auml;hlen","#".$tableData['headerColor']).'</td>
                        </tr>
                        <tr>
                            <td>Letzte Bearbeitung<br>anzeigen:</td>
                            <td><center>'.Tickbox("showLastEdit","showLastEdit","",($tableData['showLastEdit'] == 1) ? true : false).'</center></td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <br>
                                <input type="hidden" name="id" value="'.$tableData['id'].'"/>
                                <center><button type="submit" name="editTable">Tabelle aktualisieren</button><center>
                            </td>
                        </tr>
                    </table>
                </form>
            ';
        }

        if(isset($_GET['editSection']))
        {
            $sectionData = MySQL::Row("SELECT * FROM ooebvrl_sections WHERE sectionFilename = ?",'s',$_GET['section']);

            echo '<h2>Neue Tabellen-Sektion erstellen</h2>';

            echo '
                <br><br>
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Sektions-Name:</td>
                            <td><textarea name="name" placeholder="Sektions-Name...">'.$sectionData['sectionName'].'</textarea></td>
                        </tr>
                        <tr>
                            <td>Sektions-Info<br>Links:</td>
                            <td><textarea name="infoLeft" placeholder="Sektions-Info...">'.$sectionData['sectionInfoLeft'].'</textarea></td>
                        </tr>
                        <tr>
                            <td>Sektions-Info<br>Rechts:</td>
                            <td><textarea name="infoRight" placeholder="Sektions-Info...">'.$sectionData['sectionInfoRight'].'</textarea></td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <br>
                                <input type="hidden" name="id" value="'.$sectionData['id'].'"/>
                                <center><button type="submit" name="editSection">Sektion aktualisieren</button><center>
                            </td>
                        </tr>
                    </table>
                </form>
            ';
        }

        if(isset($_GET['editplayer']))
        {
            $list = $_GET['list'];
            $table = $_GET['table'];
        }
    }
    else if(isset($_GET['manage']))
    {
        $list = $_GET['list'];
        $table = $_GET['table'];
        $section = $_GET['section'];

        if(isset($_GET['add']))
        {
            echo '<h2>Spieler hinzuf&uuml;gen</h2>';

            if(!isset($_GET['playerCount']))
            {
                echo '
                    <form action="/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table'].'/'.$_GET['section'].'/spieler/hinzufuegen?playerCount" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <td>Spieler hinzuf&uuml;gen (Anz.)</td>
                                <td><input type="number" placeholder="Anzahl Spieler..." name="amountPlayers"/></td>
                            </tr>
                            <tr>
                                <td colspan=2>
                                    <center><button type="submit">Spieler hinzuf&uuml;gen</button></center>
                                </td>
                            </tr>
                        </table>
                    </form>
                ';
            }
        }
        else
        {
            if(CheckPermission("AddOOEBVRLJgnd"))
            {
                echo AddButton('/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table'].'/'.$_GET['section'].'/spieler/hinzufuegen',false,false,"Spieler hinzuf&uuml;gen");

                $sectionID = MySQL::Scalar("SELECT id FROM ooebvrl_sections WHERE sectionFilename = ?",'s',$_GET['section']);
                $nextRankPos = MySQL::Count("SELECT * FROM members_ooebvrl WHERE sectionID = ?",'s',$sectionID);
                $nextRankPos++;

                echo AddButton('/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table'].'/'.$_GET['section'].'/'.$nextRankPos.'/trennlinie/',false,false,"Trennlinie einf&uuml;gen");
            }
        }



        echo '
            <center>
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <script>
                        $( function() {
                            $( "#sortList" ).sortable();
                            $( "#sortList" ).disableSelection();
                        } );

                        window.setInterval(function(){
                            CheckSortableListState("sortList","sortListOutput");
                        }, 100);
                    </script>
                    <textarea style="display: none" name="rankList" id="sortListOutput"></textarea>
        ';

        $clubList = MySQL::Cluster("SELECT * FROM vereine ORDER BY ort,verein ASC");

        $playerAddCount = 0;
        if(isset($_POST['amountPlayers'])) $playerAddCount = $_POST['amountPlayers'];

        $roundCount = MySQL::Scalar("SELECT roundCount FROM ooebvrl_tables INNER JOIN ooebvrl_lists ON ooebvrl_lists.id = ooebvrl_tables.listID WHERE ooebvrl_tables.tableFilename = ? AND ooebvrl_lists.listFilename = ?",'ss',$_GET['table'],$_GET['list']);
        $players = MySQL::Cluster("SELECT *, members_ooebvrl.id AS entryID FROM members_ooebvrl INNER JOIN ooebvrl_sections ON members_ooebvrl.sectionID = ooebvrl_sections.id INNER JOIN ooebvrl_tables ON ooebvrl_sections.tableID = ooebvrl_tables.id INNER JOIN ooebvrl_lists ON ooebvrl_tables.listID = ooebvrl_lists.id WHERE sectionFilename = ? AND tableFilename = ? AND listFilename = ? ORDER BY members_ooebvrl.rank ASC",'sss',$_GET['section'],$_GET['table'],$_GET['list']);
        $i=1;

        echo '
            <div class="rlSortContainer">
                <ul class="rlSortableListCount">
                    <li><div>Rang</div></li>
                </ul>
                <ul class="rlSortableListData">
                    <li>
                        <table>
                            <tr>
                                <td style="width: 21px;"></td>
                                <td style="width: 100px;">MgNr.</td>
                                <td style="width: 171px;">Name</td>
                                <td style="width: 180px;">Verein</td>
                                <td style="width: 50px;"></td>
                                <td style="width: 50px;">Jg.</td>
                                ';
                                for($rc=1;$rc<=$roundCount;$rc++) echo '<td style="width: 50px;">'.$rc.'. Rd.</td>';
                                echo '
                                <td style="width: 50px;">Str.</td>
                                <td style="width: 50px;">Ges.</td>
                                <td style="width: 18px;"></td>
                            </tr>
                        </table>
                    </li>
                </ul>
            </div>
        ';


        echo '
            <div class="rlSortContainer">
                <ul class="rlSortableListCount">
        ';

        for($i = 1; $i <= count($players) + $playerAddCount; $i++) echo '<li><div>'.$i.'</div></li>';
        echo '
            </ul>
            <ul class="rlSortableListData" id="sortList">
        ';



        $p = 1;
        foreach($players AS $player)
        {
            $clubMemberData = MySQL::Row("SELECT * FROM members INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members.id = ?",'s',$player['memberID']);

            if(!$player['isSep'])
            {
                echo '
                    <li value="'.$p.'">
                        <table>
                            <tr>
                                <td><i class="fas fa-grip-horizontal"></i></td>
                                <td>
                                    <input class="cel_s" type="number" placeholder="MgNr." name="playerID'.$p.'" value="'.((!StartsWith($clubMemberData['playerID'],'TMP')) ? $clubMemberData['playerID'] : '').'"/>
                                    <input type="hidden" name="tempPlayerID'.$p.'" value="'.((StartsWith($clubMemberData['playerID'],'TMP')) ? $clubMemberData['playerID'] : '').'"/>
                                </td>
                                <td><input class="" type="text" placeholder="Name" name="name'.$p.'" value="'.$clubMemberData['firstname'].' '.$clubMemberData['lastname'].'"/></td>
                                <td>
                                    <input type="hidden" name="clubID'.$p.'" id="outClubID'.$p.'" value="'.$clubMemberData['clubID'].'"/>
                                    <input class="cel_m" type="text" placeholder="Verein" id="outClub'.$p.'" value="'.$clubMemberData['verein'].' '.$clubMemberData['ort'].'" readonly/>
                                    <select class="cel_xs" onchange="CopyValueToElement(this,\'outClubID'.$p.'\'); CopyDisplayToElement(this,\'outClub'.$p.'\'); ResetDropdown(this)">
                                        <option value="" selected disabled>&#9660;</option>
                                        ';
                                        foreach($clubList as $club) echo '<option value="'.$club['kennzahl'].'">'.$club['verein'].' '.$club['ort'].'</option>';
                                        echo '
                                    </select>
                                </td>
                                <td><input class="cel_xs" type="number" placeholder="Jg." name="jg'.$p.'" value="'.date_format(date_create($clubMemberData['birthdate']),"y").'"/></td>
                                ';
                                for($rc=1;$rc<=$roundCount;$rc++) echo '<td><input class="cel_xs" type="number" placeholder="'.$rc.'.Rd." name="'.$rc.'rd'.$p.'" value="'.$player['round'.$rc].'"/></td>';
                                echo '
                                <td><input class="cel_xs" type="number" placeholder="Str." name="str'.$p.'"value="'.$player['str'].'"/></td>
                                <td><input class="cel_xs" type="number" placeholder="Ges." name="ges'.$p.'"value="'.$player['ges'].'"/></td>
                                <td>'.(CheckPermission("DeleteOOEBVRLJgnd") ? DeleteButton("OOEBVRLJgnd","members_ooebvrl",$player['entryID'],true) : '').'</td>
                            </tr>
                        </table>
                    </li>
                ';
            }
            else
            {
                echo '
                <li value="'.$p.'">
                    <input type="hidden" value="1" name="isSep'.$p.'"/>
                    <table>
                        <tr>
                            <td><i class="fas fa-grip-horizontal"></i></td>
                            <td>Trennlinie - Bezeichnung: <input type="text" placeholder="Bezeichnung..." name="sepText'.$p.'" value="'.$player['sepText'].'"/></td>
                            <td>'.(CheckPermission("DeleteOOEBVRLJgnd") ? DeleteButton("OOEBVRLJgnd","members_ooebvrl",$player['entryID'],true) : '').'</td>
                        </tr>
                    </table>
                    </li>
                ';
            }

            $p++;
        }

        if(isset($_POST['amountPlayers']))
        {
            for($i = $p; $i <= count($players) + $playerAddCount; $i++)
            {
                echo '
                <li value="'.$i.'">
                    <table>
                        <tr>
                            <td><i class="fas fa-grip-horizontal"></i></td>
                            <td>
                                <input class="cel_s" type="number" placeholder="MgNr." name="playerID'.$i.'"/>
                                <input class="cel_s" type="hidden" name="isTemp'.$i.'" value="0"/>
                            </td>
                            <td><input class="" type="text" placeholder="Name" name="name'.$i.'"/></td>
                            <td>
                                <input type="hidden" name="clubID'.$i.'" id="outClubID'.$i.'"/>
                                <input class="cel_m" type="text" placeholder="Verein" id="outClub'.$i.'" readonly/>
                                <select class="cel_xs" onchange="CopyValueToElement(this,\'outClubID'.$i.'\'); CopyDisplayToElement(this,\'outClub'.$i.'\'); ResetDropdown(this)">
                                    <option value="" selected disabled>&#9660;</option>
                                    ';
                                    foreach($clubList as $club) echo '<option value="'.$club['kennzahl'].'">'.$club['verein'].' '.$club['ort'].'</option>';
                                    echo '
                                </select>
                            </td>
                            <td><input class="cel_xs" type="number" placeholder="Jg." name="jg'.$i.'"/></td>
                            ';
                            for($rc=1;$rc<=$roundCount;$rc++) echo '<td><input class="cel_xs" type="number" placeholder="'.$rc.'.Rd." name="'.$rc.'rd'.$i.'"/></td>';
                            echo '
                            <td><input class="cel_xs" type="number" placeholder="Str." name="str'.$i.'"/></td>
                            <td><input class="cel_xs" type="number" placeholder="Ges." name="ges'.$i.'"/></td>
                        </tr>
                    </table>
                    </li>
                ';
            }
        }

        echo '
                        </ul>
                    </div>
                    <button type="submit" name="updatePlayerList">Reihung aktualisieren</button>
                </form>
            </center>
        ';


    }
    else if(isset($_GET['show']))
    {
        if(CheckPermission("addOOEBVRLJgnd")) echo AddButton("/ooebv-ranglisten/".$_GET['list']."/".$_GET['table']."/neue-sektion",false,false,"Sektion hinzuf&uuml;gen");

        $listID = MySQL::Scalar("SELECT id FROM ooebvrl_lists WHERE listFilename = ?",'s',$_GET['list']);
        $tableID = MySQL::Scalar("SELECT id FROM ooebvrl_tables WHERE tableFilename = ?",'s',$_GET['table']);

        $sections = MySQL::Cluster("SELECT *,ooebvrl_sections.id AS sectionID FROM ooebvrl_sections INNER JOIN ooebvrl_tables ON ooebvrl_sections.tableID = ooebvrl_tables.id WHERE ooebvrl_sections.listID = ? AND ooebvrl_sections.tableID = ?",'ss',$listID,$tableID);

        echo '
            <br><br>
                <a href="#export"><button type="button">Exportieren</button></a>
        ';

        $roundCount = MySQL::Scalar("SELECT roundCount FROM ooebvrl_tables INNER JOIN ooebvrl_lists ON ooebvrl_lists.id = ooebvrl_tables.listID WHERE ooebvrl_tables.tableFilename = ? AND ooebvrl_lists.listFilename = ?",'ss',$_GET['table'],$_GET['list']);

        foreach($sections AS $section)
        {
            echo '
                <center>
                    <table class="ooebvRanglistenTable">
                        <tr style="background: #'.$section['headerColor'].'">
                            <td colspan=2>'.nl2br($section['sectionInfoLeft']).'</td>
                            <td colspan='.(1 + $roundCount).' style="font-size: 16pt; font-weight: normal">'.(CheckPermission("editOOEBVRLJgnd") ? EditButton("/ooebv-ranglisten/".$_GET['list']."/".$_GET['table']."/".$section['sectionFilename']."/bearbeiten",true) : '').' '.(CheckPermission("deleteOOEBVRLJgnd") ? DeleteButton("OOEBVRLJgnd","ooebvrl_sections",$section['sectionID'],true) : '').' '.nl2br($section['sectionName']).'</td>
                            <td colspan=4>'.nl2br($section['sectionInfoRight']).'</td>
                        </tr>
                        <tr style="background: #'.$section['headerColor'].'">
                            <td>Rang</td>
                            <td>MgNr.</td>
                            <td>Name</td>
                            <td>Verein</td>
                            <td>Jg.</td>
                            ';
                            for($rc=1;$rc<=$section['roundCount'];$rc++) echo '<td>'.$rc.'. Rd</td>';
                            echo '
                            <td>Str.</td>
                            <td>Ges.</td>
                        </tr>
            ';

            $players = MySQL::Cluster("SELECT * FROM members_ooebvrl INNER JOIN ooebvrl_sections ON members_ooebvrl.sectionID = ooebvrl_sections.id INNER JOIN ooebvrl_tables ON ooebvrl_sections.tableID = ooebvrl_tables.id INNER JOIN ooebvrl_lists ON ooebvrl_tables.listID = ooebvrl_lists.id WHERE ooebvrl_lists.listFilename = ? AND ooebvrl_tables.tableFilename = ? AND ooebvrl_sections.sectionFilename = ? ORDER BY members_ooebvrl.rank ASC",'sss',$_GET['list'],$_GET['table'],$section['sectionFilename']);

            $showRankNr = true;
            $rankOffset = 0;
            foreach($players AS $player)
            {
                $clubMemberData = MySQL::Row("SELECT * FROM members INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members.id = ?",'s',$player['memberID']);



                if(!$player['isSep'])
                {
                    echo '
                        <tr>
                            <td>'.($showRankNr ? (($player['rank'] + $rankOffset).'.') : 'NR' ).'</td>
                            <td>'.((!StartsWith($clubMemberData['playerID'],'TMP')) ? $clubMemberData['playerID'] : '').'</td>
                            <td '.((StartsWith($clubMemberData['playerID'],'TMP')) ? 'style="color: #1E90FF; font-weight: bold;"' : '').'>'.$clubMemberData['firstname'].' '.$clubMemberData['lastname'].'</td>
                            <td '.((StartsWith($clubMemberData['playerID'],'TMP')) ? 'style="color: #1E90FF; font-weight: bold;"' : '').'>'.$clubMemberData['verein'].' '.$clubMemberData['ort'].'</td>
                            <td>'.date_format(date_create($clubMemberData['birthdate']),"y").'</td>
                            ';
                            for($rc=1;$rc<=$section['roundCount'];$rc++) echo '<td>'.$player['round'.$rc].'</td>';
                            echo '
                            <td>'.$player['str'].'</td>
                            <td>'.$player['ges'].'</td>
                        </tr>
                    ';
                }
                else
                {
                    $rankOffset -=1;
                    $showRankNr = false;
                    echo '
                        <tr style="background: #84E184; font-weight: bold; border-top: 2px solid black;">
                            <td style="text-align: left;" colspan='.(7+$section['roundCount']).'>'.$player['sepText'].'</td>
                        </tr>
                    ';
                }
            }

            echo '
                    </table>
                </center>
            ';



            if(CheckPermission("addOOEBVRLJgnd") OR CheckPermission("addOOEBVRLJgnd")) echo '<center>'.AddButton('/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table'].'/'.$section['sectionFilename'].'/spieler',false,false,"Spieler hinzuf&uuml;gen / Reihung &auml;ndern").'</center>';
            echo '<br><br>';
        }




        echo '
            <div class="modal_wrapper" id="export">
                <a href="#c"><div class="modal_bg"></div></a>
                <div class="modal_container" style="width: 370px; height: 160px; overflow-y: hidden">
                    <center>
                    <a href="/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table'].'/pdf"><button type="button"><span style="font-size: 60pt; color: #FFFFFF;"><i class="fas fa-file-pdf"></i></span><br>PDF</button></a>
                    <button type="button" disabled><span style="font-size: 60pt; color: #FFFFFF;"><i class="fas fa-file-excel"></i></span><br>Excel</button>
                    <button type="button" disabled><span style="font-size: 60pt; color: #FFFFFF;"><i class="fas fa-file-csv"></i></span><br>CSV</button>
                    </center>

                </div>
            </div>
        ';
    }
    else
    {
        if(CheckPermission("addOOEBVRLJgnd")) echo AddButton("/ooebv-ranglisten/neue-liste",false,false,"Liste hinzuf&uuml;gen");


        $lists = MySQL::Cluster("SELECT * FROM ooebvrl_lists");
        foreach($lists AS $list)
        {
            echo '<br><br><h4>'.$list['listName'].' <sub>'.$list['listFootnote'].'</sub>'.(CheckPermission("editOOEBVRLJgnd") ? EditButton("/ooebv-ranglisten/".$list['listFilename']."/bearbeiten",true) : '').' '.(CheckPermission("deleteOOEBVRLJgnd") ? DeleteButton("OOEBVRLJgnd","ooebvrl_lists",$list['id'],true) : '').'</h4>';

            if(CheckPermission("addOOEBVRLJgnd")) echo AddButton("/ooebv-ranglisten/".$list['listFilename']."/neue-tabelle",false,false,"Tabelle hinzuf&uuml;gen").'<br><br>';

            $tables = MySQL::Cluster("SELECT * FROM ooebvrl_tables WHERE listID = ?",'s',$list['id']);
            foreach($tables AS $table)
            {
                echo '<a href="ooebv-ranglisten/'.$list['listFilename'].'/'.$table['tableFilename'].'">'.$table['tableName'].' '.($table['showLastEdit']==1 ? ('(Stand: '.date_format(date_create($table['lastEdit']),"d.m.Y").')') : '').'</a>'.(CheckPermission("editOOEBVRLJgnd") ? EditButton("/ooebv-ranglisten/".$list['listFilename']."/".$table['tableFilename']."/bearbeiten",true) : '').' '.(CheckPermission("deleteOOEBVRLJgnd") ? DeleteButton("OOEBVRLJgnd","ooebvrl_tables",$table['id'],true) : '').'<br>';
            }
        }
    }


    /*
    echo '
        <p>
           '.PageContent('1',CheckPermission("ChangeContent")).'
        </p>
    ';
    */

    include("footer.php");
?>