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
                            echo '<a onclick="ArchiveSelectYear('.$y.');">'.(($y == $year) ? ('<span>'.$y.'</span>') : $y ).'</a>';
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
                <a onclick="ArchiveSelectMonth(01);">J&auml;nner</a>
                <a onclick="ArchiveSelectMonth(02);">Februar</a>
                <a onclick="ArchiveSelectMonth(03);">M&auml;rz</a>
                <a onclick="ArchiveSelectMonth(04);">April</a>
                <a onclick="ArchiveSelectMonth(05);">Mai</a>
                <a onclick="ArchiveSelectMonth(06);">Juni</a>
                <a onclick="ArchiveSelectMonth(07);">Juli</a>
                <a onclick="ArchiveSelectMonth(08);">August</a>
                <a onclick="ArchiveSelectMonth(09);">September</a>
                <a onclick="ArchiveSelectMonth(10);">Oktober</a>
                <a onclick="ArchiveSelectMonth(11);">November</a>
                <a onclick="ArchiveSelectMonth(12);">Dezember</a>
            </div>

            <input type="hidden" id="selectedYear" value="'.date("Y").'"/>
            <input type="hidden" id="selectedMonth" value="'.date("m").'"/>
            <hr style="margin-top:3px;">


            <img src="/content/loadersprite.gif" alt="" style="height: 80px; opacity: 0;" id="loaderSprite" class="ease50"/>

            <table style="float: right;">
                <tr>
                    <td style="padding-top: 20px;">Detailansicht</td>
                    <td>'.Checkbox("","showDetail",0,"UpdateArchiveFrame();").'</td>
                </tr>
            </table>
            <iframe style="margin-top: -50px;" onload="ResizeIframe(this)" src="news-archiv-content?year='.date("Y").'&month='.date("m").'&detail=0" class="news_archive_iframe" id="archiveFrame">
            </iframe>
        </center>

    ';

    include("footer.php");
?>