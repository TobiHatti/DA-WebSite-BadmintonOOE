<?php
    session_start();

    require("headerlinks.php");

    require("data/mysql_connect.php");

    require("data/extension.lib.php");
    require("data/file.lib.php");
    require("data/mysql.lib.php");
    require("data/property.lib.php");
    require("data/string.lib.php");

    require("data/functions.php");

    if(isset($_POST['uploadFroalaFile']))
    {
        $id = uniqid();
        $displayName = $_POST['display'];

        $filename = SReplace($_FILES['file']['name'][0]);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        // Adding a "-u" in the end of the filename avoids first duplicate override
        $filenameNoExtension = str_replace('.'.$extension,'',$filename).'-u';
        $filename = $filenameNoExtension.'.'.$extension;

        // In case the ID already exists, cycle
        if(MySQL::Exist("SELECT filename FROM uploads WHERE filename = ?",'@s',$filename))
        {
            $i=2;
            do
            {
                $newFilename = $filenameNoExtension.'-'.$i;
                $newFilenamePlusExtension = $newFilename.'.'.$extension;
                $i++;
            }
            while(MySQL::Exist("SELECT filename FROM uploads WHERE filename = ?",'@s',$newFilenamePlusExtension));

            $filename = $newFilename.'.'.$extension;
            $filenameNoExtension = $newFilename;
        }

        if($displayName == '') $displayName = $filename;

        FileUpload("files/pagecontent/","file","","","INSERT INTO uploads (id,filename,displayname) VALUES ('$id','$filename','$displayName')",$filenameNoExtension);

        Redirect(ThisPage("+uploadComplete=$id"));
        die();
    }


    if(isset($_GET['uploadComplete']))
    {
        $uploadData = MySQL::Row("SELECT * FROM uploads WHERE id = ?",'s',$_GET['uploadComplete']);
        echo '
            <h4>Datei wurde hochgeladen!</h4>
            <center>
                <img src="/content/success.gif" alt="" style="width: 200px;"/>
                <br>
                <i><span style="color: #696969; font-size: 10pt;">'.$uploadData['displayname'].' ['.$uploadData['filename'].']</span></i>
                <br>
                <input type="hidden" id="htmlInsert" value="<a href=\'/files/pagecontent/'.$uploadData['filename'].'\'>'.$uploadData['displayname'].'</a>"/>

                <a href="#"><button type="button" onclick="AddStringToFroala(document.getElementById(\'htmlInsert\').value); window.location.replace(\'froalapl-upload-file?parent='.urldecode($_GET['parent']).'\'); window.top.location = \'/'.urldecode($_GET['parent']).'#\'"><i class="fas fa-paste"></i> In Textfeld einfügen</button></a>
            </center>
        ';
    }
    else if(isset($_GET['existingFiles']))
    {
        echo '
            <h4>Hochgeladene Dateien</h4>
            <ul>
        ';

        $strSQL = "SELECT * FROM uploads ORDER BY displayname ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <a href="'.ThisPage("-existingFiles","+uploadSelection=".$row['id']).'">
                    <table style="margin-bottom: 5px;" class="hoverFocus">
                        <tr>
                            <td rowspan=2>
                                <li></li>
                            </td>
                            <td>
                                <span style="font-size: 12pt;">'.$row['displayname'].'</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="color: #696969; font-size: 10pt;">['.$row['filename'].']</span>
                            </td>
                        </tr>
                    </table>
                </a>
            ';
        }

        echo '
            </ul>
        ';
    }
    else if(isset($_GET['uploadSelection']))
    {
        $uploadData = MySQL::Row("SELECT * FROM uploads WHERE id = ?",'s',$_GET['uploadSelection']);
        echo '
            <h4>Datei wurde ausgew&auml;hlt!</h4>
            <center>
                <img src="/content/success.gif" alt="" style="width: 200px;"/>
                <br>
                <i><span style="color: #696969; font-size: 10pt;">'.$uploadData['displayname'].' ['.$uploadData['filename'].']</span></i>
                <br>
                <input type="hidden" id="htmlInsert" value="<a href=\'/files/pagecontent/'.$uploadData['filename'].'\'>'.$uploadData['displayname'].'</a>"/>

                <a href="#"><button type="button" onclick="AddStringToFroala(document.getElementById(\'htmlInsert\').value); window.location.replace(\'froalapl-upload-file?parent='.urldecode($_GET['parent']).'\'); window.top.location = \'/'.urldecode($_GET['parent']).'#\'"><i class="fas fa-paste"></i> In Textfeld einfügen</button></a>
            </center>
        ';
    }
    else
    {
        echo '
            <div class="iframe_content stagfade2" style="font-size: 12pt;">
                <h4>Datei hochladen</h4>

                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <center>
                        <br>
                        <input type="text" name="display" class="cel_m" placeholder="Anzeigename (optional)"/><br>
                        <br>
                        '.FileButton("file","file",false,"","","width:140px;").'
                        <br><br>
                        <button type="submit" name="uploadFroalaFile">Hochladen</button>
                        <br>
                        oder<br>
                        <a href="'.ThisPage("+existingFiles").'">Existierende Datei auswählen</a>
                    </center>
                </form>
            </div>
        ';
    }

?>