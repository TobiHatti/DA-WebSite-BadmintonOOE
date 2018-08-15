<?php
    require("header.php");
    PageTitle("O&Ouml;MM-Archiv");

    echo '<h1 class="stagfade1">O&Ouml;MM-Archiv</h1>';
    echo '<h2 class="stagfade2">'.$_GET['jahr'].'</h2>';

    if(isset($_GET['jahr']) AND $_GET['jahr']=="2005-2006")
    {
        echo '
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
                 <th colspan="9">2. Landesliga Nord</th>
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
                  <th colspan="9">2. Landesliga S&uuml;d</th>
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
                  <th colspan="9">1. Klasse Nord</th>
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
                   <td>ASK&Ouml; Traun III</td>
                   <td>8</td>
                   <td>8</td>
                   <td>0</td>
                   <td>0</td>
                   <td>57:7</td>
                   <td>117:18</td>
                   <td>24</td>


                </tr>

                <tr>
                   <td>2</td>
                   <td>SV Kematen I</td>
                   <td>8</td>
                   <td>4</td>
                   <td>2</td>
                   <td>2</td>
                   <td>36:28</td>
                   <td>76:65</td>
                   <td>18</td>
                </tr>

                <tr>
                   <td>3</td>
                   <td>SK V&ouml;st I</td>
                   <td>8</td>
                   <td>3</td>
                   <td>3</td>
                   <td>2</td>
                   <td>33:31</td>
                   <td>72:68</td>
                   <td>17</td>

                </tr>

                <tr>
                   <td>4</td>
                   <td>ABV Wels I</td>
                   <td>8</td>
                   <td>1</td>
                   <td>1</td>
                   <td>6</td>
                   <td>23:41</td>
                   <td>56:90</td>
                   <td>11</td>

                </tr>

                <tr>
                   <td>5</td>
                   <td>BC M&uuml;nzkirchen I</td>
                   <td>8</td>
                   <td>1</td>
                   <td>0</td>
                   <td>7</td>
                   <td>11:53</td>
                   <td>30:110</td>
                   <td>10</td>


                </tr>
            </table>
            <hr>
            <table>
                  <th colspan="9">2. Klasse Mitte</th>
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
                   <td>Union Ohlsdorf III</td>
                   <td>10</td>
                   <td>7</td>
                   <td>3</td>
                   <td>0</td>
                   <td>53:27</td>
                   <td>115:69</td>
                   <td>27</td>

                </tr>

                <tr>
                   <td>2</td>
                   <td>ATV Josko Andorf II</td>
                   <td>10</td>
                   <td>6</td>
                   <td>2</td>
                   <td>2</td>
                   <td>53:27</td>
                   <td>115:63</td>
                   <td>24</td>
                </tr>

                <tr>
                   <td>3</td>
                   <td>ASK&Ouml; Steyrerm&uuml;hl I</td>
                   <td>10</td>
                   <td>6</td>
                   <td>2</td>
                   <td>2</td>
                   <td>52:28</td>
                   <td>113:62</td>
                   <td>24</td>

                </tr>

                <tr>
                   <td>4</td>
                   <td>ASk&Ouml; Attnang-Puchheim</td>
                   <td>10</td>
                   <td>3</td>
                   <td>2</td>
                   <td>5</td>
                   <td>33:47</td>
                   <td>74:103</td>
                   <td>18</td>

                </tr>

                <tr>
                   <td>5</td>
                   <td>USC Attergau II</td>
                   <td>10</td>
                   <td>3</td>
                   <td>1</td>
                   <td>6</td>
                   <td>35:45</td>
                   <td>80:99</td>
                   <td>17</td>

                </tr>

                <tr>
                   <td>6</td>
                   <td>Sportunion Altm&uuml;nster I</td>
                   <td>10</td>
                   <td>0</td>
                   <td>0</td>
                   <td>10</td>
                   <td>14:66</td>
                   <td>36:137</td>
                   <td>10</td>

                </tr>
            </table>
            <hr>
            <table>
                  <th colspan="9">2. Klasse S&uuml;d</th>
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
                    <td>ASk&Ouml; Traun IV</td>
                    <td>10</td>
                    <td>10</td>
                    <td>0</td>
                    <td>0</td>
                    <td>70:10</td>
                    <td>140:32</td>
                    <td>30</td>
                </tr>

                <tr>
                   <td>2</td>
                   <td>TuSv Altm&uuml;nster I</td>
                   <td>10</td>
                   <td>7</td>
                   <td>0</td>
                   <td>3</td>
                   <td>50:30</td>
                   <td>104:64</td>
                   <td>24</td>

                </tr>

                <tr>
                   <td>3</td>
                   <td>ATSV Steinbach/Gr&uuml;nburg I</td>
                   <td>10</td>
                   <td>5</td>
                   <td>1</td>
                   <td>4</td>
                   <td>42:38</td>
                   <td>96:81</td>
                   <td>21</td>

                </tr>

                <tr>
                   <td>4</td>
                   <td>UBC Neuhofen IV</td>
                   <td>10</td>
                   <td>3</td>
                   <td>2</td>
                   <td>5</td>
                   <td>36:44</td>
                   <td>77:98</td>
                   <td>18</td>

                </tr>

                <tr>
                   <td>5</td>
                   <td>Union Stadl Paura I</td>
                   <td>10</td>
                   <td>2</td>
                   <td>2</td>
                   <td>6</td>
                   <td>29:51</td>
                   <td>65:107</td>
                   <td>16</td>
                </tr>

                <tr>
                   <td>6</td>
                   <td>UBC Neuhofen V</td>
                   <td>10</td>
                   <td>0</td>
                   <td>1</td>
                   <td>9</td>
                   <td>13:67</td>
                   <td>37:137</td>
                   <td>11</td>

                </tr>
            </table>

            </div>


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