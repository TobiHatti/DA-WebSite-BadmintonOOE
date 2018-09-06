
<head>
  <title>PHP</title>
</head>

<body>

<?php
        include("data/mysql_connect.php");

        //Alle Termine ausgeben(Speichern)
        $inhalt="Subject;Start Date;Start Time;End Date;End Time;All Day Event;Description;Location;Private\r\n";

        $strSQL = "SELECT * FROM agenda";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {

            $inhalt.=$row['titel'].';'.$row['date'].';'.$row['time'].';;;FALSE;'.$row['description'].';'.$row['place'].';FALSE'."\r\n";
        }
        $handle = fopen ("allagendas.csv", 'w');
        fwrite ($handle, $inhalt);
        fclose ($handle);

        //Selected Termine ausgeben(Speichern)
        $id="1";
        $inhalt="Subject;Start Date;Start Time;End Date;End Time;All Day Event;Description;Location;Private\r\n";

        $strSQL = "SELECT * FROM agenda WHERE id=$id";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {

            $inhalt.=$row['titel'].';'.$row['date'].';'.$row['time'].';;;FALSE;'.$row['description'].';'.$row['place'].';FALSE'."\r\n";
        }
        $handle = fopen ("selected_id.csv", 'w');
        fwrite ($handle, $inhalt);
        fclose ($handle);
?>

</body>
