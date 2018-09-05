<?php

require("header.php");

    if(isset($_POST['add_termin']))
    {
      $terminName = $_POST['termin_titel'];
      $description = $_POST['description_date'];
      $termin_date=$_POST['date_termin'];
      $termin_place=$_POST['place'];
      $termin_time=$_POST['time'];
      $termin_color=$_POST['color'];

      MySQLNonQuery("INSERT INTO agenda (id, titel, description, date, place, time, color) VALUES ('','$terminName','$description','$termin_date','$termin_place','$termin_time','$termin_color')");

    }


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
                <br>
                <button type="submit" name="add_termin" value="post-value">Termin hinzuf&uuml;gen

            </form>
        ';

    }
    else
    {
        // Anzeige des Normalen Terminkalenders
        echo'<h4>Liste der Termine</h4>';
        $strSQL = "SELECT DISTINCT * FROM agenda";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo'

            <div>
               <hr>
               <span>Titel: '.$row['titel'].' <br> Datum: '.$row['date'].' <br> Zeit: '.$row['time'].'</span>
               <p>'.$row['description'].'</p>
               <hr>

            </div>




            ';
        }
    }


include("footer.php");

?>