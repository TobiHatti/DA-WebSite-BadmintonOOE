<!DOCTYPE html>

<html>

<head>
  <title>ZIP-Datei</title>
</head>

<body>


        <?php
        echo'
            <h4>ZIP-Datei</h4>
        ';
            /*
         * Zip-Archiv erstellen und Datei herunterladen
         * zip_archiv.php (utf-8) - 07.07.2015
         * - Webbausteine.de
         */

        // Beachten Sie, das hiermit keine Verzeichnisse gelesen
        // werden können die ihrerseits Verzeichnisse enthalten!


        //Zip-Ordner erstellen

        $verzeichnis = "content/Vorstand/";
        $zip_name = "AlbumDownloa234d.zip";

        // Verzeichnis auslesen
        $dateien = array_slice(scanDir($verzeichnis), 2);

        // Neue Instanz der ZipArchive Klasse erzeugen
        $zip = new ZipArchive;

        if (!file_exists($zip_name))
        {
            // Zip-Archiv erstellen
            $status = $zip->open($zip_name, ZipArchive::CREATE);
        }
        else
        {
            // Zip-Archiv überschreiben
            $status = $zip->open($zip_name, ZipArchive::OVERWRITE);
        }

        if ($status)
        {
            // Dateien ins Zip-Archiv einfügen
            foreach ($dateien as $datei) $zip->addFile($verzeichnis . $datei, $datei);

            // Zip-Archiv schließen
            $zip->close();

            if(file_exists($zip_name))
            {
                // Dateigröße ermitteln
                $info = stat($zip_name);
                echo '<p><a href="' . $zip_name . '" download>' . $zip_name . '</a> - ' .
                number_format(round($info["size"] / 1024 ,1), 2, ",", ".") .' KB</p></center>';
            }
        }
    ?>


</body>
</html>