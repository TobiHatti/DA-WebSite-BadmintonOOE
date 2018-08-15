<?php
    require("header.php");
    PageTitle("O&Ouml;MM-Archiv");

    echo '<h1 class="stagfade1">O&Ouml;MM-Archiv</h1>';
    echo '<h2 class="stagfade2">'.$_GET['jahr'].'</h2>';

    if(isset($_GET['jahr']) AND $_GET['jahr']=="2005-2006")
    {
        echo '
<<<<<<< HEAD
        <div class="archiveTables">
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
                    <td>Union Windischgarsten I</td>
                    <td>10</td>
                    <td>4</td>
                    <td>1</td>
                    <td>5</td>
                    <td>31:49</td>
                    <td>67:105</td>
                    <td>19</td>
                </tr>

                <tr>
                    <td>5</td>
                    <td>UBC Sodian Vorchdorf III</td>
                    <td>10</td>
                    <td>2</td>
                    <td>0</td>
                    <td>8</td>
                    <td>29:51</td>
                    <td>69:108</td>
                    <td>14</td>
                </tr>

                <tr>
                    <td>6</td>
                    <td>ATV Josko Andorf I</td>
                    <td>10</td>
                    <td>1</td>
                    <td>0</td>
                    <td>9</td>
                    <td>18:62</td>
                    <td>38:127</td>
                    <td>10</td>
                </tr>
            </table>
            <hr>
            <table>
                 <th>2. Landesliga Nord</th>
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
                   <td>Union Urfahr I</td>
                   <td>10</td>
                   <td>8</td>
                   <td>0</td>
                   <td>2</td>
                   <td>51:29</td>
                   <td>107:71</td>
                   <td>26</td>

                </tr>

                <tr>
                   <td>2</td>
                   <td>ASK&Ouml; BV Pasching I</td>
                   <td>10</td>
                   <td>8</td>
                   <td>0</td>
                   <td>2</td>
                   <td>51:29</td>
                   <td>107:71</td>
                   <td>26</td>

                </tr>

                <tr>
                   <td>3</td>
                   <td>Union VKB Braunau I</td>
                   <td>10</td>
                   <td>5</td>
                   <td>2</td>
                   <td>3</td>
                   <td>47:33</td>
                   <td>101:69</td>
                   <td>22</td>

                </tr>

                <tr>
                    <td>4</td>
                    <td>BC Raiffeisen Alkhoven II</td>
                    <td>10</td>
                    <td>5</td>
                    <td>1</td>
                    <td>4</td>
                    <td>45:35</td>
                    <td>103:75</td>
                    <td>21</td>

                </tr>

                <tr>
                    <td>5</td>
                    <td>BSC 70 Linz III</td>
                    <td>10</td>
                    <td>3</td>
                    <td>2</td>
                    <td>5</td>
                    <td>34:46</td>
                    <td>76:103</td>
                    <td>18</td>

                </tr>

                <tr>
                    <td>6</td>
                    <td>Union Kirchschlag I</td>
                    <td>10</td>
                    <td>0</td>
                    <td>1</td>
                    <td>9</td>
                    <td>13:67</td>
                    <td>29:137</td>
                    <td>7</td>
                </tr>
            </table>
            <hr>
            <table>
                  <th>2. Landesliga S&uuml;d</th>
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
                   <td>BSC 70 Linz</td>
                   <td>10</td>
                   <td>8</td>
                   <td>1</td>
                   <td>1</td>
                   <td>56:24</td>
                   <td>116:54</td>
                   <td>27</td>

                </tr>

                <tr>
                    <td>2</td>
                    <td>Union Ohlsdorf I</td>
                    <td>10</td>
                    <td>6</td>
                    <td>2</td>
                    <td>2</td>
                    <td>52:28</td>
                    <td>108:68</td>
                    <td>24</td>

                </tr>

                <tr>
                   <td>3</td>
                   <td>ATSV Steyr II</td>
                   <td>10</td>
                   <td>5</td>
                   <td>0</td>
                   <td>5</td>
                   <td>42:38</td>
                   <td>93:83</td>
                   <td>20</td>

                </tr>

                <tr>
                   <td>4</td>
                   <td>ASK Nettingsdorf I</td>
                   <td>10</td>
                   <td>3</td>
                   <td>3</td>
                   <td>4</td>
                   <td>38:42</td>
                   <td>86:97</td>
                   <td>19</td>
                </tr>

                <tr>
                   <td>5</td>
                   <td>UBC Neuhofen I</td>
                   <td>10</td>
                   <td>3</td>
                   <td>1</td>
                   <td>6</td>
                   <td>33:47</td>
                   <td>80:100</td>
                   <td>17</td>

                </tr>

                <tr>
                   <td>6</td>
                   <td>ASK Nettingsdorf II</td>
                   <td>10</td>
                   <td>0</td>
                   <td>3</td>
                   <td>7</td>
                   <td>19:61</td>
                   <td>47:128</td>
                   <td>13</td>

                </tr>
            </table>
            <hr>
            <table>
                  <th>1. Klasse Nord</th>
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
                   <td>1</td>

                </tr>
            </table>

            </div class="archiveTables">
=======
            <div class="archiveTables">
                <table>
                    <th colspan="9">1. Landesliga</th>
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
                </table>
                <table>
                    <th colspan="9">1. Landesliga</th>
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
                </table>
                <table>
                    <th colspan="9">1. Landesliga</th>
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
                </table>
            </div>
>>>>>>> bd68a5778b95a84e59bd77194092568fbd34a64b
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