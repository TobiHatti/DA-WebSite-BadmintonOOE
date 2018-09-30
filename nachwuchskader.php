<?php
    require("header.php");
    PageTitle("O\u00d6 Nachwuchskader");

    echo '<h1 class="stagfade1">O&Ouml; Nachwuchskader</h1>

    <h3 style="color: blue">Burschen U19 ('.(date("Y") - 18).' - '.(date("Y") - 15).')</h3>
    ';
    $minYear = date("Y") - 18;
    $maxYear = date("Y") - 15;
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'M' AND birthyear >= '$minYear' AND birthyear <= '$maxYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove blue;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }
    echo '

    <br><br>
    <h3 style="color: red">M&auml;dchen U19 ('.(date("Y") - 18).' - '.(date("Y") - 15).')</h3>
    ';
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'W' AND birthyear >= '$minYear' AND birthyear <= '$maxYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove red;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }
    echo '

    <br><br>
    <h3 style="color: blue">Burschen U15 ('.(date("Y") - 14).' - '.(date("Y") - 13).')</h3>
    ';
    $minYear = date("Y") - 14;
    $maxYear = date("Y") - 13;
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'M' AND birthyear >= '$minYear' AND birthyear <= '$maxYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove blue;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }
    echo '

    <br><br>
    <h3 style="color: red">M&auml;dchen U15 ('.(date("Y") - 14).' - '.(date("Y") - 13).')</h3>
    ';
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'W' AND birthyear >= '$minYear' AND birthyear <= '$maxYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove red;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }
    echo '

    <br><br>
    <h3 style="color: blue">Burschen U13 ('.(date("Y") - 12).' - '.(date("Y") - 11).')</h3>
    ';
    $minYear = date("Y") - 12;
    $maxYear = date("Y") - 11;
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'M' AND birthyear >= '$minYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove blue;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }
    echo '

    <br><br>
    <h3 style="color: red">M&auml;dchen U13 ('.(date("Y") - 12).' - '.(date("Y") - 11).')</h3>
    ';
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'W' AND birthyear >= '$minYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove red;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }
    echo '


    <br><br><br><br><br><br><br><br><br><br><br>
    <table style="display: inline-table; verticla-align: top;">
        <tr>

           <td><span style="color: #FF0000"><b>Burschen U19 - 2000-2001<b></span></td>
           <td><span style="color: #000099"><b>M&auml;dchen U19 - 2000-2001</b></span></td>
           <td><span style="color: #FF0000"><b>Burschen U15 - 2004-2005</b></span></td>
        </tr>

        <tr>

            <td>
              <hr>
                Br&uuml;ggenwerth<br>
                Matteo<br>
                2000<br>
                Ask&Ouml; Linz<br>
            </td>

            <td>
              <hr>
                Stadlmayr<br>
                Christine<br>
                2000<br>
                Sportunion Ohlsdorf<br>
            </td>

            <td>
              <hr>
                Windauer<br>
                Jonas<br>
                2004<br>
                Sportunion Ohlsdorf<br>
            </td>



        </tr>

        <tr>
            <td>
              <hr>
                Froschauer<br>
                Luca<br>
                2001<br>
                ASK&Ouml; Traun<br>
            </td>

            <td>
              <hr>
                Zinganellr<br>
                Lena<br>
                2000<br>
                ASK&Ouml; Traun<br>
            </td>

            <td>
              <hr>
                Rosenberger<br>
                Alexander<br>
                2004<br>
                ASk&Ouml; Linz<br>
            </td>




        </tr>

        <tr>
            <td>
              <hr>
                Br&uuml;ggenwerth<br>
                Carle<br>
                2002<br>
                ASK&Ouml; Linz<br>
            </td>

            <td>
              <hr>
                Rebhandl<br>
                Eva<br>
                2000<br>
                Sportunion Windischgarsten<br>
            </td>

            <td>
              <hr>
                Labmayr<br>
                Florian<br>
                2004<br>
                BC Alkhoven<br>
            </td>




        </tr>

        <tr>
            <td>
              <hr>
                Schausberger<br>
                Stefan<br>
                2002<br>
                Sportunion Ohlsdorf<br>
            </td>

            <td>
              <hr>
                Lungenschmid<br>
                Nora<br>
                2002<br>
                Sportunion Kirchdorf<br>
            </td>

            <td>
              <hr>
                Spitzhofer<br>
                Elias<br>
                2004<br>
                ASK&Ouml; Traun<br>
            </td>

        </tr>

        <tr>
           <td>
              <hr>
                Nu&szlig;dorfer<br>
                Jonas<br>
                2002<br>
                USC Attergau<br>
            </td>

           <td>
              <hr>
                Mittermayr<br>
                Carolina<br>
                2003<br>
                ATV Andorf<br>
            </td>

           <td>
              <hr>
                Stockinger<br>
                Marco<br>
                2004<br>
                ASK&Ouml; Traun<br>
            </td>
        </tr>

        <tr>
            <td>
              <hr>
                Huemer<br>
                Alexander<br>
                2002<br>
                Sportunion Ohlsdorf<br>
            </td>

            <td>
              <hr>
                Stadlmayr<br>
                Sandra<br>
                2003<br>
                Sportunion Ohlsdorf<br>
            </td>

            <td>
              <hr>
                B&ouml;sch<br>
                Julian<br>
                2005<br>
                ATV Andorf<br>
            </td>

        </tr>

        <tr>
            <td>
              <hr>
                Schwarzmann<br>
                Adrian<br>
                2003<br>
                BC Windischgarsten<br>
            </td>

            <td>
              <hr>
                Maierhofer<br>
                Helena<br>
                2003<br>
                ABV Wels<br>
            </td>

            <td>
              <hr>
                Schobesberger<br>
                Daniel<br>
                2005<br>
                Union Kirchdorf<br>
            </td>

        </tr>

        <tr>
            <td>
              <hr>
                Niederhuber<br>
                Kai<br>
                2003<br>
                Union Neuhofen<br>
            </td>

            <td>
            </td>

            <td>
              <hr>
                Grum<br>
                Christoph<br>
                2007<br>
                BC Alkhoven<br>
            </td>
        </tr>

        <tr>
            <td>
              <hr>
                Auberger<br>
                Jannik<br>
                2003<br>
                ASK&Ouml; Traun<br>
            </td>

            <td>
            </td>

            <td>
              <hr>
                Kaiblinger<br>
                Simon<br>
                2005<br>
                BC Alkhoven<br>
            </td>

        </tr>

        <tr>
             <td>
              <hr>
                Ofner<br>
                Eric<br>
                2003<br>
                Union Kirchdorf<br>
            </td>
        </tr>

        <tr>
            <td>
              <hr>
                Tomancok<br>
                Max<br>
                2003<br>
                BSC 70 Linz<br>
            </td>
        </tr>

    </table>

    
    <table style="display: inline-table; verticla-align: top;">
        <tr>
            <td><span style="color: #000099"><b>M&auml;dchen U15 - 2004-2005</b></span></td>
            <td><span style="color: #FF0000"><b>Burschen U13 - 2006-2007</b></span></td>
            <td><span style="color: #000099"><b>M&auml;dchen U13 - 2006-2007</b></span></td>
        </tr>
        <tr>
            <td>
              <hr>
                Dlapka<br>
                Kira<br>
                2004<br>
                Sportunion Ohlsdorf<br>
            </td>

            <td>
              <hr>
                Windauer<br>
                Lorenz<br>
                2006<br>
                Sportunion Ohlsdorf<br>
            </td>

            <td>
              <hr>
                Fr&uuml;hauf<br>
                Raffaela<br>
                2006<br>
                BC Alkhoven<br>
            </td>

          </tr>

          <tr>
               <td>
              <hr>
                Stockinger<br>
                Lara<br>
                2004<br>
                ASK&Ouml; Traun<br>
            </td>

            <td>
              <hr>
                Maier<br>
                Benjamin<br>
                2006<br>
                BC Alkhoven<br>
            </td>

            <td>
              <hr>
                Dlapka<br>
                Sarah<br>
                2006<br>
                Sportunion Ohlsdorf<br>
            </td>

          </tr>

          <tr>
             <td>
              <hr>
                Fobian<br>
                Anita<br>
                2004<br>
                ASK&Ouml; Traun<br>
            </td>

            <td>
              <hr>
                Fichtner<br>
                Niklas<br>
                2006<br>
                BC Windischgarsten<br>
            </td>

            <td>
              <hr>
                Gillesberger<br>
                Hanna<br>
                2007<br>
                Sportunion Ohlsdorf<br>
            </td>

          </tr>

          <tr>
            <td>
              <hr>
                Auberger<br>
                Ronja<br>
                2004<br>
                ASK&Ouml; Traun<br>
            </td>

            <td>
              <hr>
                Hayb&ouml;ck<br>
                Eric<br>
                2008<br>
                BC Windischgarsten<br>
            </td>

            <td>
              <hr>
                Manjic<br>
                Emma<br>
                2007<br>
                BC Alkhoven<br>
            </td>

        </tr>

        <tr>
             <td>
              <hr>
                Gassenbauer<br>
                Katharina<br>
                2004<br>
                Union Altm&uuml;ster<br>
            </td>

            <td>
              <hr>
                Huber<br>
                Sebastian<br>
                2007<br>
                Sportunion Ohlsdorf<br>
            </td>

        </tr>

        <tr>
            <td>
              <hr>
                Hattinger<br>
                Lara<br>
                2005<br>
                Union Altm&uuml;nster<br>
            </td>

        </tr>

        <tr>
            <td>
              <hr>
                Weberstorfer<br>
                Emma<br>
                2005<br>
                Union Altm&uuml;nster<br>
            </td>

        </tr>

        <tr>
            <td>
              <hr>
                Stierberger<br>
                Tabea<br>
                2005<br>
                BC Alkhoven<br>
            </td>

        </tr>

        <tr>


        </tr>



    </table>
    <br>
    <div>
        <b>1.1-30.6.2018 g&uuml;ltig</b>
    </div>
    <br>
    <p>
        <b>Kaderkriterien:</b>
      <ul>
          <li>Speilst&auml;rke</li>
          <li>Borg/BTZ Ambitionen</li>
          <li>Regelm&auml;&szlig;iges Training mit Qualit&auml;t (3xWoche)</li>
          <li>Entwicklungspotential</li>
          <li>regelm&auml;&szlig;e Teilnahme an Tunieren</li>
      </ul>
    </p>


    ';

    include("footer.php");
?>