<?php
    require("header.php");
    PageTitle("O\u00d6BV-Ranglisten");

    echo '<h1 class="stagfade1">O&Ouml;BV-Ranglisten</h1>';

    if(!isset($_GET['section']))
    {
        echo '
            <p>
                <h4>Einzel <sub>Bearbeiter Markus Eckersberger (Stand: 05.10.2018)</sub></h4>
                <a href="/ooebv-ranglisten/U11-U19-Maedchen">U11 - U19 M&auml;dchen Einzel</a><br>
                <a href="/ooebv-ranglisten/U11-U19-Burschen">U11 - U19 Burschen Einzel</a><br>

                <br><br>
                <h4>Einzel <sub>Bearbeiter Markus Eckersberger (Stand: 05.10.2018)</sub></h4>
                <a href="/ooebv-ranglisten/Damen-Doppel">Damen Doppel (Stand 22.10.2018)</a><br>
                <a href="/ooebv-ranglisten/Herren-Doppel">Herren Doppel (Stand 22.10.2018)</a><br>
                <a href="/ooebv-ranglisten/Mixed-Doppel">Mixed Doppel(Stand 27.05.2018)</a><br>

            </p>
        ';
    }
    if(isset($_GET['section']))
    {
        if($_GET['section']=='U11-U19-Maedchen')
        {
            echo '<h3>U11 - U19 M&auml;dchen</h3>';

            if(isset($_GET['edit']))
            {

                echo '
                    <table>
                        <tr>
                            <td>
                                Rang mit iQueryUI Sortable<br>
                                Spieler manuel eintragen oder Automatisch<br>
                                Sonstige felder auf dieser Seite (1.Rd, 2.Rd, ..., Ges.)
                            </td>
                        </tr>
                    </table>
                ';

            }
            else if(CheckPermission("editOOEBVRLJgnd")) echo EditButton("/ooebv-ranglisten/".$_GET['section']."/edit");

            echo '
                <center>
                    <table class="ooebvRanglistenTable">
                        <tr>
                            <td colspan=2>O&Ouml;BV<br>Rangliste 2018</td>
                            <td colspan=4 style="font-size: 16pt; font-weight: normal">Damen Einzel U11</td>
                            <td colspan=4>Stand nach 5. Runde<br>22.10.2018</td>
                        </tr>
                        <tr>
                            <td>Rang</td>
                            <td>MgNr.</td>
                            <td>Name</td>
                            <td>Verein</td>
                            <td>Jg.</td>
                            <td>1. Rd</td>
                            <td>2. Rd</td>
                            <td>3. Rd</td>
                            <td>Str.</td>
                            <td>Ges.</td>
                        </tr>
                    </table>
                </center>
            ';

        }

        if($_GET['section']=='U11-U19-Burschen')
        {
            echo '<h3>U11 - U19 Burschen Einzel</h3>';

            echo '
                <center>

                </center>
            ';
        }

        if($_GET['section']=='Damen-Doppel')
        {
            echo '<h3>Damen Doppel</h3>';

            echo '
                <center>

                </center>
            ';
        }

        if($_GET['section']=='Herren-Doppel')
        {
            echo '<h3>Herren Doppel</h3>';

            echo '
                <center>

                </center>
            ';
        }

        if($_GET['section']=='Mixed-Doppel')
        {
            echo '<h3>Mixed Doppel</h3>';

            echo '
                <center>

                </center>
            ';
        }
    }

    echo '


    <p>
       '.PageContent('1',CheckPermission("ChangeContent")).'
    </p>



    ';

    include("footer.php");
?>