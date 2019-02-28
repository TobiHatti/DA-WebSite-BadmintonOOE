<?php
    require("header.php");

    if(isset($_POST['uploadChronik']))
    {
        if(!MySQL::Exist("SELECT * FROM settings WHERE setting = 'currentChronik'")) MySQL::NonQuery("INSERT INTO settings (setting) VALUES ('currentChronik')");

        FileUpload("/files/chronik/","chronik","","","UPDATE settings SET value = 'FNAME' WHERE setting = 'currentChronik'");

        Redirect(ThisPage());
    }

    echo '
        <h1 class="stagfade1">Chronik</h1>

        '.PageContent('1',CheckPermission("ChangeContent")).'

    ';

    if(CheckPermission("ChangeContent"))
    {
        echo '
            <br><br>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>Datei für Chronik hochladen: </td>
                    </tr>
                    <tr>
                        <td>'.FileButton("chronik","chronik").'</td>
                    </tr>
                    <tr>
                        <td>
                            <button type="submit" name="uploadChronik">Datei hochladen</button>
                        </td>
                    </tr>
                </table>
            </form>
        ';
    }

    echo '
        <br>
        <iframe src="/files/chronik/'.Setting::Get("currentChronik").'" frameborder="0" style="width:100%; height: 800px;"></iframe>
        <a href="/files/chronik/'.Setting::Get("currentChronik").'">Klicken f&uuml;r Vollbild</a>
    ';

    include("footer.php");

?>