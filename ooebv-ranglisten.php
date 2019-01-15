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
                            <td><textarea name="name" placeholder="Sektions-Info..."></textarea></td>
                        </tr>
                        <tr>
                            <td>Sektions-Info<br>Rechts:</td>
                            <td><textarea name="name" placeholder="Sektions-Info..."></textarea></td>
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

        if(isset($_GET['editTableData']))
        {
            $list = $_GET['list'];
            $table = $_GET['table'];
        }
    }
    else if(isset($_GET['show']))
    {
        if(CheckPermission("addOOEBVRLJgnd")) echo AddButton("/ooebv-ranglisten/".$_GET['list']."/".$_GET['table']."/neue-sektion",false,false,"Sektion hinzuf&uuml;gen");
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