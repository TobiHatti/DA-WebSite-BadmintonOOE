<?php
    require("header.php");
    PageTitle("Monats-Uebersicht");

    if(isset($_POST['addNWOverview']))
    {
        $id = uniqid();
        $title = $_POST['title'];
        $last_edit = date("Y-m-d");
        $showLastEdit = isset($_POST['showLastEdit']) ? 1 : 0;

        MySQL::NonQuery("INSERT INTO nw_month_overview (id,title,show_last_edit,last_edit) VALUES (?,?,?,?)",'@s',$id,$title,$showLastEdit,$last_edit);
        FileUpload("content/nachwuchs/","uploadFile","","","UPDATE nw_month_overview SET file = 'FNAME' WHERE id = '$id'");

        Redirect("/monatsuebersicht");
        die();
    }

    if(isset($_POST['updateNWOverview']))
    {
        $id = $_POST['updateNWOverview'];
        $title = $_POST['title'];
        $last_edit = date("Y-m-d");
        $showLastEdit = isset($_POST['showLastEdit']) ? 1 : 0;

        MySQL::NonQuery("UPDATE nw_month_overview SET title = ?, show_last_edit = ?, last_edit = ? WHERE id = ?",'@s',$title,$showLastEdit,$last_edit,$id);
        FileUpload("content/nachwuchs/","uploadFile","","","UPDATE nw_month_overview SET file = 'FNAME' WHERE id = '$id'");

        Redirect("/monatsuebersicht");
        die();
    }

    if(isset($_POST['updateRowing']))
    {
        $reihungComboMOV = $_POST['sortOutputMOV'];

        foreach(explode('||',$reihungComboMOV) as $rp)
        {
            $rp = explode('##',$rp);
            if(!isset($rp[1])) continue;

            MySQL::NonQuery("UPDATE nw_month_overview SET position = ? WHERE id = ?",'@s',$rp[0],$rp[1]);
        }

        Redirect("/monatsuebersicht");
        die();

    }

    echo '<h1 class="stagfade1">Monats-&Uuml;bersicht</h1>';



    if(CheckPermission("AddDate") AND isset($_GET['new']))
    {
        echo '<h3>Neuen Eintrag erstellen</h3>';

        echo '
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <br><br>
                <table>
                    <tr>
                        <td>Abschnitts-Titel:</td>
                        <td><input type="text" placeholder="Abschnitts-Title..." name="title"/></td>
                    </tr>
                    <tr>
                        <td>Letzte &auml;nderung<br>anzeigen:</td>
                        <td>'.Tickbox("showLastEdit","showLastEdit","Datum anzeigen",true).'</td>
                    </tr>
                    <tr>
                        <td>Datei (PDF):</td>
                        <td>'.FileButton("uploadFile","uploadFile").'</td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <br><br>
                            <center>
                                <button type="submit" name="addNWOverview">Hinzuf&uuml;gen</button>
                            </center>
                        </td>
                    </tr>
                </table>
            </form>
        ';
    }
    else if(CheckPermission("AddDate") AND isset($_GET['edit']))
    {
        $section = MySQL::Row("SELECT * FROM nw_month_overview WHERE id = ?",'s',$_GET['edit']);

        echo '<h3>Eintrag bearbeiten</h3>';

        echo '
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <br><br>
                <table>
                    <tr>
                        <td>Abschnitts-Titel:</td>
                        <td><input type="text" placeholder="Abschnitts-Title..." name="title" value="'.$section['title'].'"/></td>
                    </tr>
                    <tr>
                        <td>Letzte &auml;nderung<br>anzeigen:</td>
                        <td>'.Tickbox("showLastEdit","showLastEdit","Datum anzeigen",($section['show_last_edit']==1 ? true : false)).'</td>
                    </tr>
                    <tr>
                        <td>Datei (PDF):</td>
                        <td>'.FileButton("uploadFile","uploadFile").'</td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <br><br>
                            <center>
                                <button type="submit" name="updateNWOverview" value="'.$section['id'].'">Aktualisieren</button>
                            </center>
                        </td>
                    </tr>
                </table>
            </form>
        ';
    }
    else if(CheckPermission("AddDate") AND isset($_GET['reihung']))
    {
        echo '<h3>Reihenfolge bearbeiten</h3>';

        echo '
            Mit der Maus anglicken und verschieben (Drag\'n\'drop)<br>

            <script>
                $( function() {
                    $( "#sortListMOV" ).sortable();
                    $( "#sortListMOV" ).disableSelection();
                } );

                window.setInterval(function(){
                    CheckSortableListStateRev2("sortListMOV","outputMOV");
                }, 100);

            </script>
        ';


        $tgDataCount = MySQL::Count("SELECT * FROM nw_month_overview ORDER BY position ASC");
        echo '<ul class="dragSortList_posNumbers">';
        for($i=1 ; $i <= $tgDataCount ; $i++) echo '<li>'.$i.'</li>';
        echo '</ul>';

        $tgData = MySQL::Cluster("SELECT * FROM nw_month_overview ORDER BY position ASC");

        $i=1;
        echo '<ul class="dragSortList_values" id="sortListMOV">';
        foreach($tgData as $memberData) echo '<li type="text" data-memberID="'.$memberData['id'].'">'.$memberData['title'].'</b></li>';
        echo '</ul>

        ';

        echo '
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <input type="hidden" name="sortOutputMOV" id="outputMOV"/>
                <br><br>
                <button type="submit" name="updateRowing" value="">Reihenfolge speichern</button>
            </form>
            <br><br>
        ';
    }
    else
    {
        if(CheckPermission("AddDate"))
        {
           echo AddButton("/monatsuebersicht/neu");
           echo '<br>';
           echo EditButton("monatsuebersicht/reihung",false,false,"Reihenfolge bearbeiten");
        }


        $sections = MySQL::Cluster("SELECT * FROM nw_month_overview ORDER BY position ASC");

        foreach($sections as $sect)
        {
            echo '

                <h3>'.$sect['title'].' &nbsp;&nbsp;<sub>'.(($sect['show_last_edit']==1) ? ('Stand: '.date_format(date_create($sect['last_edit']),"d. m. Y")) : '').'&nbsp;&nbsp;&nbsp;'.(CheckPermission("EditDate") ? EditButton("monatsuebersicht/".$sect['id']."/bearbeiten",true) : '').' '.(CheckPermission("DeleteDate") ? DeleteButton("Date","nw_month_overview",$sect['id'],true) : '').'</sub></h3>
                <hr>
                <iframe src="/content/nachwuchs/'.$sect['file'].'" frameborder="0" style="width: 100%; height: 300px;"></iframe>
                <a href="/content/nachwuchs/'.$sect['file'].'">Vollbild-Ansicht</a>
            ';
        }
    }


    include("footer.php");
?>