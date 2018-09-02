<?php

require("header.php");


    if(isset($_GET['neu']))
    {
        // PHP-Form zum eintragen neuer Termine
        echo'
        <h1 class="stagfade1">Neuer Termin</h1>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <br>
                <input type="text" placeholder="Termin Titel" name="termin_titel"/>
                <br>
                <textarea name="description_date" placeholder="">Termin Beschreibung</textarea>
                <br>
                <input type="date" name="date_termin"/>
                <br>
                <input type="text" placeholder="Ort" name="place"/>
                <br>
                <input type="time" name="time"/>
                <br>
                <input type="color" name="color"/>
                <p>
                    <span style="font-size: 14pt">
                       <span style="color: #FF4500">Orange </span>&#8680; Nachwuchs
                       <br>
                       <span style="color: #008000">Gr&uuml;n</span>&#8680; Senioren
                       <br>
                       <span style="color: #800080">Lila</span>&#8680; Sch&uuml;er/Jugend
                       <br>
                       <span style="color: #FF0000">Rot</span>&#8680; Meisterschaft
                       <br>
                       <span style="color: #40E0D0">T&uuml;rkis</span>&#8680; Doppelturniere
                   </span>
                </p>

            </form>
        ';

    }
    else
    {
        // Anzeige des Normalen Terminkalenders
    }


include("footer.php");

?>