<?php
    require("header.php");

//========================================================================================
//========================================================================================
//========================================================================================

    // 2) START PHP-FORM-PROCESSING

    // Zweiter Teil:

    // mit isset($_POST['button-name']) wird geprüft, ob der Knopf gedrückt worden ist.
    // So können auf einer Seite mehrere Funktionen erfüllt werden, anstatt für jedes Form eine neue Seite zu erstellen
    if(isset($_POST['add_user']))
    {
        // Die Werte aus dem Textfeldern werden in einem POST-Array übergeben.
        // Um leichter mit diesen werten zu arbeiten, werden diese in Standart PHP-Variablen geschrieben:
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];


        // In Diese Funktion einfach den SQL-String mit den gerade erstellten Variablen eingeben
        MySQLNonQuery("INSERT INTO users (fname,lname) VALUES ('$firstname','$lastname')");

        // Man kann nach der SQL-Funktion noch folgendes machen, um einen Fehler/Misserfolg abzufangen und das Laden der Seite abzubrechen:
        // MySQLNonQuery("..bla...bla...bla...") or die("Ein fehler ist aufgetreten!");
        // Yep..., PHP ist makaber, es sagt einfach WORK OR DIE!!!!.....


        // Weitere Möglichkeiten sind z.B.:
        // MySQLNonQuery("DELETE FROM users WHERE x = '$x'");
        // MySQLNonQuery("UPDATE users SET firstname = '$firstname' WHERE id = '$id'");
        // usw...

        // Weitere nützliche SQL-Funktionen sind in data/basefunctions.php Deklariert.
        // Beschreibung sagt alles was sie machen


        // WICHTIG:
        // Mit Redirect(ThisPage()); wird die Seite einfach nochmal neu geladen.
        // Dies verhindert ein mehrfaches eintragen in die Datenbank
        Redirect(ThisPage());

        // Im Grunde ist das alles. Oft müssen allerdings nich die Eingegangenen Daten
        // geprüft oder Manipuliert werden. (z.B. Auf Kleinbuchstaben umwandeln, Prüfen
        // ob ein eintrag schon existiert,...). Auch hier sind wieder mehrere nützliche
        // Funktionen in data/basefunctions.php Deklariert
    }

    // 2) END PHP-FORM-PROCESSING

//========================================================================================
//========================================================================================
//========================================================================================

    echo '<h1 class="stagfade1">PHP-Form Intro</h1>';
    echo '<h3 class="stagfade2">1) Werte aus Form in Datenbank &uuml;bertragen</h2><br><br>';

    // 1) START HTML-FORM

    // Erster Teil:

    echo '
        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">

            <input type="text" name="firstname" placeholder="Vorname"/>

            <br>

            <input type="text" name="lastname" placeholder="Nachname"/>

            <br><br>

            <button type="submit" name="add_user">Nutzer Hinzuf&uuml;gen</button>



            <br><br><br>

            Info: Dieses Form wird kurz (ein paar Millisekunden) einen Fehler zur&uuml;ckgeben da die Datenbank in die etwas eingef&uuml;gt sollte nicht existiert.

        </form>
    ';

    // 1) END HTML-FORM

//========================================================================================
//========================================================================================
//========================================================================================

    require("footer.php");
?>