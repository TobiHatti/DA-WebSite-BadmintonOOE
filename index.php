<?php
    require("header.php");
    PageTitle("Home");


    echo '
        <div class="indexContentClassic">
            <div class="doublecol">
                <article>

                    <!--

                    <center>
                        <div id="wowslider-container1">
                            <div class="ws_images">
                                <ul>
                                    <li>
                                        <img src="/content/news/Hobbynachwuchsturnier-in-Altmuenster/5b8556fc07fd9.png" title="Title 01" id="wows1_0"/>
                                    </li>
                                    <li>
                                        <img src="/content/news/Mit-neuem-Schwung-in-die-Saison-2018-19/5b8557d50d4dc.png"  title="Title 02" id="wows1_1"/>
                                    </li>
                                </ul>
                            </div>
                            <div class="ws_bullets">
                                <div>
                                    <a href="#" title="5b8556fc07fd9"><span><img src="/content/news/Hobbynachwuchsturnier-in-Altmuenster/5b8556fc07fd9.png" alt="Title 01" height="48px"/>1</span></a>
                                    <a href="#" title="5b8557d50d4dc"><span><img src="/content/news/Mit-neuem-Schwung-in-die-Saison-2018-19/5b8557d50d4dc.png" alt="Title 02" height="48px"/>2</span></a>
                                </div>
                            </div>
                            <div class="ws_shadow"></div>
                        </div>
                        <script type="text/javascript" src="/js/wowslider.js"></script>
                        <script type="text/javascript" src="/js/script.js"></script>
                    </center>

                    -->

                    <hr>
                    <br>
                    <div class="home_news_container">
                    ';

                    $today = date("Y-m-d");
                    // Only Top-News
                    echo NewsTile("SELECT * FROM news WHERE release_date <= '$today' AND tags LIKE '%Top-News%' ORDER BY release_date DESC, id DESC LIMIT 0,3");

                    echo '

                            </div>
                    </article>

                <aside>
                   <div class="home_tile_wrapper">
                        <div class="home_tile_col1">


                            <div class="home_tile_container_l stagfade1">
                                <div class="home_tile_title">Nachwuchs</div>
                                <div class="home_tile_content">

                                    Content

                                </div>
                            </div>

                            <div class="home_tile_container_l stagfade2">
                                <div class="home_tile_title"><img src="/content/rss.png" alt="" style="width:20px; height: 20px; margin-bottom:-2px; margin-right: 5px;"/>&Ouml;BV News</div>
                                <div class="home_tile_content">
                                    <ul>
                                        <li><a href="http://www.badminton.at/extended.php?id=5217" target="_blank">Bundessportakademie</a></li>
                                        <li><a href="http://www.badminton.at/extended.php?id=5216" target="_blank">White Nights 2018</a></li>
                                        <li><a href="http://www.badminton.at/extended.php?id=5215" target="_blank">&Ouml;BV-Leistungssportkonferenz</a></li>
                                        <li><a href="http://www.badminton.at/extended.php?id=5214" target="_blank">&Ouml;BV-Kdernominierungen</a></li>
                                        <li><a href="http://www.badminton.at/extended.php?id=5213" target="_blank">Shuttle Time und &Uuml;L-Ausbildungen</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="home_tile_container_l stagfade3">
                                <div class="home_tile_title">Videos</div>
                                <div class="home_tile_content">
                                    <iframe width="220" src="https://www.youtube.com/embed/_FpDVKgfTHs" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                </div>
                            </div>

                            <div class="home_tile_container_l stagfade3">
                                <div class="home_tile_title">Development</div>
                                <div class="home_tile_content">
                                    <ul>
                                        <li><u><b>Development-Tools</b></u></li>
                                        <li><a target="_blank" href="https://e64980-phpmyadmin.services.easyname.eu">MySQL Datenbank</a></li>
                                        <li><a href="__dbsync">Datenbank Tools</a></li>
                                        <li><a href="__tempform">Temp-Forms</a></li>
                                        <li><a href="__sources">Code-Sources</a></li>
                                        <li><a href="__templates">Templates</a></li>
                                        <li><a href="__test">Test</a></li>
                                        <li><a target="_blank" href="https://codeshare.io/2WzAbE">CodeShare.io</a></li>
                                        <li><a target="_blank" href="https://github.com/TobiHatti/DA-WebSite-BadmintonOOE/projects">GitHub Project Manager</a></li>
                                        <li><u><b>Demos/Training</b></u></li>
                                        <li><a href="__formoverview">PHP-Forms Overview</a></li>
                                        <li><a href="__formintro1">PHP-Forms - Werte Speichern</a></li>
                                        <li><a href="__formintro2">PHP-Forms - Werte Laden</a></li>
                                    </ul>
                                </div>
                            </div>


                        </div>
                        <div class="home_tile_col2">

                            <div class="home_tile_container_s stagfade1">
                                <div class="home_tile_title">Termine</div>
                                    <div class="home_tile_content">
                                    <ul>
                                        <li> Terminliste zurzeit nicht verf&uuml;gbar, bitte folgenden Link zum Terminkalender verwenden ...</li>
                                        <br>
                                        <li><a href="agenda">Terminkalender</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="home_tile_container_s stagfade2">
                                <div class="home_tile_title">Meisterschaft</div>
                                <div class="home_tile_content">
                                    <ul>
                                        <li>1. Landesliga</li>
                                    </ul>
                                    <table cellspacing="0px" cellpadding="2px">
                                        <tr style="font-weight: bold;">
                                            <td>Pl</td>
                                            <td>Mannschaft</td>
                                            <td>Sp</td>
                                            <td>Pkt</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">1</td>
                                            <td>BSC 70 Linz 2</td>
                                            <td style="text-align:center;">10</td>
                                            <td style="text-align:center;">28</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">2</td>
                                            <td>Union Oaschdorf</td>
                                            <td style="text-align:center;">10</td>
                                            <td style="text-align:center;">25</td>

                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">3</td>
                                            <td>U. W-garsten 1</td>
                                            <td style="text-align:center;">10</td>
                                            <td style="text-align:center;">21</td>

                                        </tr>
                                         <tr>
                                            <td style="text-align:right;">4</td>
                                            <td>ASK&Ouml; Traun 2</td>
                                            <td style="text-align:center;">10</td>
                                            <td style="text-align:center;">20</td>
                                        </tr>
                                         <tr>
                                            <td style="text-align:right;">5</td>
                                            <td>UBC Vorchdorf 2</td>
                                            <td style="text-align:center;">10</td>
                                            <td style="text-align:center;">14</td>
                                        </tr>
                                         <tr>
                                            <td style="text-align:right;">6</td>
                                            <td>UBC Neuhofen 1</td>
                                            <td style="text-align:center;">10</td>
                                            <td style="text-align:center;">12</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="home_tile_container_s stagfade3">
                                <div class="home_tile_title">Ranglisten</div>
                                <div class="home_tile_content">

                                    Content

                                </div>
                            </div>

                            <div class="home_tile_container_s stagfade4">
                                <div class="home_tile_title">Links</div>
                                <div class="home_tile_content">
                                    <ul>
                                        <li><a href="http://www.badminton.at/" target="_blank">&Ouml;. Badmintonverband</a></li>
                                        <li><a href="http://oebv-badminton.liga.nu/" target="_blank">&Ouml;BV-Verwaltungssystem</a></li>
                                        <li><a href="http://obv.tournamentsoftware.com/Home" target="_blank">Tournamentsoftware</a></li>
                                        <li><a href="https://turnieranmeldung.at/" target="_blank">Turnieranmeldung O&Ouml;BV Doppeltuniere</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    ';

// =================================================================================================
// =================================================================================================
//      Modern Layout
// =================================================================================================
// =================================================================================================

    $today = date("Y-m-d");

    $sliderLimit = GetProperty("SliderImageCount");
    $nachwuchsLimit = GetProperty("NewsAmountStartpageNW");
    $newsLimit = GetProperty("NewsAmountStartpageTN");

    echo '
        <div class="indexContentModern">
    ';


    if(GetProperty("ShowTodaysEvents") AND MySQLExists("SELECT id FROM zentralausschreibungen WHERE date_begin = '$today'"))
    {
        $zaVal = FetchArray("zentralausschreibungen","date_begin",$today);

        echo '
            <h2>Heute, am '.str_replace('ä','&auml;',strftime("%A den %d. %B %Y",strtotime($today))).':</h2>
            <hr>
            <div class="todays_event tripple_container">
                <div>
                    <span>Zentralausschreibung:</span>
                </div>
                <div>
                    <h4 style="color: '.GetProperty("Color".$zaVal['kategorie']).'">'.$zaVal['title_line1'].'</h4>
                    <h4 style="color: '.GetProperty("Color".$zaVal['kategorie']).'">'.$zaVal['title_line2'].'</h4>
                </div>
                <div>
                    <center>
                        <table>
                            <tr>
                                <td class="ta_r"><b>Verein:</b></td>
                                <td class="ta_l">'.$zaVal['verein'].'</td>
                            </tr>
                            <tr>
                                <td class="ta_r"><b>Uhrzeit:</b></td>
                                <td class="ta_l">'.$zaVal['uhrzeit'].'</td>
                            </tr>
                            <tr>
                                <td colspan=2 style="text-align: center;"><a href="/zentralausschreibung#'.SReplace($zaVal['title_line1'].' '.$zaVal['title_line2']).'">Weitere Infos &#9654;</a></td>
                            </tr>
                        </table>
                    </center>
                </div>
            </div>

        ';
    }

    echo '
            <div class="doublecol">
                <article>
                    <h2>News</h2>
                    <hr>

                    <center>
                        <div id="wowslider-container1">
                            <div class="ws_images">
                                <ul>
                                    ';
                                    //RefreshSliderContent();
                                    // 3 SQL-Queries are required for the slider to work in the best way.
                                    // Bundeling some queries can slow the slider down and skip slides
                                    $i=1;
                                    $refreshID = uniqid();
                                    $strSQL = "SELECT * FROM news ORDER BY release_date DESC, id DESC LIMIT 0,$sliderLimit";
                                    $rs=mysqli_query($link,$strSQL);
                                    while($row=mysqli_fetch_assoc($rs))
                                    {
                                        echo '
                                        <li>
                                            <img src="/content/news/_slideshow/slide_'.$i.'.jpg?'.$refreshID.'" title="'.$row['title'].'" id="wows1_'.($i-1).'"/>
                                        </li>
                                        ';

                                        $i++;
                                    }

                                    echo '
                                </ul>
                            </div>
                            <div class="ws_bullets">
                                <div>
                                    ';

                                    $i=1;
                                    $strSQL = "SELECT * FROM news ORDER BY release_date DESC, id DESC LIMIT 0,$sliderLimit";
                                    $rs=mysqli_query($link,$strSQL);
                                    while($row=mysqli_fetch_assoc($rs))
                                    {
                                        echo '<a href="#" title="'.$row['title'].'"><span><img src="/content/news/_slideshow/slide_'.$i.'.jpg?'.$refreshID.'" alt="'.$row['title'].'" height="48px"/>'.$i++.'</span></a>';
                                    }

                                    echo '
                                </div>
                            </div>
                            <div class="ws_shadow"></div>
                        </div>
                        <script type="text/javascript" src="/js/wowslider.js"></script>
                        <script type="text/javascript" src="/js/slides/'.GetProperty("SliderAnimation").'"></script>
                    </center>

                    ';

                    $i=1;
                    $strSQL = "SELECT * FROM news ORDER BY release_date DESC, id DESC LIMIT 0,$sliderLimit";
                    $rs=mysqli_query($link,$strSQL);
                    while($row=mysqli_fetch_assoc($rs))
                    {
                        echo '
                            <input type="hidden" id="slideTitle'.$i.'" value="'.$row['title'].'">
                            <input type="hidden" id="slideDate'.$i.'" value="'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['release_date']))).'">
                            <input type="hidden" id="slideLink'.$i++.'" value="'.$row['article_url'].'">
                        ';
                    }

                    echo '

                    <script>
                        window.setInterval(function(){
                            CopySliderTitle('.$sliderLimit.');
                        }, 500);
                    </script>

                    <h1><a href="" id="sliderLink"><output id="slider_news_title" style="color: #000000; height: 36px; overflow: hidden;">News-Artikel Titel</output></a></h1>
                    <span style="color: #A9A9A9"><output id="sliderDate"></output></span>
                </article>
                <aside>
                    <h2>Nachwuchs</h2>
                    <hr>
                    '.NewsTileSlim("SELECT * FROM news WHERE release_date <= '$today' AND tags LIKE '%Nachwuchs%' ORDER BY release_date DESC, id DESC LIMIT 0,$nachwuchsLimit").'
                </aside>
            </div>
            <br>
            <div>
                <h3>Neuigkeiten</h3>
                <hr>
                <div class="mdrn_news_tile_container">
                    ';

                    $i=2;
                    $strSQL = "SELECT * FROM news WHERE release_date <= '$today' AND tags LIKE '%Top-News%' ORDER BY release_date DESC, id DESC LIMIT 0,$newsLimit";
                    $rs=mysqli_query($link,$strSQL);
                    while($row=mysqli_fetch_assoc($rs))
                    {
                        echo '
                            <div class="mdrn_news_tile stagfade'.$i++.'">
                                <div class="img_tag_container">
                                    <a href="/news/artikel/'.$row['article_url'].'"><img src="'.$row['thumbnail'].'" alt=""/></a>
                                    <div class="tag_overlay">'.ShowTags($row['tags']).'</div>
                                </div>
                                <a href="/news/artikel/'.$row['article_url'].'"><b>'.$row['title'].'</b></a>
                                <p></p>
                                <span>
                                '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['release_date']))).':&nbsp;
                                </span>
                                <div class="p">
                                '.TrimText(str_replace('</p><p>',' ',str_replace('<br>',' ',NBSPClean(str_replace($row['title'],'',$row['article'])))), 120) .'<br>
                                </div>
                                <a href="/news/artikel/'.$row['article_url'].'">&#9654; Mehr lesen</a>
                            </div>
                        ';
                    }
                    echo '
                </div>
                <br>

                <div class="tripple_container">
                    <div>
                        <h3>Meisterschaft [WIP]</h3>
                        <hr>
                        <center>
                            <br>
                            <select name="" id="Meisterschaft" onchange="RedirectListLink(\'Meisterschaft\');">
                                <option value="" selected disabled>&#9135;&#9135; Tabelle Ausw&auml;hlen &#9135;&#9135;</option>
                                <option value="http://obv.tournamentsoftware.com/sport/draw.aspx?id=D99AEEED-819E-4D1D-9648-4FE568AB2777&draw=1">1. Bundesliga</option>
                                <option value="http://obv.tournamentsoftware.com/sport/draw.aspx?id=D99AEEED-819E-4D1D-9648-4FE568AB2777&draw=2">2. Bundesliga</option>
                                <option value="http://obv.tournamentsoftware.com/sport/draw.aspx?id=7E6C0314-C641-4A5D-93D8-52C3E0EE981D&draw=3">1. Landesliga</option>
                                <option value="http://obv.tournamentsoftware.com/sport/draw.aspx?id=7E6C0314-C641-4A5D-93D8-52C3E0EE981D&draw=5">2. Landesliga Nord</option>
                                <option value="http://obv.tournamentsoftware.com/sport/draw.aspx?id=7E6C0314-C641-4A5D-93D8-52C3E0EE981D&draw=6">2. Landesliga S&uuml;d</option>
                                <option value="http://obv.tournamentsoftware.com/sport/draw.aspx?id=7E6C0314-C641-4A5D-93D8-52C3E0EE981D&draw=7">Bezirksliga Nord</option>
                                <option value="http://obv.tournamentsoftware.com/sport/draw.aspx?id=7E6C0314-C641-4A5D-93D8-52C3E0EE981D&draw=8">Bezirksliga S&uuml;d</option>
                                <option value="http://obv.tournamentsoftware.com/sport/draw.aspx?id=7E6C0314-C641-4A5D-93D8-52C3E0EE981D&draw=1">1. Klasse Nord</option>
                                <option value="http://obv.tournamentsoftware.com/sport/draw.aspx?id=7E6C0314-C641-4A5D-93D8-52C3E0EE981D&draw=2">1. Klasse S&uuml;d</option>
                                <option value="http://obv.tournamentsoftware.com/sport/draw.aspx?id=7E6C0314-C641-4A5D-93D8-52C3E0EE981D&draw=4">2. Klasse</option>
                                <option value="http://obv.tournamentsoftware.com/sport/events.aspx?id=C5983872-69F8-4E1B-A463-CB1DB1323A39&tlt=1">SCH-JGD-O&Ouml;MM</option>
                            </select>
                            <br>
                            <table>
                                <tr>
                                    <td><b>Pl</b></td>
                                    <td><b>Mannschaft</b></td>
                                    <td><b>Sp</b></td>
                                    <td><b>Pkt</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">1</td>
                                    <td>BSC 70 Linz 2</td>
                                    <td style="text-align:center;">10</td>
                                    <td style="text-align:center;">28</td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">2</td>
                                    <td>Union Oaschdorf</td>
                                    <td style="text-align:center;">10</td>
                                    <td style="text-align:center;">25</td>

                                </tr>
                                <tr>
                                    <td style="text-align:right;">3</td>
                                    <td>U. W-garsten 1</td>
                                    <td style="text-align:center;">10</td>
                                    <td style="text-align:center;">21</td>

                                </tr>
                                 <tr>
                                    <td style="text-align:right;">4</td>
                                    <td>ASK&Ouml; Traun 2</td>
                                    <td style="text-align:center;">10</td>
                                    <td style="text-align:center;">20</td>
                                </tr>
                                 <tr>
                                    <td style="text-align:right;">5</td>
                                    <td>UBC Vorchdorf 2</td>
                                    <td style="text-align:center;">10</td>
                                    <td style="text-align:center;">14</td>
                                </tr>
                                 <tr>
                                    <td style="text-align:right;">6</td>
                                    <td>UBC Neuhofen 1</td>
                                    <td style="text-align:center;">10</td>
                                    <td style="text-align:center;">12</td>
                                </tr>
                            </table>
                        </center>
                    </div>
                    <div>
                        <h3>Ranglisten [WIP]</h3>
                        <hr>
                        <br>
                        <center>
                            <select name="" id="">
                                <option value="" selected disabled>&#9135;&#9135; Rangliste Ausw&auml;hlen &#9135;&#9135;</option>
                                <option value="">O&Ouml;BV DD Allg. Klasse</option>
                                <option value="">O&Ouml;BV HD Allg. Klasse</option>
                                <option value="">O&Ouml;BV Mixed Allg. Klasse</option>
                                <option value="">&Ouml;BV DE Allg. Klasse</option>
                                <option value="">&Ouml;BV HE Allg. Klasse</option>
                                <option value="">&Ouml;BV DD Allg. Klasse</option>
                                <option value="">&Ouml;BV HD Allg. Klasse</option>
                                <option value="">&Ouml;BV Mixed Allg. Klasse</option>
                            </select>
                            <br><br>
                            <img src="/content/ooebv.png" alt="" width="100px;"/>
                        </center>
                    </div>
                    <div>
                        <h3>Links [WIP]</h3>
                        <hr>
                    </div>
                </div>
                <br><br><br>
                <div class="double_container">

                    <div style="text-align: center; min-width: 330px;">
                        <a href="/kalender"><h3 style="text-align: left;">Termine</h3></a>
                        <hr>
                        <iframe src="/graphic_calendar_thumb" frameborder="0" style="height: 250px; width: 320px;" scrolling="no"></iframe>
                    </div>
                    <div>
                        <h3><img src="/content/rss.png" alt="" style="width:20px; height: 20px; margin-bottom:-2px; margin-right: 5px;"/>&Ouml;BV News</h3>
                        <hr>
                        <center>
                            <div style="max-width: 500px; min-width: 300px;">
                                <script language="javascript" src="http://www.badminton.at/files/rss/badminton_news.php"></script>
                            </div>
                        </center>
                    </div>
                </div>
                <br><br><br>
                <div>
                        <h3>Veranstaltungen</h3>
                        <hr>
                        <center>
                            <iframe width="420px" height="240" src="https://www.youtube.com/embed/_FpDVKgfTHs" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </center>
                    </div>

                <hr>
                <hr>
                <hr>

                <h3>Development</h3>
                <hr>
                <ul>
                    <li><u><b>Development-Tools</b></u></li>
                    <li><a target="_blank" href="https://e64980-phpmyadmin.services.easyname.eu">MySQL Datenbank</a></li>
                    <li><a href="__dbsync">Datenbank Tools</a></li>
                    <li><a href="__tempform">Temp-Forms</a></li>
                    <li><a href="__sources">Code-Sources</a></li>
                    <li><a href="__templates">Templates</a></li>
                    <li><a href="__test">Test</a></li>
                    <li><a target="_blank" href="https://codeshare.io/2WzAbE">CodeShare.io</a></li>
                    <li><a target="_blank" href="https://github.com/TobiHatti/DA-WebSite-BadmintonOOE/projects">GitHub Project Manager</a></li>
                    <li><u><b>Demos/Training</b></u></li>
                    <li><a href="__formoverview">PHP-Forms Overview</a></li>
                    <li><a href="__formintro1">PHP-Forms - Werte Speichern</a></li>
                    <li><a href="__formintro2">PHP-Forms - Werte Laden</a></li>
                </ul>

            </div>
        </div>
    ';


    include("footer.php");
?>