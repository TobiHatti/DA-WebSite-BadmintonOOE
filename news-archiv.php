<?php
    require("header.php");
    PageTitle("News-Archiv");

    echo '<h1 class="stagfade1">News-Archiv</h1>';

    echo '
        <center>
            <div class="archive_year_selection">
                <a onclick="ListMoveRight();"><img src="/content/left.png" alt=""/></a>
                <div class="year_container">
                    <div class="year_slider" id="YearSlider">

                        ';

                        $first_article = explode('-',SQL::Scalar("SELECT release_date FROM news ORDER BY release_date ASC"));
                        for($y = date("Y"); $y >= $first_article[0];$y--)
                        {
                            echo '<a onclick="ArchiveSelectYear('.$y.');" id="year'.$y.'">'.$y.'</a>';

                            $m01 = false;
                            $m02 = false;
                            $m03 = false;
                            $m04 = false;
                            $m05 = false;
                            $m06 = false;
                            $m07 = false;
                            $m08 = false;
                            $m09 = false;
                            $m10 = false;
                            $m11 = false;
                            $m12 = false;

                            $strSQL = "SELECT DISTINCT release_date FROM news WHERE release_date LIKE '$y-%'";
                            $rs=mysqli_query($link,$strSQL);
                            while($row=mysqli_fetch_assoc($rs))
                            {
                                if(SubStringFind($row['release_date'],'-01-')) $m01 = true;
                                else if(SubStringFind($row['release_date'],'-02-')) $m02 = true;
                                else if(SubStringFind($row['release_date'],'-03-')) $m03 = true;
                                else if(SubStringFind($row['release_date'],'-04-')) $m04 = true;
                                else if(SubStringFind($row['release_date'],'-05-')) $m05 = true;
                                else if(SubStringFind($row['release_date'],'-06-')) $m06 = true;
                                else if(SubStringFind($row['release_date'],'-07-')) $m07 = true;
                                else if(SubStringFind($row['release_date'],'-08-')) $m08 = true;
                                else if(SubStringFind($row['release_date'],'-09-')) $m09 = true;
                                else if(SubStringFind($row['release_date'],'-10-')) $m10 = true;
                                else if(SubStringFind($row['release_date'],'-11-')) $m11 = true;
                                else if(SubStringFind($row['release_date'],'-12-')) $m12 = true;
                            }

                            $validMonths = '';

                            $validMonths .= $m01 ? '|01|' : '';
                            $validMonths .= $m02 ? '|02|' : '';
                            $validMonths .= $m03 ? '|03|' : '';
                            $validMonths .= $m04 ? '|04|' : '';
                            $validMonths .= $m05 ? '|05|' : '';
                            $validMonths .= $m06 ? '|06|' : '';
                            $validMonths .= $m07 ? '|07|' : '';
                            $validMonths .= $m08 ? '|08|' : '';
                            $validMonths .= $m09 ? '|09|' : '';
                            $validMonths .= $m10 ? '|10|' : '';
                            $validMonths .= $m11 ? '|11|' : '';
                            $validMonths .= $m12 ? '|12|' : '';

                            echo '<input type="hidden" id="valMonth_'.$y.'" value="'.$validMonths.'">';
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
                <a class="" id="month1">J&auml;nner</a>
                <a class="" id="month2">Februar</a>
                <a class="" id="month3">M&auml;rz</a>
                <a class="" id="month4">April</a>
                <a class="" id="month5">Mai</a>
                <a class="" id="month6">Juni</a>
                <a class="" id="month7">Juli</a>
                <a class="" id="month8">August</a>
                <a class="" id="month9">September</a>
                <a class="" id="month10">Oktober</a>
                <a class="" id="month11">November</a>
                <a class="" id="month12">Dezember</a>
            </div>

            <input type="hidden" id="selectedYear" value="'.date("Y").'"/>
            <input type="hidden" id="selectedMonth" value="'.date("m").'"/>


            <script>
                window.onload = function () {
                    ArchiveSelectYear('.date("Y").');
                    ArchiveSelectMonth('.date("m").');
                    UpdateArchiveFrame();
                }
            </script>

            <hr style="margin-top:3px;">

            ';

            if(Setting::Get("EnablePreloaderArchive"))
            {
                echo '<img src="/content/loadersprite.gif" alt="" style="height: 80px; opacity: 0;" id="loaderSprite" class="ease50"/>';
            }

            echo '

            <table style="float: right;">
                <tr>
                    <td>Liste / Detail</td>
                    <td>'.Togglebox("","showDetail",0,"UpdateArchiveFrame();").'</td>
                </tr>
            </table>
            <iframe style="margin-top: -50px;" onload="ResizeIframe(this)" src="news-archiv-content?year='.date("Y").'&month='.date("m").'&detail=0" class="news_archive_iframe" id="archiveFrame">
            </iframe>
        </center>

    ';

    include("footer.php");
?>