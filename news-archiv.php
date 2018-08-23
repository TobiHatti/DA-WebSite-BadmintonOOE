<?php
    require("header.php");
    PageTitle("News-Archiv");

    echo '<h1 class="stagfade1">News-Archiv</h1>';

    echo '
        <center>
        <div class="archive_year_selection">
            <button onclick="ListMoveRight();"> < </button>
            <div class="year_container">
                <div class="year_slider" id="YearSlider">

                    ';

                    for($year = date("Y"); $year >= date("Y")-10;$year--)
                    {
                        echo '<a href="#">'.$year.'</a>';
                    }

                    echo '
                </div>

            </div>
            <button onclick="ListMoveLeft();"> > </button>

            <input type="hidden" id="offsetIdx" value="0"/>
            <input type="hidden" id="scrollWidth" value="'.(100*2).'"/>
        </div>
        </center>



        Note: change following content in iframe

    ';

    include("footer.php");
?>