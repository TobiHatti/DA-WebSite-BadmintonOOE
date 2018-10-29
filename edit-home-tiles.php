<?php
    require("header.php");

    if(isset($_POST['editLinks']))
    {
        $pageContent = $_POST['contentLinks'];
        $page = 'index';
        $pidx = 3;

        if(!MySQLExists("SELECT id FROM page_content WHERE page = '$page' AND paragraph_index = '$pidx'"))
        {
            MySQLNonQuery("INSERT INTO page_content (id, page, paragraph_index) VALUES ('','$page','$pidx')");
        }

        MySQLNonQuery("UPDATE page_content SET text = '$pageContent' WHERE page = '$page' AND paragraph_index = '$pidx'");

        Redirect("/");
        die();
    }

    if(isset($_POST['editMeisterschaft']))
    {
        $pageContent = $_POST['contentLinks'];
        $page = 'index';
        $pidx = 1;

        if(!MySQLExists("SELECT id FROM page_content WHERE page = '$page' AND paragraph_index = '$pidx'"))
        {
            MySQLNonQuery("INSERT INTO page_content (id, page, paragraph_index) VALUES ('','$page','$pidx')");
        }

        MySQLNonQuery("UPDATE page_content SET text = '$pageContent' WHERE page = '$page' AND paragraph_index = '$pidx'");

        Redirect("/");
        die();
    }

    if(isset($_POST['addLineMeisterschaft']))
    {
        $id = uniqid();
        $text = $_POST['text'];
        $value = $_POST['value'];

        MySQLNonQuery("INSERT INTO home_tiles (id,tile,text,value) VALUES ('$id','Meisterschaft','$text','$value')");

        if($value=="")FileUpload("/files/ranglisten/","uploadFile","","","UPDATE home_tiles SET value = '/files/ranglisten/FNAME' WHERE id = '$id'");

        Redirect(ThisPage());
        die();
    }

    if(isset($_POST['addLineRanglisten']))
    {
        $id = uniqid();
        $text = $_POST['text'];
        $value = $_POST['value'];

        MySQLNonQuery("INSERT INTO home_tiles (id,tile,text,value) VALUES ('$id','Ranglisten','$text','$value')");

        if($value=="")FileUpload("/files/ranglisten/","uploadFile","","","UPDATE home_tiles SET value = '/files/ranglisten/FNAME' WHERE id = '$id'");

        Redirect(ThisPage());
        die();
    }

    if(isset($_POST['editUpdate']))
    {
        $text = $_POST['editText'];
        $value = $_POST['editValue'];
        $id = $_POST['editUpdate'];

        MySQLNonQuery("UPDATE home_tiles SET text = '$text', value = '$value' WHERE id = '$id'");
        Redirect("/home/Ranglisten/bearbeiten");
        die();
    }


    if(CheckPermission("ChangeContent"))
    {


        if(isset($_GET['tile']) AND $_GET['tile'] == 'Meisterschaft')
        {
            echo '<h2 class="stagfade1">Startseite &#9654; Meisterschaft &#9654; Bearbeiten</h2>';

            echo '<ul>';
            $strSQL = "SELECT * FROM home_tiles WHERE tile = 'Meisterschaft' ORDER BY id ASC";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs))
            {
                if(isset($_GET['editLine']) ANd $_GET['editLine']==$row['id'])
                {
                    echo '
                        <li>
                            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                <input type="text" name="editText" value="'.$row['text'].'" placeholder="Anzeigename..."/>
                                <input type="url"  name="editValue" value="'.$row['value'].'" placeholder="http://..."/>
                                <button type="submit" name="editUpdate" value="'.$row['id'].'">Aktualisieren</button>
                            </form>
                        </li>
                    ';
                }
                else echo '<li><a href="'.$row['value'].'" target="_blank">'.$row['text'].'</a>&nbsp;&nbsp;'.EditButton("/home/Ranglisten/bearbeiten?editLine=".$row['id'],true).'  '.DeleteButton("CC","home_tiles",$row['id'],true).'</li>';
            }
            echo '</ul><br><br>';



            echo '
                <a href="#addLine"><button type="button">Zeile hinzuf&uuml;gen</button></a>

                <div class="modal_wrapper" id="addLine">
                    <a href="#c">
                        <div class="modal_bg"></div>
                    </a>
                    <div class="modal_container" style="width: 400px; height: 250px;">
                        <center>
                            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                <table style="border-spacing: 10px;">
                                    <tr>
                                        <td class="ta_r">Bezeignung:<br><br> </td>
                                        <td><input type="text" placeholder="Anzeigename..." name="text"/><br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="ta_r">Link/Datei: </td>
                                        <td><input type="url" placeholder="http://..." name="value"/><br>oder<br>'.FileButton("uploadFile","uploadFile").'</td>
                                    </tr>
                                </table>
                                <br><br>
                                <button type="submit" name="addLineMeisterschaft">Hinzuf&uuml;gen</button>
                            </form>
                        </center>
                    </div>
                </div>
                <br><br>
            ';
            echo '<form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">';
            echo TextareaPlus("contentLinks","contentLinks",MySQLSkalar("SELECT text AS x FROM page_content WHERE page = 'index' AND paragraph_index = '1'"));
            echo '<br><button type="submit" name="editMeisterschaft">&Auml;nderungen speichern</button> <button type="button" onclick="window.history.back();">&Auml;nderungen verwerfen</button></form>';
        }

        if(isset($_GET['tile']) AND $_GET['tile'] == 'Ranglisten')
        {
            echo '<h2 class="stagfade1">Startseite &#9654; Ranglisten &#9654; Bearbeiten</h2>';

            echo '<ul>';
            $strSQL = "SELECT * FROM home_tiles WHERE tile = 'Ranglisten' ORDER BY id ASC";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs))
            {
                if(isset($_GET['editLine']) ANd $_GET['editLine']==$row['id'])
                {
                    echo '
                        <li>
                            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                <input type="text" name="editText" value="'.$row['text'].'" placeholder="Anzeigename..."/>
                                <input type="url"  name="editValue" value="'.$row['value'].'" placeholder="http://..."/>
                                <button type="submit" name="editUpdate" value="'.$row['id'].'">Aktualisieren</button>
                            </form>
                        </li>
                    ';
                }
                else echo '<li><a href="'.$row['value'].'" target="_blank">'.$row['text'].'</a>&nbsp;&nbsp;'.EditButton("/home/Ranglisten/bearbeiten?editLine=".$row['id'],true).'  '.DeleteButton("CC","home_tiles",$row['id'],true).'</li>';
            }
            echo '</ul>';

            echo '
                <br><br>
                <a href="#addLine"><button type="button">Zeile hinzuf&uuml;gen</button></a>

                <div class="modal_wrapper" id="addLine">
                    <a href="#c">
                        <div class="modal_bg"></div>
                    </a>
                    <div class="modal_container" style="width: 400px; height: 250px;">
                        <center>
                            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                <table style="border-spacing: 10px;">
                                    <tr>
                                        <td class="ta_r">Bezeignung:<br><br> </td>
                                        <td><input type="text" placeholder="Anzeigename..." name="text"/><br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="ta_r">Link/Datei: </td>
                                        <td><input type="url" placeholder="http://..." name="value"/><br>oder<br>'.FileButton("uploadFile","uploadFile").'</td>
                                    </tr>
                                </table>
                                <br><br>
                                <button type="submit" name="addLineRanglisten">Hinzuf&uuml;gen</button>
                            </form>
                        </center>
                    </div>
                </div>
            ';
        }

        if(isset($_GET['tile']) AND $_GET['tile'] == 'Links')
        {
            echo '<h2 class="stagfade1">Startseite &#9654; Links &#9654; Bearbeiten</h2>';

            echo '<form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">';

            echo TextareaPlus("contentLinks","contentLinks",MySQLSkalar("SELECT text AS x FROM page_content WHERE page = 'index' AND paragraph_index = '3'"));

            echo '<br><button type="submit" name="editLinks">&Auml;nderungen speichern</button> <button type="button" onclick="window.history.back();">&Auml;nderungen verwerfen</button></form>';
        }

        echo '</form>';
    }


    include("footer.php");
?>