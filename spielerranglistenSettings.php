<?php
    require("header.php");

    if(isset($_POST['updateRanglistenSettings']))
    {
        $season = $_POST['updateRanglistenSettings'];
        $color1 = $_POST['color1'];
        $color2 = $_POST['color2'];
        $headerSubtitle = $_POST['headerSubtitle'];

        $paramColorA = "Y".$season."ColorA";
        $paramColorB = "Y".$season."ColorB";
        $paramHeaderSubtitle = "Y".$season."HeaderSubtitle";

        if(!MySQL::Exist("SELECT * FROM ranglisten_settings WHERE setting = ?",'s',$paramColorA))
        {
            MySQL::NonQuery("INSERT INTO ranglisten_settings (setting,value) VALUES (?,''),(?,''),(?,'')",'sss',$paramColorA,$paramColorB,$paramHeaderSubtitle);
        }

        MySQL::NonQuery("UPDATE ranglisten_settings SET value = ? WHERE setting = ?",'ss',$color1,$paramColorA);
        MySQL::NonQuery("UPDATE ranglisten_settings SET value = ? WHERE setting = ?",'ss',$color2,$paramColorB);
        MySQL::NonQuery("UPDATE ranglisten_settings SET value = ? WHERE setting = ?",'ss',$headerSubtitle,$paramHeaderSubtitle);

        Redirect(ThisPage());
    }




    echo '<h1>Einstellungen zu Spielerranglisten</h1><hr>';

    echo '
        Spielerrangliste ausw&auml;hlen:

        <select name="" id="" onchange="RedirectSelectBox(this,\'/spielerrangliste/einstellungen/\');">
            <option value="">--- Ausw&auml;hlen ---</option>
    ';

    for($i=intval(date("Y"));$i>=2011;$i--)
    {
        if($i == intval(date("Y")))
        {
            // Saisonwechsel mit 1. September
            if(intval(date("m"))>= 9) echo '<option '.((isset($_GET['season']) AND $_GET['season'] == ($i.'-'.($i+1))) ? 'selected' : '' ).' value="'.$i.'-'.($i+1).'">Spielerrangliste '.$i.'-'.($i+1).'</option>';
        }
        else echo '<option '.((isset($_GET['season']) AND $_GET['season'] == ($i.'-'.($i+1))) ? 'selected' : '' ).' value="'.$i.'-'.($i+1).'">Spielerrangliste '.$i.'-'.($i+1).'</option>';
    }
    echo '</select>';

    if(isset($_GET['season']))
    {
        echo '
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <br><br>
                <h3>Farbschema w&auml;hlen</h3>
                <hr>
                <table>
                    <tr>
                        <td>Hauptfarbe: </td>
                        <td>'.ColorPicker("color1","color1","Farbe w&auml;hlen",MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = 'Y".$_GET['season']."ColorA'")).'</td>
                    </tr>
                    <tr>
                        <td>Zweitfarbe: </td>
                        <td>'.ColorPicker("color2","color2","Farbe w&auml;hlen",MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = 'Y".$_GET['season']."ColorB'")).'</td>
                    </tr>
                </table>



                <br><br>
                <h3>Kopfzeile - Untertitel</h3>
                <hr>
                <input type="text" name="headerSubtitle" class="cel_50" placeholder="z.B.: 6. - 10. Meisterschaftsrunde - R&uuml;ckrunde" value="'.MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = 'Y".$_GET['season']."HeaderSubtitle'").'"/>

                <br><br><br>
                <center>
                    <button type="submit" value="'.$_GET['season'].'" name="updateRanglistenSettings">Ranglisteneinstellungen &uuml;bernehmen</button>
                </center>
            </form>
        ';
    }
    else echo '<br><br><span style="color: #CC0000"><i>Keine Rangliste ausgew&auml;hlt</i></span>';

    include("footer.php");
?>