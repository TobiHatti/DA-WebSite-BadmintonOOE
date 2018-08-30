<?php
    require("header.php");
    PageTitle("Vereine");

    echo '<h1 class="stagfade1">Vereine</h1>';

    echo '<br>Alphabetisch Sortiert:';
    $strSQL = "SELECT DISTINCT LEFT(ort , 1) AS letter FROM vereine ORDER BY ort ASC";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs)) echo ' | <a href="#'.$row['letter'].'">'.$row['letter'].'</a>';
    echo ' |<br><br>';


    $strSQL = "SELECT DISTINCT LEFT(ort , 1) AS letter FROM vereine ORDER BY ort ASC";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        $letter = $row['letter'];

        echo '<a name="'.$letter.'"></a>';

        $strSQLo = "SELECT * FROM vereine WHERE ort LIKE '$letter%'";
        $rso=mysqli_query($link,$strSQLo);
        while($rowo=mysqli_fetch_assoc($rso))
        {
            echo '
                <div>
                    <h4 style="margin: 4px;">
                    <a href="#alkhoven" name="alkhoven">'.$rowo['verein'].' '.$rowo['ort'].'</a>
                    </h4>
                    Kennzahl: '.$rowo['kennzahl'].'
                    <br>
                    Dachverband: '.$rowo['dachverband'].'
                    <br>
                    <br>
                    <b>'.$rowo['contact_name'].'</b>
                    <br>
                    '.$rowo['contact_street'].'
                    <br>
                    '.$rowo['contact_city'].'
                    <br>
                    Internet: <a href="'.$rowo['website'].'">'.str_replace('http://','',str_replace('https://','',$rowo['website'])).'</a>
                    <br>
                    E-mail: <a href="mailto:'.$rowo['contact_email'].'">'.$rowo['contact_email'].'</a>
                    <br>
                    '.(($rowo['contact_phone1']!='') ? ($rowo['contact_phoneLabel1'].' '.$rowo['contact_phone1'].'<br>') : '' ).'
                    '.(($rowo['contact_phone2']!='') ? ($rowo['contact_phoneLabel2'].' '.$rowo['contact_phone2'].'<br>') : '' ).'
                    '.(($rowo['contact_phone3']!='') ? ($rowo['contact_phoneLabel3'].' '.$rowo['contact_phone3'].'<br>') : '' ).'
                </div>
                <br><br>
            ';
        }
    }

    include("footer.php");
?>