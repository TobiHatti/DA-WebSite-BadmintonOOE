<?php
    require("header.php");
    PageTitle("Trainingsgruppen");

    if(isset($_POST['addNWTG']))
    {
        $id = uniqid();
        $title = $_POST['title'];
        $last_edit = date("Y-m-d");
        $showLastEdit = isset($_POST['showLastEdit']) ? 1 : 0;

        MySQL::NonQuery("INSERT INTO nw_trainingsgruppen (id,title,show_last_edit,last_edit) VALUES (?,?,?,?)",'@s',$id,$title,$showLastEdit,$last_edit);
        FileUpload("content/trainingsgruppen/","uploadFile","","","UPDATE nw_trainingsgruppen SET file = 'FNAME' WHERE id = '$id'");

        Redirect("/trainingsgruppen");
        die();
    }

    if(isset($_POST['updateNWTG']))
    {
        $id = $_POST['updateNWOverview'];
        $title = $_POST['title'];
        $last_edit = date("Y-m-d");
        $showLastEdit = isset($_POST['showLastEdit']) ? 1 : 0;

        MySQL::NonQuery("UPDATE nw_trainingsgruppen SET title = ?, show_last_edit = ?, last_edit = ? WHERE id = ?",'@s',$title,$showLastEdit,$last_edit,$id);
        FileUpload("content/trainingsgruppen/","uploadFile","","","UPDATE nw_trainingsgruppen SET file = 'FNAME' WHERE id = '$id'");

        Redirect("/trainingsgruppen");
        die();
    }

    echo '<h1 class="stagfade1">Trainingsgruppen</h1>';



    if(CheckPermission("AddNWTG") AND isset($_GET['new']))
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
                                <button type="submit" name="addNWTG">Hinzuf&uuml;gen</button>
                            </center>
                        </td>
                    </tr>
                </table>
            </form>
        ';
    }
    else if(CheckPermission("EditNWTG") AND isset($_GET['edit']))
    {
        $section = MySQL::Row("SELECT * FROM nw_trainingsgruppen WHERE id = ?",'s',$_GET['edit']);

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
                                <button type="submit" name="updateNWTG" value="'.$section['id'].'">Aktualisieren</button>
                            </center>
                        </td>
                    </tr>
                </table>
            </form>
        ';
    }
    else
    {
        if(CheckPermission("AddNWTG")) echo AddButton("/trainingsgruppen/neu");

        $sections = MySQL::Cluster("SELECT * FROM nw_trainingsgruppen");

        foreach($sections as $sect)
        {
            echo '

                <h3><sub>Gruppe </sub>'.$sect['title'].' &nbsp;&nbsp;<sub>'.(($sect['show_last_edit']==1) ? ('(Stand: '.date_format(date_create($sect['last_edit']),"d. m. Y").')') : '').'&nbsp;&nbsp;&nbsp;'.(CheckPermission("EditNWTG") ? EditButton("trainingsgruppen/".$sect['id']."/bearbeiten",true) : '').' '.(CheckPermission("DeleteNWTG") ? DeleteButton("NWTG","nw_trainingsgruppen",$sect['id'],true) : '').'</sub></h3>
                <hr>
                <iframe src="/content/trainingsgruppen/'.$sect['file'].'" frameborder="0" style="width: 100%; height: 300px;"></iframe>
                <a href="/content/trainingsgruppen/'.$sect['file'].'">Vollbild-Ansicht</a>
            ';
        }
    }


    include("footer.php");
?>