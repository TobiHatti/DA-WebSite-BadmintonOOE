<?php
    require("header.php");
    PageTitle("Home");

// =================================================================================================
// =================================================================================================
//      Modern Layout
// =================================================================================================
// =================================================================================================

    $today = date("Y-m-d");

    $sliderLimit = Setting::Get("SliderImageCount");
    $nachwuchsLimit = Setting::Get("NewsAmountStartpageNW");
    $newsLimit = Setting::Get("NewsAmountStartpageTN");

    echo '
        <div class="indexContentModern">
    ';


    if(Setting::Get("ShowTodaysEvents") AND MySQL::Exist("SELECT id FROM zentralausschreibungen WHERE date_begin = ?",'@s',$today))
    {
        $zaVal = MySQL::Row("SELECT * FROM zentralausschreibungen WHERE date_begin = ?",'s',$today);

        echo '
            <h2>Heute, am '.str_replace('�','&auml;',strftime("%A den %d. %B %Y",strtotime($today))).':</h2>
            <hr>
            <div class="todays_event tripple_container">
                <div>
                    <span>Zentralausschreibung:</span>
                </div>
                <div>
                    <h4 style="color: '.Setting::Get("Color".$zaVal['category']).'">'.$zaVal['title_line1'].'</h4>
                    <h4 style="color: '.Setting::Get("Color".$zaVal['category']).'">'.$zaVal['title_line2'].'</h4>
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

    if(CheckPermission("ChangeContent")) echo '<table><tr><td>Rundruf-Nachricht anzeigen:</td><td>'.Checkbox("toggleBroadcast", "toggleBroadcast",Setting::Get("ShowBroadcast"),"window.location.replace('index?toggleBroadcast')").'</td></tr></table>';

    if(Setting::Get("ShowBroadcast"))
    {
        echo '
            <h2>Rundruf</h2>
            <hr>
            <div>
            '.PageContent("5",CheckPermission("ChangeContent"),"index",true).'
            </div>
        ';
    }

    echo '
            <div class="doublecol">
                <article>
                    <div style="position: relative">
                        <a href="/news"><h2>News</h2></a>
                        ';

                        if(CheckPermission("AddNews"))
                        {
                            echo '
                                <div style="position: absolute; top: 0px; left: 70px;">
                                    '.AddButton("/news/neu",false,false,"News-Artikel hinzuf&uuml;gen").'<br>
                                    '.AddButton("/spieler-des-monats/neu",false,false,"\"Spieler des Monats\" hinzuf&uuml;gen").'
                                </div>
                            ';
                        }

                        echo '
                        </div>
                    <hr>

                    <center>
                        <div id="wowslider-container1">
                            <div class="ws_images">
                                <ul>
                                    ';
                                    //RefreshSliderContent();
                                    if(MySQL::Count("SELECT * FROM news WHERE tags = 'Spieler-des-Monats'")>0)
                                    {
                                        $sdm = MySQL::Row("SELECT * FROM news WHERE tags = 'Spieler-des-Monats' ORDER BY id DESC LIMIT 0,1");
                                        $showSDM = Setting::Get("ShowSpielerDesMonats");
                                    }
                                    else $showSDM = false;

                                    $strSQL = "SELECT * FROM news WHERE thumbnail NOT LIKE '' AND tags NOT LIKE 'Spieler-des-Monats' ORDER BY release_date DESC, id DESC LIMIT 0,$sliderLimit";
                                    $sliderImageDataArray = MySQL::Cluster($strSQL);


                                    $sdmPosition = Setting::Get("SDMSliderPosition");
                                    $i=1;
                                    $refreshID = uniqid();


                                    foreach($sliderImageDataArray as $row)
                                    {
                                        if($showSDM=='true' AND $sdmPosition==$i)
                                        {
                                            echo '
                                                <li>
                                                    <a href="/spieler-des-monats/'.$row['article_url'].'"><img src="/content/news/_slideshow/slide_sdm.jpg?'.$refreshID.'" title="'.$sdm['title'].'" id="wows1_99"/></a>
                                                </li>
                                            ';
                                        }

                                        echo '
                                            <li>
                                                <a href="/news/artikel/'.$row['article_url'].'"><img src="/content/news/_slideshow/slide_'.$i.'.jpg?'.$refreshID.'" title="'.$row['title'].'" id="wows1_'.($i-1).'"/></a>
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
                                    foreach($sliderImageDataArray as $row)
                                    {
                                        if($showSDM=='true' AND $sdmPosition==$i)
                                        {
                                            echo '<a href="#" title="'.$sdm['title'].'"><span><img src="/content/news/_slideshow/slide_sdm.jpg?'.$refreshID.'" alt="'.$sdm['title'].'" height="48px"/>99</span></a>';
                                        }

                                        echo '<a href="#" title="'.$row['title'].'"><span><img src="/content/news/_slideshow/slide_'.$i.'.jpg?'.$refreshID.'" alt="'.$row['title'].'" height="48px"/>'.$i++.'</span></a>';
                                    }

                                    echo '
                                </div>
                            </div>
                            <div class="ws_shadow"></div>
                        </div>
                        <script type="text/javascript" src="/js/wowslider.js"></script>
                        <script type="text/javascript" src="/js/slides/'.Setting::Get("SliderAnimation").'"></script>
                        ';

                        if(CheckPermission("ChangeContent")) echo '<a href="index'.str_replace('index','',ThisPage("+regenerateSlider")).'">&#9874; Slider Aktualisieren (Fehlerbehebung etc.)</a>';

                        echo '
                    </center>
                    ';

                    $i=1;
                    foreach($sliderImageDataArray as $row)
                    {
                        if($showSDM=='true' AND $sdmPosition==$i)
                        {
                            echo '
                                <input type="hidden" id="slideTitle99" value="'.$sdm['title'].'">
                                <input type="hidden" id="slideDate99" value="'.str_replace('�','&auml;',strftime("%d. %B %Y",strtotime($sdm['release_date']))).'">
                                <input type="hidden" id="slideLink99" value="'.$sdm['article_url'].'">
                            ';
                        }

                        echo '
                            <input type="hidden" id="slideTitle'.$i.'" value="'.str_replace("&amp;","&",$row['title']).'">
                            <input type="hidden" id="slideDate'.$i.'" value="'.str_replace('�','&auml;',strftime("%d. %B %Y",strtotime($row['release_date']))).'">
                            <input type="hidden" id="slideLink'.$i++.'" value="'.$row['article_url'].'">
                        ';
                    }

                    echo '

                    <script>
                        window.setInterval(function(){
                            CopySliderTitle('.$sliderLimit.','.$showSDM.');
                        }, 500);
                    </script>

                    <h1 class="stagfade6"><a href="" id="sliderLink"><output id="slider_news_title" style="color: #000000; height: 36px; overflow: hidden;"></output></a></h1>
                    <span style="color: #A9A9A9" class="stagfade7"><output id="sliderDate"></output></span>
                </article>
                <aside>
                    <a href="/news/kategorie/Nachwuchs"><h2>Nachwuchs</h2></a>
                    <hr>
                    '.NewsTileSlim("SELECT * FROM news WHERE release_date <= '$today' AND tags LIKE '%Nachwuchs%' ORDER BY release_date DESC, id DESC LIMIT 0,$nachwuchsLimit").'
                </aside>
            </div>
            <br>
            <!--
            <div>
                <hr>
                <center><h3>Entwicklernotiz - Info</h3></center>

                Dies ist die vorl&auml;ufige Endversion der Website. Einige Funktionen und Features sind noch nicht vollst&auml;ndig ausgebaut bzw. ausgereift.<br>
                Im Hintergrund wird weiterhin aktiv an der Seite gearbeitet, um f&uuml;r Sie das beste Nutzererlebnis bereitszustellen.<br>
                W&uuml;nsche, Verbesserungsvorschl&auml;ge, Fehlermeldungen und allgemeine Fragen k&ouml;nnen in k&uuml;rze <a target="_blank" href="https://development.endix.at/de/projekte/badminton-ooe/support"><b>hier</b></a>  eingereicht werden.<br>
                Wir werden uns darum bem&uuml;hen, auf Ihre W&uuml;nsche so schnell wie m&ouml;glich zu reagieren.<br>
                <br>
                Mit freundlichen Gr&uuml;&szlig;en,<br>
                <b>Das Entwicklerteam:</b><br>
                <i>Tobias Hattinger</i><br>
                <i>Paul Luger</i>
                <hr>
                <br>
            </div>
            -->
            <div>
                <a href="/news/kategorie/Top-News"><h3>Neuigkeiten</h3></a>
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
                                    <!-- <div class="tag_overlay">'.ShowTags($row['tags']).'</div> -->
                                </div>
                                <a href="/news/artikel/'.$row['article_url'].'"><b>'.$row['title'].'</b></a>
                                <p></p>
                                <span>
                                '.str_replace('�','&auml;',strftime("%d. %B %Y",strtotime($row['release_date']))).':&nbsp;
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
                        <h3>Meisterschaft</h3>
                        <hr>
                        ';
                        if(CheckPermission("ChangeContent")) echo EditButton("/home/Meisterschaft/bearbeiten");

                        echo '
                            <center>
                                <select onchange="RedirectListLink(this);">
                                    <option value="" selected disabled>&#9135;&#9135; Tabelle Ausw&auml;hlen &#9135;&#9135;</option>
                                    ';

                                    $strSQL = "SELECT * FROM home_tiles WHERE tile = 'Meisterschaft' ORDER BY id ASC";
                                    $rs=mysqli_query($link,$strSQL);
                                    while($row=mysqli_fetch_assoc($rs)) echo '<option value="'.$row['value'].'">'.$row['text'].'</option>';

                                    echo '
                                </select>
                            </center>
                        ';

                        echo MySQL::Scalar("SELECT text FROM page_content WHERE page = 'index' AND paragraph_index = '1'");

                        echo '
                    </div>
                    <div>
                        <h3>Ranglisten</h3>
                        <hr>
                        ';
                        if(CheckPermission("ChangeContent")) echo EditButton("/home/Ranglisten/bearbeiten");

                        echo '
                        <br>
                        <center>
                            <select onchange="RedirectListLink(this);">
                                <option value="" selected disabled>&#9135;&#9135; Rangliste Ausw&auml;hlen &#9135;&#9135;</option>
                                ';

                                $strSQL = "SELECT * FROM home_tiles WHERE tile = 'Ranglisten' ORDER BY id ASC";
                                $rs=mysqli_query($link,$strSQL);
                                while($row=mysqli_fetch_assoc($rs)) echo '<option value="'.$row['value'].'">'.$row['text'].'</option>';

                                echo '
                            </select>
                            <br><br>
                            <img src="/content/ooebv.png" alt="" width="100px;"/>
                        </center>
                    </div>
                    <div>
                        <h3>Links</h3>
                        <hr>
                        ';
                        if(CheckPermission("ChangeContent")) echo EditButton("/home/Links/bearbeiten").'<br>';

                        echo MySQL::Scalar("SELECT text FROM page_content WHERE page = 'index' AND paragraph_index = '3'");

                        echo '
                    </div>
                </div>
                <br><br><br>
                <div class="double_container">

                    <div style="text-align: center; min-width: 330px;">
                        <a href="/kalender"><h3 style="text-align: left;">Termine</h3></a>
                        <hr>
                        <iframe src="/graphic_calendar_thumb" frameborder="0" style="height: 250px; width: 320px;" scrolling="no"></iframe>
                        <h4 style="text-align: left;">Anstehende Ereignisse:</h4>
                        <ul>
                        ';

                        $todayAgenda = date("Y-m-").'1';

                        $strSQL = "SELECT id AS isAgenda, NULL AS isZA, title, date_begin FROM agenda
                        WHERE date_begin >= '$todayAgenda'
                        UNION ALL
                        SELECT NULL AS isAgenda, id AS isZA, CONCAT_WS(' ', title_line1, title_line2) AS title, date_begin FROM zentralausschreibungen
                        WHERE date_begin >= '$todayAgenda'
                        ORDER BY date_begin ASC
                        LIMIT 0,3";
                        $rs=mysqli_query($link,$strSQL);
                        while($row=mysqli_fetch_assoc($rs))
                        {
                            if($row['isZA']!=NULL) $link = '/kalender/event/ZA'.$row['isZA'].'/'.$row['date_begin'];
                            else $link = '/kalender/event/AG'.$row['isAgenda'].'/'.$row['date_begin'];

                            echo '<a href="'.$link.'"><li style="text-align: left;"><b>'.(($row['date_begin']==$today) ? 'Heute: ' : (str_replace('�','&auml;',strftime("%d. %b",strtotime($row['date_begin']))).': ')).'</b> '.$row['title'].'</li></a>';
                        }

                        echo '
                        </ul>
                    </div>
                    <div>
                        <h3><!--<img src="/content/rss.png" alt="" style="width:20px; height: 20px; margin-bottom:-2px; margin-right: 5px;"/>-->&Ouml;BV News</h3>
                        <hr>
                        <center>
                            <div style="max-width: 500px; min-width: 300px;">
                                <script language="javascript" src="http://www.badminton.at/files/rss/badminton_news.php"></script>
                            </div>
                        </center>
                    </div>
                </div>
                <br><br><br>
                ';

                if(CheckPermission("ChangeContent")) echo '<table><tr><td>Veranstaltungen-Feld anzeigen:</td><td>'.Checkbox("toggleEvents", "toggleEvents",Setting::Get("ShowHomeEvents"),"window.location.replace('index?toggleEvents')").'</td></tr></table>';

                if(Setting::Get("ShowHomeEvents"))
                {
                    echo '
                        <div>
                            <h3>Veranstaltungen</h3>
                            <hr>
                            '.PageContent("6",CheckPermission("ChangeContent"),"index",true).'
                        </div>
                    ';
                }

                echo '

                <!--
                <br><br><br><br><br><br>
                <hr>
                <hr>
                <hr>

                <h3>Development</h3>
                <hr>
                <ul>
                    <li><u><b>Development-Tools</b></u></li>
                    <li><a target="_blank" href="https://e64980-phpmyadmin.services.easyname.eu">MySQL Datenbank</a></li>
                    <li><a href="__tempform">Temp-Forms</a></li>
                    <li><a href="__sources">Code-Sources</a></li>
                    <li><a href="__templates">Templates</a></li>
                    <li><u><b>Demos/Training</b></u></li>
                    <li><a href="__formoverview">PHP-Forms Overview</a></li>
                </ul>
                -->
            </div>
        </div>
    ';


    include("footer.php");
?>