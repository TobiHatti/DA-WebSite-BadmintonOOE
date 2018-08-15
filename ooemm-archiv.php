<?php
    require("header.php");
    PageTitle("O&Ouml;MM-Archiv");

    echo '<h1 class="stagfade1">O&Ouml;MM-Archiv</h1>';
    echo '<h2 class="stagfade2">'.$_GET['jahr'].'</h2>';

    if(isset($_GET['jahr']) AND $_GET['jahr']=="2005-2006")
    {
        echo '
            <table>
                <th>1. Landesliga</th>
                <tr>
                    <td>
                      <b>Pl</b>
                    </td>
                    <td>
                      <b>Verein</b>
                    </td>
                    <td>
                      <b>Rd</b>
                    </td>
                    <td>
                      <b>S</b>
                    </td>
                    <td>
                      <b>U</b>
                    </td>
                    <td>
                      <b>N</b>
                    </td>
                    <td>
                      <b>Spiele</b>
                    </td>
                    <td>
                      <b>S&auml;tze</b>
                    </td>
                    <td>
                      <b>Pkt</b>
                    </td>
                </tr>

                <tr>
                   <td>1</td>
                   <td>ASK&Ouml; Traun</td>
                   <td>10</td>
                   <td>10</td>
                   <td>0</td>
                   <td>0</td>
                   <td>71:9</td>
                   <td>143:26</td>
                   <td>30</td>
                </tr>

                <tr>
                  <td>2</td>
                  <td>UBC Sodian Vorchdorf II</td>
                  <td>10</td>
                  <td>6</td>
                  <td>1</td>
                  <td>3</td>
                  <td>74:33</td>
                  <td>99:74</td>
                  <td>23</td>
                </tr>

                <tr>
                  <td>3</td>
                  <td>BC Raiffeisen Alkhoven I</td>
                  <td>10</td>
                  <td>5</td>
                  <td>2</td>
                  <td>3</td>
                  <td>44:36</td>
                  <td>97:73</td>
                  <td>22</td>
                </tr>

                <tr>
                    <td>4</td>
                </tr>
            </table>
        ';
    }
    else if(isset($_GET['jahr']) AND $_GET['jahr']=="2004-2005")
    {

    }
    else if(isset($_GET['jahr']) AND $_GET['jahr']=="2003-2004")
    {

    }
    else if(isset($_GET['jahr']) AND $_GET['jahr']=="2002-2003")
    {

    }
    else if(isset($_GET['jahr']) AND $_GET['jahr']=="2001-2002")
    {

    }
    else if(isset($_GET['jahr']) AND $_GET['jahr']=="2000-2001")
    {

    }
    else if(isset($_GET['jahr']) AND $_GET['jahr']=="19990-2000")
    {

    }



    include("footer.php");
?>