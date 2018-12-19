<?php

    require("header.php");
    PageTitle("Kalender");

    if(isset($_POST['add_termin']))
    {
        $terminName = $_POST['termin_titel'];
        $description = $_POST['description_date'];
        $termin_date_start=$_POST['date_termin_start'];
        $termin_date_end=(isset($_POST['toggleMultiday']) ? $_POST['date_termin_end'] : '0000-00-00');
        $isTimespan = (isset($_POST['toggleMultiday']) ? 1 : 0);
        $termin_place=$_POST['place'];
        $termin_time=$_POST['time'];
        $termin_kategorie=$_POST['kategorie'];

        MySQL::NonQuery("INSERT INTO agenda (id, titel, description, date_begin, date_end, isTimespan, place, time,kategorie) VALUES ('',?,?,?,?,?,?,?,?)",'@s',$terminName,$description,$termin_date_start,$termin_date_end,$isTimespan,$termin_place,$termin_time,$termin_kategorie);

        Redirect("/kalender");
        die();

    }

    if(isset($_POST['update_termin']))
    {
        $id = $_POST['update_termin'];
        $terminName = $_POST['termin_titel'];
        $description = $_POST['description_date'];
        $termin_date=$_POST['date_termin'];
        $termin_place=$_POST['place'];
        $termin_time=$_POST['time'];
        $termin_kategorie=$_POST['kategorie'];

        $strSQL = "UPDATE agenda SET
        titel = ?,
        description = ?,
        date = ?,
        place = ?,
        time = ?,
        kategorie = ?
        WHERE id = ?
        ";

        MySQL::NonQuery($strSQL,'@s',$terminName,$description,$termin_date,$termin_place,$termin_time,$termin_kategorie,$id);

        Redirect("/kalender");
        die();
    }

    if(isset($_POST['export_csv']))
    {
        if(StartsWith($_POST['export_csv'], 'MZA'))
        {
            $multiple = str_replace('MZA','',$_POST['export_csv']);
            $path = ExportCSVAgenda("zentralausschreibungen","",$multiple);
        }
        else if(StartsWith($_POST['export_csv'], 'MAG'))
        {
            $multiple = str_replace('MAG','',$_POST['export_csv']);
            $path = ExportCSVAgenda("agenda","",$multiple);
        }
        else if(StartsWith($_POST['export_csv'], 'ZA'))
        {
            $id = str_replace('ZA','',$_POST['export_csv']);
            $path = ExportCSVAgenda("zentralausschreibungen",$id);
        }
        else if(StartsWith($_POST['export_csv'], 'AG'))
        {
            $id = str_replace('AG','',$_POST['export_csv']);
            $path = ExportCSVAgenda("agenda",$id);
        }

        Redirect("/forceDownload?file=".urlencode($path));
    }

    if(isset($_POST['export_ics']))
    {

    }


    if(isset($_GET['neu']) AND CheckPermission("AddDate"))
    {
        echo'
            <h2 class="stagfade1">Neuer Termin</h2>
            <hr>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data" class="stagfade2">
                <table>
                    <tr>
                        <td class="ta_r">Titel</td>
                        <td><input type="text" class="cel_l" placeholder="Titel" name="termin_titel" required/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Beschreibung</td>
                        <td><textarea class="cel_l" name="description_date" placeholder="Beschreibung" style="resize: vertical;" require></textarea></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Datum <output id="outBeginText" style="display:none;">Start</output></td>
                        <td><input type="date" class="cel_l" name="date_termin_start" required/></td>
                        <td>'.Checkbox("toggleMultiday", "toggleMultiday", false,"ShowHideTableRow(this,'rowEndDate'); ShowHideElement(this,'outBeginText')").'</td>
                        <td>Mehrt&auml;gig</td>
                    </tr>
                    <tr id="rowEndDate" style="display:none;">
                        <td class="ta_r">Datum<br>Ende</td>
                        <td><input type="date" class="cel_l" name="date_termin_end"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Ort</td>
                        <td><input type="text" class="cel_l" placeholder="Ort" name="place"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Uhrzeit</td>
                        <td><input type="time" class="cel_l" name="time"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Kategorie</td>
                        <td>
                        <select class="cel_l" name="kategorie" id="classKat">
                            <option value="" disabled selected>--- Kategorie ausw&auml;hlen ---</option>
                            <option value="">Anderes</option>
                            <option value="Landesmeisterschaft" style="color: '.Setting::Get("ColorLandesmeisterschaft").'">Landesmeisterschaft</option>
                            <option value="Doppelturnier" style="color: '.Setting::Get("ColorDoppelturnier").'">Doppelturnier</option>
                            <option value="Nachwuchs" style="color: '.Setting::Get("ColorNachwuchs").'">Nachwuchs</option>
                            <option value="SchuelerJugend" style="color: '.Setting::Get("ColorSchuelerJugend").'">Sch&uuml;ler/Jugend</option>
                            <option value="Senioren" style="color: '.Setting::Get("ColorSenioren").'">Senioren</option>
                            <option value="Training" style="color: '.Setting::Get("ColorTraining").'">Training</option>
                        </select>
                        </td>
                    </tr>
                </table>

                <br>
                <br>
                <button type="submit" name="add_termin" class="stagfade3">Termin hinzuf&uuml;gen</button>

            </form>
        ';

    }
    else
    {
        $frameExtension = (isset($_GET['event'])) ? ('?datum='.$_GET['datum'].'#calenderInfo'.$_GET['event']) : ((isset($_GET['datum'])) ? ('?datum='.$_GET['datum']) : '');
        if(isset($_GET['editSC']))  $frameExtension = '?datum='.$_GET['datum'].'&edit='.$_GET['editSC'].'#calenderInfo'.$_GET['event'];



        echo '<div style="float:right;"><table><tr><td>Liste / Kalender</td><td>'.Togglebox("","changeListStyle",1,"ChangeCalenderStyle();","toggleCalendar").'</td></tr></table></div><br>';



        echo'
            <div id="CalenderList" style="display:none;">
            <h2>Liste der Termine</h2>
            ';

            if(CheckPermission("AddDate"))
            {
                echo AddButton(ThisPage("+neu")).' oder <a href="/kalender-import">Termine Importieren</a><br><br>';
            }

            echo '
            <hr>
        ';
            $entriesPerPage = Setting::Get("PagerSizeCalendar");
            $offset = ((isset($_GET['page'])) ? $_GET['page']-1 : 0 ) * $entriesPerPage;
            $today = date("Y-m-d");
            $strSQL = "SELECT id,date_begin,titel,description,kategorie FROM agenda WHERE date_begin >= '$today' UNION ALL SELECT id,date_begin,CONCAT_WS(' ', title_line1, title_line2) AS titel, NULL AS description,kategorie FROM zentralausschreibungen WHERE date_begin >= '$today' ORDER BY date_begin ASC LIMIT $offset,$entriesPerPage";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs))
            {
                $isZA = ($row['description']==NULL) ? true : false;

                echo'
                    <div class="calendar_list" style="border-left-color: '.Setting::Get("Color".$row['kategorie']).'">
                        '.($isZA ? '<span style="color: #696969"><i>Zentralausschreibung</i></span>' : '').'
                        <a onclick="window.sessionStorage.setItem(\'toggleCalendar\',1);" href="/kalender/event/'.($isZA ? 'ZA' : 'AG').$row['id'].'/'.$row['date_begin'].'"><h4 style="margin:0">'.$row['titel'].'</h4></a>
                        <a onclick="window.sessionStorage.setItem(\'toggleCalendar\',1);" href="/kalender/datum/'.$row['date_begin'].'">'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['date_begin']))).'</span></a>
                        '.($isZA ? '' : ('<p>'.$row['description'].'</p>')).'
                    </div>
                ';
            }

            echo Pager(str_replace("LIMIT $offset,$entriesPerPage",'',$strSQL),$entriesPerPage);



        echo '
        </div>
        <div id="CalenderGraphic" style="display:block;">
            <h2>Kalender</h2>
            ';

            if(CheckPermission("AddDate"))
            {
                echo AddButton(ThisPage("+neu")).' oder <a href="/kalender-import">Termine Importieren</a><br><br>';
            }

            echo '
            <iframe src="/graphic_calendar'.$frameExtension.'" frameborder="0" onload="ResizeIframe(this);" class="calender_iframe"></iframe>
        </div>
        ';


    }

include("footer.php");

?>