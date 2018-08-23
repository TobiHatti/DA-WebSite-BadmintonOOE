<?php
    require("header.php");
    PageTitle("News-Archiv");

    $year = (isset($_GET['jahr'])) ? $_GET['jahr'] : date("Y");


    echo '<h1 class="stagfade1">News-Archiv</h1>';

    echo '
        <center>
            <div class="archive_year_selection">
                <a onclick="ListMoveRight();"><img src="/content/left.png" alt=""/></a>
                <div class="year_container">
                    <div class="year_slider" id="YearSlider">

                        ';
                        for($y = date("Y"); $y >= date("Y")-10;$y--)
                        {
                            echo '<a href="#">'.(($y == $year) ? ('<span>'.$y.'</span>') : $y ).'</a>';
                        }
                        echo '
                    </div>

                </div>
                <a onclick="ListMoveLeft();"><img src="/content/right.png" alt=""/></a>

                <input type="hidden" id="offsetIdx" value="0"/>
                <input type="hidden" id="scrollWidth" value="'.(100*2).'"/>
            </div>
            <hr style="margin-bottom:3px;">
            <div class="archive_month_selection">
                <a href="#">J&auml;nner</a>
                <a href="#">Februar</a>
                <a href="#">M&auml;rz</a>
                <a href="#">April</a>
                <a href="#">Mai</a>
                <a href="#">Juni</a>
                <a href="#">Juli</a>
                <a href="#">August</a>
                <a href="#">September</a>
                <a href="#">Oktober</a>
                <a href="#">November</a>
                <a href="#">Dezember</a>
            </div>
            <hr style="margin-top:3px;">


            <br><br>

            <iframe src="news-archiv-content" frameborder="1">
            </iframe>
        </center>

    ';

    include("footer.php");
?>