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

    if(isset($_POST['createNewTable']))
    {
        $tableName = $_POST['name'];
        $headerColor = $_POST['headerColor'];
        $tableFilename = SReplace($tableName);
        $showLastEdit = (isset($_POST['showLastEdit']) ? 1 : 0);
        $lastEdit = date("Y-m-d");

        $listID = MySQL::Scalar("SELECT id FROM ooebvrl_lists WHERE listFilename = ?",'s',$_GET['list']);

        MySQL::NonQuery("INSERT INTO ooebvrl_tables (id, listID, tableName, tableFilename, headerColor, showLastEdit, lastEdit) VALUES ('',?,?,?,?,?,?)",'ssssss',$listID,$tableName,$tableFilename,$headerColor,$showLastEdit,$lastEdit);

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

    if(isset($_POST['addPlayers']))
    {
        $sectionID = MySQL::Scalar("SELECT id FROM ooebvrl_sections WHERE sectionFilename = ?",'s',$_GET['section']);
        $rankList = $_POST['rankList'];

        $rankParts = explode('||',$rankList);

        $i = 1;
        while(isset($_POST['playerID'.$i]) AND ($_POST['playerID'.$i] != "" OR $_POST['name'.$i] != "" OR $_POST['clubID'.$i] != "" OR $_POST['jg'.$i] != "" OR $_POST['str'.$i] != "" OR $_POST['ges'.$i] != ""))
        {
            $id = uniqid();
            $playerID = $_POST['playerID'.$i];
            $name = $_POST['name'.$i];
            $clubID = $_POST['clubID'.$i];
            $jg = $_POST['jg'.$i];
            $str = $_POST['str'.$i];
            $ges = $_POST['ges'.$i];

            $rank = explode('##',$rankParts[$i-1])[1];

            // Check if member exists. If not create new member in Members Table.
            // If no Player-ID is given, create a new member with a Temp-ID
            if($playerID != "" AND MySQL::Exist("SELECT * FROM members WHERE playerID = ?",'s',$playerID))
            {
                $memberID = MySQL::Scalar("SELECT id FROM members WHERE playerID = ?",'s',$playerID);
            }
            else
            {
                $memberID = uniqid();
                $nameParts = explode(' ',$name);
                $lastname = $nameParts[count($nameParts)-1];
                $firstname = str_replace($lastname,'',$name);
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

            $i++;
        }

        Redirect('/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table']);
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
                            <td><input type="text" placeholder="Tabellen-Name..." name="name"/></td>
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
            $list = $_GET['list'];
        }

        if(isset($_GET['editTable']))
        {
            $list = $_GET['list'];
            $table = $_GET['table'];
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
            else
            {
                $playerCount = $_POST['amountPlayers'];
                $clubList = MySQL::Cluster("SELECT * FROM vereine ORDER BY ort,verein ASC");

                echo '
                    <script>
                        $( function() {
                            $( "#sortList" ).sortable();
                            $( "#sortList" ).disableSelection();
                        } );

                        window.setInterval(function(){
                            CheckSortableListState("sortList","sortListOutput");
                        }, 100);
                    </script>
                ';


                echo '
                    <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <center>
                        <textarea hidden name="rankList" id="sortListOutput" cols="30" rows="10"></textarea>
                            <div class="rlSortContainer">
                                <ul class="rlSortableListCount">
                ';
                for($i = 1; $i <= $playerCount; $i++) echo '<li><div>'.$i.'</div></li>';
                echo '
                    </ul>
                    <ul class="rlSortableListData" id="sortList">
                ';
                for($i = 1; $i <= $playerCount; $i++)
                {
                    echo '
                    <li value="'.$i.'">
                        <table>
                            <tr>
                                <td><i class="fas fa-grip-horizontal"></i></td>
                                <td><input class="cel_s" type="number" placeholder="MgNr." name="playerID'.$i.'"/></td>
                                <td><input class="" type="text" placeholder="Name" name="name'.$i.'"/></td>
                                <td>
                                    <input type="hidden" name="clubID'.$i.'" id="outClubID'.$i.'"/>
                                    <input class="cel_m" type="text" placeholder="Verein" id="outClub'.$i.'"/>
                                    <select class="cel_xs" onchange="CopyValueToElement(this,\'outClubID'.$i.'\'); CopyDisplayToElement(this,\'outClub'.$i.'\'); ResetDropdown(this)">
                                        <option value="" selected disabled>&#9660;</option>
                                        ';
                                        foreach($clubList as $club) echo '<option value="'.$club['kennzahl'].'">'.$club['verein'].' '.$club['ort'].'</option>';
                                        echo '
                                    </select>
                                </td>
                                <td><input class="cel_xs" type="number" placeholder="Jg." min="0" max="99" name="jg'.$i.'"/></td>
                                <td><input class="cel_xs" type="number" placeholder="1.Rd." name="1rd'.$i.'"/></td>
                                <td><input class="cel_xs" type="number" placeholder="2.Rd." name="2rd'.$i.'"/></td>
                                <td><input class="cel_xs" type="number" placeholder="3.Rd." name="3rd'.$i.'"/></td>
                                <td><input class="cel_xs" type="number" placeholder="Str." name="str'.$i.'"/></td>
                                <td><input class="cel_xs" type="number" placeholder="Ges." name="ges'.$i.'"/></td>
                            </tr>
                        </table>
                        </li>
                    ';
                }
                echo '
                                </ul>
                            </div>
                            <button type="submit" name="addPlayers">Spieler hinzuf&uuml;gen</button>
                        </center>
                    </form>
                ';
            }
        }
        else if(CheckPermission("addOOEBVRLJgnd")) echo AddButton('/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table'].'/'.$_GET['section'].'/spieler/hinzufuegen',false,false,"Spieler hinzuf&uuml;gen");


        echo '
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
        ';


        $players = MySQL::Cluster("SELECT * FROM members_ooebvrl INNER JOIN ooebvrl_sections ON members_ooebvrl.sectionID = ooebvrl_sections.id INNER JOIN ooebvrl_tables ON ooebvrl_sections.tableID = ooebvrl_tables.id INNER JOIN ooebvrl_lists ON ooebvrl_tables.listID = ooebvrl_lists.id WHERE sectionFilename = ? AND tableFilename = ? AND listFilename = ? ORDER BY members_ooebvrl.rank ASC",'sss',$_GET['section'],$_GET['table'],$_GET['list']);
        $i=1;
        echo '
            <div class="rlSortContainer">
                <ul class="rlSortableListCount">
        ';

        for($i = 1; $i <= count($players); $i++) echo '<li><div>'.$i.'</div></li>';
        echo '
            </ul>
            <ul class="rlSortableListData" id="sortList">
        ';

        foreach($players AS $player)
        {
            echo '
            <li>
                <table>
                    <tr>
                        <td><i class="fas fa-grip-horizontal"></i></td>
                        <td><input class="cel_s" type="number" placeholder="MgNr." name="playerID'.$i.'"/></td>
                        <td><input class="" type="text" placeholder="Name" name="name'.$i.'"/></td>
                        <td>
                            <input type="hidden" name="clubID" id="outClubID'.$i.'"/>
                            <input class="cel_m" type="text" placeholder="Verein" id="outClub'.$i.'"/>
                            <select class="cel_xs" onchange="CopyValueToElement(this,\'outClubID'.$i.'\'); CopyDisplayToElement(this,\'outClub'.$i.'\'); ResetDropdown(this)">
                                <option value="" selected disabled>&#9660;</option>
                                ';
                                foreach($clubList as $club) echo '<option value="'.$club['kennzahl'].'">'.$club['verein'].' '.$club['ort'].'</option>';
                                echo '
                            </select>
                        </td>
                        <td><input class="cel_xs" type="number" placeholder="Jg." name="jg'.$i.'"/></td>
                        <td><input class="cel_xs" type="number" placeholder="1.Rd." name="1rd'.$i.'"/></td>
                        <td><input class="cel_xs" type="number" placeholder="2.Rd." name="2rd'.$i.'"/></td>
                        <td><input class="cel_xs" type="number" placeholder="3.Rd." name="3rd'.$i.'"/></td>
                        <td><input class="cel_xs" type="number" placeholder="Str." name="str'.$i.'"/></td>
                        <td><input class="cel_xs" type="number" placeholder="Ges." name="ges'.$i.'"/></td>
                    </tr>
                </table>
                </li>
            ';
        }

        echo '
                    </ul>
                </div>
                <button type="submit">Reihung aktualisieren</button>
            </form>
        ';


    }
    else if(isset($_GET['show']))
    {
        if(CheckPermission("addOOEBVRLJgnd")) echo AddButton("/ooebv-ranglisten/".$_GET['list']."/".$_GET['table']."/neue-sektion",false,false,"Sektion hinzuf&uuml;gen");

        $listID = MySQL::Scalar("SELECT id FROM ooebvrl_lists WHERE listFilename = ?",'s',$_GET['list']);
        $tableID = MySQL::Scalar("SELECT id FROM ooebvrl_tables WHERE tableFilename = ?",'s',$_GET['table']);

        $sections = MySQL::Cluster("SELECT * FROM ooebvrl_sections INNER JOIN ooebvrl_tables ON ooebvrl_sections.tableID = ooebvrl_tables.id WHERE ooebvrl_sections.listID = ? AND ooebvrl_sections.tableID = ?",'ss',$listID,$tableID);

        echo '
            <br><br>
                <a href="#export"><button type="button">Exportieren</button></a>
        ';

        foreach($sections AS $section)
        {
            echo '
                <center>
                    <table class="ooebvRanglistenTable">
                        <tr style="background: #'.$section['headerColor'].'">
                            <td colspan=2>'.nl2br($section['sectionInfoLeft']).'</td>
                            <td colspan=4 style="font-size: 16pt; font-weight: normal">'.nl2br($section['sectionName']).'</td>
                            <td colspan=4>'.nl2br($section['sectionInfoRight']).'</td>
                        </tr>
                        <tr style="background: #'.$section['headerColor'].'">
                            <td>Rang</td>
                            <td>MgNr.</td>
                            <td>Name</td>
                            <td>Verein</td>
                            <td>Jg.</td>
                            <td>1. Rd</td>
                            <td>2. Rd</td>
                            <td>3. Rd</td>
                            <td>Str.</td>
                            <td>Ges.</td>
                        </tr>
            ';

            $players = MySQL::Cluster("SELECT * FROM members_ooebvrl INNER JOIN members ON members_ooebvrl.memberID = members.id INNER JOIN ooebvrl_sections ON members_ooebvrl.sectionID = ooebvrl_sections.id INNER JOIN ooebvrl_tables ON ooebvrl_sections.tableID = ooebvrl_tables.id INNER JOIN ooebvrl_lists ON ooebvrl_tables.listID = ooebvrl_lists.id WHERE ooebvrl_lists.listFilename = ? AND ooebvrl_tables.tableFilename = ? AND ooebvrl_sections.sectionFilename = ? ORDER BY members_ooebvrl.rank ASC",'sss',$_GET['list'],$_GET['table'],$section['sectionFilename']);
            foreach($players AS $player)
            {
                echo '
                    <tr>
                        <td>'.$player['rank'].'.</td>
                        <td>'.((!StartsWith($player['playerID'],'TMP')) ? $player['playerID'] : '').'</td>
                        <td>'.$player['firstname'].' '.$player['lastname'].'</td>
                        <td>'.$player['clubID'].'</td>
                        <td>'.date_format(date_create($player['birthdate']),"y").'</td>
                        <td>'.$player['round1'].'</td>
                        <td>'.$player['round2'].'</td>
                        <td>'.$player['round3'].'</td>
                        <td>'.$player['str'].'</td>
                        <td>'.$player['ges'].'</td>
                    </tr>
                ';
            }

            echo '
                    </table>
                </center>
            ';



            if(CheckPermission("addOOEBVRLJgnd") OR CheckPermission("addOOEBVRLJgnd")) echo AddButton('/ooebv-ranglisten/'.$_GET['list'].'/'.$_GET['table'].'/'.$section['sectionFilename'].'/spieler',false,false,"Spieler hinzuf&uuml;gen / Reihung &auml;ndern");
            echo '<br><br>';
        }




        echo '
            <div class="modal_wrapper" id="export">
                <a href="#c"><div class="modal_bg"></div></a>
                <div class="modal_container" style="width: 370px; height: 160px; overflow-y: hidden">
                    <center>
                    <button type="button"><span style="font-size: 60pt; color: #FFFFFF;"><i class="fas fa-file-pdf"></i></span><br>PDF</button>
                    <button type="button"><span style="font-size: 60pt; color: #FFFFFF;"><i class="fas fa-file-excel"></i></span><br>Excel</button>
                    <button type="button"><span style="font-size: 60pt; color: #FFFFFF;"><i class="fas fa-file-csv"></i></span><br>CSV</button>
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
            echo '<br><br><h4>'.$list['listName'].' <sub>'.$list['listFootnote'].'</sub></h4>';

            if(CheckPermission("addOOEBVRLJgnd")) echo AddButton("/ooebv-ranglisten/".$list['listFilename']."/neue-tabelle",false,false,"Tabelle hinzuf&uuml;gen").'<br><br>';

            $tables = MySQL::Cluster("SELECT * FROM ooebvrl_tables WHERE listID = ?",'s',$list['id']);
            foreach($tables AS $table)
            {
                echo '<a href="ooebv-ranglisten/'.$list['listFilename'].'/'.$table['tableFilename'].'">'.$table['tableName'].' '.($table['showLastEdit']==1 ? ('(Stand: '.date_format(date_create($table['lastEdit']),"d.m.Y").')') : '').'</a><br>';
            }
        }
    }










        if(isset($_GET['section']) AND $_GET['section']=='U11-U19-Maedchen')
        {
            echo '<h3>U11 - U19 M&auml;dchen</h3><br>';

            if(isset($_GET['edit']))
            {
                $clubList = MySQL::Cluster("SELECT * FROM vereine ORDER BY ort,verein ASC");

                echo '
                    <script>
                        $( function() {
                            $( "#sortList" ).sortable();
                            $( "#sortList" ).disableSelection();
                        } );

                        window.setInterval(function(){
                            CheckSortableListState("sortListM","outputM");
                            CheckSortableListState("sortListW","outputW");
                        }, 100);


                    </script>
                    <center>
                        <div class="rlSortContainer">
                            <ul class="rlSortableListCount">
                ';

                for($i = 1; $i <= 15; $i++) echo '<li><div>'.$i.'</div></li>';

                echo '
                    </ul>
                    <ul class="rlSortableListData" id="sortList">
                ';

                for($i = 1; $i <= 15; $i++)
                {
                    echo '
                    <li>
                        <table>
                            <tr>
                                <td><i class="fas fa-grip-horizontal"></i></td>
                                <td><input class="cel_s" type="number" placeholder="MgNr." name="playerID'.$i.'"/></td>
                                <td><input class="" type="text" placeholder="Name" name="name'.$i.'"/></td>
                                <td>
                                    <input type="hidden" name="clubID" id="outClubID'.$i.'"/>
                                    <input class="cel_m" type="text" placeholder="Verein" id="outClub'.$i.'"/>
                                    <select class="cel_xs" onchange="CopyValueToElement(this,\'outClubID'.$i.'\'); CopyDisplayToElement(this,\'outClub'.$i.'\'); ResetDropdown(this)">
                                        <option value="" selected disabled>&#9660;</option>
                                        ';
                                        foreach($clubList as $club) echo '<option value="'.$club['kennzahl'].'">'.$club['verein'].' '.$club['ort'].'</option>';
                                        echo '
                                    </select>
                                </td>
                                <td><input class="cel_xs" type="number" placeholder="Jg." name="jg'.$i.'"/></td>
                                <td><input class="cel_xs" type="number" placeholder="1.Rd." name="1rd'.$i.'"/></td>
                                <td><input class="cel_xs" type="number" placeholder="2.Rd." name="2rd'.$i.'"/></td>
                                <td><input class="cel_xs" type="number" placeholder="3.Rd." name="3rd'.$i.'"/></td>
                                <td><input class="cel_xs" type="number" placeholder="Str." name="str'.$i.'"/></td>
                                <td><input class="cel_xs" type="number" placeholder="Ges." name="ges'.$i.'"/></td>
                            </tr>
                        </table>
                        </li>
                    ';
                }

                echo '
                            </ul>
                        </div>
                        <br>
                        <button type="submit">Spieler hinzuf&uuml;gen</button>
                    </center>
                ';

            }
            else
            {
                 if(CheckPermission("EditOOEBVRLJgnd")) echo EditButton("/ooebv-ranglisten/".$_GET['section']."/edit");

                echo '
                    <br><br>
                    <center>
                        <table class="ooebvRanglistenTable">
                            <tr>
                                <td colspan=2>O&Ouml;BV<br>Rangliste 2018</td>
                                <td colspan=4 style="font-size: 16pt; font-weight: normal">Damen Einzel U11</td>
                                <td colspan=4>Stand nach 5. Runde<br>22.10.2018</td>
                            </tr>
                            <tr>
                                <td>Rang</td>
                                <td>MgNr.</td>
                                <td>Name</td>
                                <td>Verein</td>
                                <td>Jg.</td>
                                <td>1. Rd</td>
                                <td>2. Rd</td>
                                <td>3. Rd</td>
                                <td>Str.</td>
                                <td>Ges.</td>
                            </tr>
                        </table>
                    </center>
                ';
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