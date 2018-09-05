<?php
    require("header.php");
    PageTitle("O\u00d6MM-Archiv");

    echo '<h1 class="stagfade1">O&Ouml;MM-Archiv</h1>';

    if(isset($_GET['jahr']))
    {
        echo '<h2 class="stagfade2">'.str_replace('-','/',$_GET['jahr']).'</h2>';

        echo '<div class="archiveTables">';

        $year = str_replace('-','/',$_GET['jahr']);
        $strSQL = "SELECT DISTINCT table_name FROM ooemm_archive WHERE year = '$year'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <a name="'.$row['table_name'].'"></a>
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
    }
    else
    {
        echo '
            <h4>Saison 2010-2011</h4>
            <a target="_blank" href="http://obv.tournamentsoftware.com/sport/event.aspx?id=7D72D424-BCF5-4403-8B37-A011889CAEAF&event=1">&emsp;O&Ouml;MM - 2010-2011 - Tournamentsoftware</a>
            <br>
            <a target="_blank" href="http://www.badminton-ooe.at/wp-content/uploads/2011/04/O%C3%96MM-SpielerranglisteR%C3%BCckrunde1.pdf">&emsp;Spielerrangliste 2010/2011 (06. 04. 2011, 88 KB)</a>
            <br>
            <a target="_blank" href="http://www.badminton-ooe.at/wp-content/uploads/2011/02/Mannschaftsf%C3%BChrer-2010-2011.pdf">&emsp;Mannschaftssprecher 2010/2011 (31. 01. 2011, 17 KB)</a>
            <br>
            <a target="_blank" href="http://www.badminton-ooe.at/wp-content/uploads/2011/05/Zentralausschreibung-2010-2011.pdf">&emsp;Zentralausschreibung 2010-2011 als PDF (02. 05. 2011, 156 KB)</a>
            <br>
            <a target="_blank" href="http://www.badminton-ooe.at/wp-content/uploads/2010/12/O%C3%96MM-Spielerrangliste-27122010-SCH-JGD.pdf">&emsp;Spielerrangliste SCH JGD 2010/2011 (27. 12. 2010, 88 KB)</a>
            <br>
            <a target="_blank" href="\content\Word-Data\downloads\Zentralausschreibung-2010-2011.doc" download">&emsp;Zentralausschreibung 2010-2011 als DOC (12. 7. 2010, 897 KB)</a>
            <br>
            <a target="_blank" href="\content\Excel-Data\downloads\Klassenteilung-2010-2011.xls" download">&emsp;O&Ouml;MM Klasseneinteilung (4. 7. 2010, 57 KB)</a>
            <br>
            <a target="_blank" href="\content\Excel-Data\downloads\Auslosung-OÖMM-2010-2011.xls" download">&emsp;O&Ouml;MM Spielauslosung (4. 7. 2010, 123 KB)</a>
            <hr>

            <h4>Saison 2009-2010</h4>
            <a target="_blank" href="http://obv.tournamentsoftware.com/sport/tournament.aspx?id=B1885A2D-0662-4B87-A6C3-E863843ADF29">&emsp;O&Ouml;MM-2009-2010-Tournamentsoftware</a>
            <br>
            <a target="_blank" href="http://www.badminton-ooe.at/wp-content/uploads/2010/01/SCH-JGD-MM-2010-Termine.pdf">&emsp;Termine SCH-JGD-MM (10. 01. 2010, 16 KB)</a>
            <br>
            <a target="_blank" href="http://www.badminton-ooe.at/wp-content/uploads/2010/01/SCH-JD-MM-2010Spielerrangliste.pdf">&emsp;Rangliste SCH-JGD-MM (10. 01. 2010, 204 KB)</a>


            <hr>

            <h4>Saison 2008-2009</h4>
            <a target="_blank" href="http://obv.tournamentsoftware.com/sport/tournament.aspx?id=64F835B2-2189-412D-B5A6-82510D27299D">&emsp;O&Ouml;MM-2008-2009-Tournamentsoftware</a>
            <hr>

            <h4>Saison 2007-2008</h4>
            <a target="_blank" href="http://obv.tournamentsoftware.com/sport/tournament.aspx?id=684BCC56-E80F-4CEC-90C8-81B9BBC59207">&emsp;O&Ouml;MM-2007-2008-Tournamentsoftware</a>
            <hr>

            <h4>&Auml;ltere Eintr&auml;ge</h4>

            <center>
                <div class="archive_table_thumb">
                    ';

                    $strSQL = "SELECT DISTINCT year FROM ooemm_archive ORDER BY year DESC";
                    $rs=mysqli_query($link,$strSQL);
                    while($row=mysqli_fetch_assoc($rs))
                    {
                        echo '
                            <a href="/ooemm-archiv/jahr/'.str_replace('/','-',$row['year']).'">
                                <table>
                                    <tr><th>'.$row['year'].'</th></tr>
                                    ';

                                    $year = $row['year'];
                                    $strSQLT = "SELECT DISTINCT table_name FROM ooemm_archive WHERE year = '$year' ORDER BY table_name ASC";
                                    $rsT=mysqli_query($link,$strSQLT);
                                    while($rowT=mysqli_fetch_assoc($rsT))
                                    {
                                        echo '<tr><td><a href="/ooemm-archiv/jahr/'.str_replace('/','-',$row['year']).'#'.$rowT['table_name'].'">'.$rowT['table_name'].'</a></td></tr>';
                                    }
                                    echo '
                                </table>
                            </a>
                        ';
                    }
                    echo '
                </div>
            </center>
        ';
    }



    include("footer.php");
?>