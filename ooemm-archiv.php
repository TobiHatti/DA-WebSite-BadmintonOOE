<?php
    require("header.php");
    PageTitle("O&Ouml;MM-Archiv");

    echo '<h1 class="stagfade1">O&Ouml;MM-Archiv</h1>';
    echo '<h2 class="stagfade2">'.str_replace('-','/',$_GET['jahr']).'</h2>';

    echo '<div class="archiveTables">';

    $year = str_replace('-','/',$_GET['jahr']);
    $strSQL = "SELECT DISTINCT table_name FROM ooemm_archive WHERE year = '$year'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <table>
                <tr>
                    <th colspan=9>'.$row['table_name'].'</th>
                </tr>
                <tr>
                    <td>Pl</td>
                    <td>Verein</td>
                    <td>Rd</td>
                    <td>S</td>
                    <td>U</td>
                    <td>N</td>
                    <td>Spiele</td>
                    <td>S&auml;tze</td>
                    <td>Pkt</td>
                </tr>
        ';

        $table_name = $row['table_name'];
        $strSQLc = "SELECT * FROM ooemm_archive WHERE year = '$year' AND table_name = '$table_name' ORDER BY c_pl ASC";
        $rsc=mysqli_query($link,$strSQLc);
        while($rowc=mysqli_fetch_assoc($rsc))
        {
            echo '
                <tr>
                    <td>'.$rowc['c_pl'].'</td>
                    <td>'.$rowc['c_verein'].'</td>
                    <td>'.$rowc['c_rd'].'</td>
                    <td>'.$rowc['c_s'].'</td>
                    <td>'.$rowc['c_u'].'</td>
                    <td>'.$rowc['c_n'].'</td>
                    <td>'.$rowc['c_spiele'].'</td>
                    <td>'.$rowc['c_satze'].'</td>
                    <td>'.$rowc['c_pkt'].'</td>
                </tr>
            ';
        }

        echo '</table>';
    }

    echo '</div>';


    include("footer.php");
?>