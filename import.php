<?php
    require("header.php");
    PageTitle("Import-Test");

     if(isset($_POST['start_import']))
     {
       $row = 1;
       if (($handle = fopen("test.csv", "r")) !== FALSE) {
           while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
               $num = count($data);
               echo "<p> $num fields in line $row: <br /></p>\n";
               $row++;
               for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }
    }


    echo'
        <h2 class="stagfade1">CSV-Datei importieren</h2>
        <hr>
        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data" class="stagfade2">

        <button type="submit" name="start_import" class="stagfade3">Importiere CSV</button>
        </form>


    ';

    include("footer.php");





?>
