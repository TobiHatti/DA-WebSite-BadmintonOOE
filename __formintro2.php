<?php
    require("header.php");

    echo '<h1 class="stagfade1">PHP-Form Intro</h1>';
    echo '<h3 class="stagfade2">2) Werte aus Datenbank holen</h2><br><br>';


    // Werte aus Datenbank holen ist immer das gleiche:

    $strSQL = "SELECT * FROM news";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        // Hier werden nacheinander alle Datens�tze ausgegeben, die der
        // SQL-Abfrage entsprechen.
        // mit $row['spaltenname'] kann der Wert einer zeile ausgegeben werden:

        echo 'Titel: '.$row['title'].'<br><br>';

    }

    // Die ersten 3 Zeilen k�nnen so am schnellsten eingef�gt werden:
    // [s] [q] [l] [Enter] [r] [s] [Enter] [r] [o] [w] [Enter]



    require("footer.php");
?>