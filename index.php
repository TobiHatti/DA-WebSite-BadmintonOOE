<?php
    require("header.php");
    PageTitle("Home");

    $video_match_badminton='<iframe width="425" height="349" src="https://www.youtube.com/watch?v=_FpDVKgfTHs" frameborder="0" allowfullscreen></iframe>"';

    echo '
    <div class="doublecol">
        <article>
            <center>
                <div style="width: 480px; height:240px; border:1px solid #0066CC;">

                    <div id="wowslider-container1">
                        <div class="ws_images">
                            <ul>
                                <li><img src="content/federall_sample2.jpg" alt="federall_sample2" title="Der Federball" id="wows1_0"/></li>
                                <li><img src="content/federball_sample1.jpg" alt="html slider" title="Der Federball" id="wows1_1"/></li>
                                <li><img src="content/federball_sample3.jpg" alt="federball_sample3" title="Der Federball" id="wows1_2"/></li>
                            </ul>
                        </div>
                        <div class="ws_bullets">
                            <div>
                                <a href="#" title="federall_sample2"><span><img src="data1/tooltips/federall_sample2.jpg" alt="federall_sample2"/>1</span></a>
                                <a href="#" title="federball_sample1"><span><img src="data1/tooltips/federball_sample1.jpg" alt="federball_sample1"/>2</span></a>
                                <a href="#" title="federball_sample3"><span><img src="data1/tooltips/federball_sample3.jpg" alt="federball_sample3"/>3</span></a>
                            </div>
                        </div>
                        <div class="ws_shadow"></div>
                    </div>
                    <script type="text/javascript" src="/js/wowslider.js"></script>
                    <script type="text/javascript" src="/js/script.js"></script>

                </div>
            </center>

            <hr>
            <br>
            <div class="home_news_container">
            ';

            $today = date("Y-m-d");
            echo NewsTile("SELECT article, article_url, thumbnail, release_date, title, tags FROM news WHERE release_date <= '$today' ORDER BY release_date DESC LIMIT 0,3");

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
    ';

    include("footer.php");
?>