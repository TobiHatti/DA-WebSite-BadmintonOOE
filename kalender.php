<?php

require("header.php");

    if(isset($_POST['add_termin']))
    {
        $terminName = $_POST['termin_titel'];
        $description = $_POST['description_date'];
        $termin_date=$_POST['date_termin'];
        $termin_place=$_POST['place'];
        $termin_time=$_POST['time'];
        $termin_kategorie=$_POST['kategorie'];

        MySQLNonQuery("INSERT INTO agenda (id, titel, description, date, place, time,kategorie) VALUES ('','$terminName','$description','$termin_date','$termin_place','$termin_time','$termin_kategorie')");

        Redirect(ThisPage());
        die();

    }


    if(isset($_GET['neu']))
    {
        echo'
        <h1 class="stagfade1">Neuer Termin</h1>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <br>
                <input type="text" class="cel_l" placeholder="Titel" name="termin_titel" required/>
                <br>
                <textarea class="cel_l" name="description_date" placeholder="Beschreibung" style="resize: vertical;"></textarea>
                <br>
                <input type="date" class="cel_l" name="date_termin" required/>
                <br>
                <input type="text" class="cel_l" placeholder="Ort" name="place"/>
                <br>
                <input type="time" class="cel_l" name="time" required/>
                <br>
                <select class="cel_l" name="kategorie" id="classKat">
                    <option value="" disabled selected>--- Kategorie ausw&auml;hlen ---</option>
                    <option value="">Anderes</option>
                    <option value="Landesmeisterschaft" style="color: '.GetProperty("ColorLandesmeisterschaft").'">Landesmeisterschaft</option>
                    <option value="Doppelturnier" style="color: '.GetProperty("ColorDoppelturnier").'">Doppelturnier</option>
                    <option value="Nachwuchs" style="color: '.GetProperty("ColorNachwuchs").'">Nachwuchs</option>
                    <option value="SchuelerJugend" style="color: '.GetProperty("ColorSchuelerJugend").'">Sch&uuml;ler/Jugend</option>
                    <option value="Senioren" style="color: '.GetProperty("ColorSenioren").'">Senioren</option>
                    <option value="Training" style="color: '.GetProperty("ColorTraining").'">Training</option>
                </select>
                <br>


                <br>
                <br>
                <br>
                <button type="submit" name="add_termin" value="post-value">Termin hinzuf&uuml;gen

            </form>
        ';

    }
    else
    {
        $frameExtension = (isset($_GET['datum'])) ? ('?datum='.$_GET['datum']) : ((isset($_GET['event'])) ? ('#calenderInfo'.$_GET['event']) : '');

        echo '<div style="float:right;"><table><tr><td>Liste / Kalender</td><td>'.Togglebox("","changeListStyle",1,"ChangeCalenderStyle();").'</td></tr></table></div><br>';

        echo'
            <div id="CalenderList" style="display:none;">
            <h3>Liste der Termine</h3>
        ';

            $strSQL = "SELECT * FROM agenda";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs))
            {
                echo'
                    <div>
                       <hr>
                       <span>Titel: '.$row['titel'].' <br> Datum: '.$row['date'].' <br> Zeit: '.$row['time'].'</span>
                       <p>'.$row['description'].'</p>
                    </div>
                ';
            }

        echo '
        </div>
        <div id="CalenderGraphic" style="display:block;">
            <h3>Kalender</h3>
            <iframe src="/graphic_calendar'.$frameExtension.'" frameborder="0" onload="ResizeIframe(this);" class="calender_iframe"></iframe>
        </div>
        ';


    }

include("footer.php");

?>