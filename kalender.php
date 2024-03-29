<?php

    require("header.php");
    PageTitle("Kalender");


    if(isset($_GET['addCategory']))
    {
        if(!isset($_SESSION['calenderSections'])) $_SESSION['calenderSections'] = '';

        if(!SubStringFind($_SESSION['calenderSections'],'|'.$_GET['addCategory'].'|')) $_SESSION['calenderSections'] = $_SESSION['calenderSections'].'|'.$_GET['addCategory'].'|';

        if($_GET['addCategory'] == 'alle') $_SESSION['calenderSections'] = '';

        Redirect(ThisPage("!addCategory"));
        die();
    }

    if(isset($_GET['removeCategory']))
    {
        if(!isset($_SESSION['calenderSections'])) $_SESSION['calenderSections'] = '';

        $_SESSION['calenderSections'] = str_replace('|'.$_GET['removeCategory'].'|','',$_SESSION['calenderSections']);

        Redirect(ThisPage("!removeCategory"));
        die();
    }

    if(isset($_POST['add_termin']))
    {
        $terminName = $_POST['termin_titel'];
        $description = $_POST['description_date'];
        $termin_date_start=$_POST['date_termin_start'];
        $termin_date_end=(isset($_POST['toggleMultiday']) ? $_POST['date_termin_end'] : '0000-00-00');
        $isTimespanDate = (isset($_POST['toggleMultiday']) ? 1 : 0);
        $isTimespanTime = (isset($_POST['toggleMultiday']) ? 1 : 0);
        $termin_location=$_POST['location'];
        $termin_time_start=$_POST['time_start'];
        $termin_time_end=$_POST['time_end'];
        $termin_kategorie=$_POST['kategorie'];
        $termin_responsible=$_POST['responsible'];
        $termin_participant=$_POST['participant'];
        $termin_additionalInfo=$_POST['additionalInfo'];


        MySQL::NonQuery("INSERT INTO agenda (title, description, date_begin, date_end, isTimespan, location, time_start, time_end,category, participant, responsible, additional_info) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",'@s',$terminName,$description,$termin_date_start,$termin_date_end,$isTimespanDate,$termin_location,$termin_time_start,$termin_time_end,$termin_kategorie,$termin_participant,$termin_responsible,$termin_additionalInfo);

        Redirect("/kalender");
        die();

    }

    if(isset($_POST['update_termin']))
    {
        $id = $_POST['update_termin'];
        $terminName = $_POST['termin_titel'];
        $description = $_POST['description_date'];
        $termin_date_start=$_POST['date_termin_start'];
        $termin_date_end=(isset($_POST['toggleMultiday']) ? $_POST['date_termin_end'] : '0000-00-00');
        $isTimespanDate = (isset($_POST['toggleMultiday']) ? 1 : 0);
        $isTimespanTime = (isset($_POST['toggleMultiday']) ? 1 : 0);
        $termin_location=$_POST['location'];
        $termin_time_start=$_POST['time_start'];
        $termin_time_end=$_POST['time_end'];
        $termin_kategorie=$_POST['kategorie'];
        $termin_responsible=$_POST['responsible'];
        $termin_participant=$_POST['participant'];
        $termin_additionalInfo=$_POST['additionalInfo'];

        $strSQL = "UPDATE agenda SET
        title = ?,
        description = ?,
        date_begin = ?,
        date_end = ?,
        isTimespan = ?,
        location = ?,
        time_start = ?,
        time_end = ?,
        category = ?,
        participant = ?,
        responsible = ?,
        additional_info = ?
        WHERE id = ?
        ";

        MySQL::NonQuery($strSQL,'@s',$terminName,$description,$termin_date_start,$termin_date_end,$isTimespanDate,$termin_location,$termin_time_start,$termin_time_end,$termin_kategorie,$termin_participant,$termin_responsible,$termin_additionalInfo,$id);

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
                        <td><input type="text" class="cel_l" placeholder="Titel..." name="termin_titel" required/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Beschreibung</td>
                        <td><textarea class="cel_l" name="description_date" placeholder="Beschreibung..." style="resize: vertical;"></textarea></td>
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
                        <td><input type="text" class="cel_l" placeholder="Ort..." name="location"/></td>
                    </tr>

                    <tr>
                        <td class="ta_r">Uhrzeit <output id="outBeginTimeText" style="display:none;">Start</output></td>
                        <td><input type="time" class="cel_l" name="time_start"/></td>
                        <td>'.Checkbox("toggleMultiTimespan", "toggleMultiTimespan", false,"ShowHideTableRow(this,'rowEndTime'); ShowHideElement(this,'outBeginTimeText')").'</td>
                        <td>Zeitspanne</td>
                    </tr>
                    <tr id="rowEndTime" style="display:none;">
                        <td class="ta_r">Uhrzeit<br>Ende</td>
                        <td><input type="time" class="cel_l" name="time_end"/></td>
                    </tr>

                    <tr>
                        <td class="ta_r">Kategorie</td>
                        <td>
                        <select class="cel_l" name="kategorie" id="classKat" required>
                            <option value="" disabled selected>--- Kategorie ausw&auml;hlen ---</option>
                            <option value="">Anderes</option>
                            <option value="OEBVInternational" style="color: '.Setting::Get("ColorOEBVInternational").'">&Ouml;BV / International</option>
                            <option value="OEBV" style="color: '.Setting::Get("ColorOEBV").'">&Ouml;BV</option>
                            <option value="LV" style="color: '.Setting::Get("ColorLV").'">LV</option>
                            <option value="Landesmeisterschaft" style="color: '.Setting::Get("ColorLandesmeisterschaft").'">Landesmeisterschaft</option>
                            <option value="Doppelturnier" style="color: '.Setting::Get("ColorDoppelturnier").'">Doppelturnier</option>
                            <option value="Nachwuchs" style="color: '.Setting::Get("ColorNachwuchs").'">Nachwuchs</option>
                            <option value="SchuelerJugend" style="color: '.Setting::Get("ColorSchuelerJugend").'">Sch&uuml;ler/Jugend</option>
                            <option value="Senioren" style="color: '.Setting::Get("ColorSenioren").'">Senioren</option>
                            <option value="Training" style="color: '.Setting::Get("ColorTraining").'">Training</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="ta_r">Verantwortlicher</td>
                        <td><input type="text" class="cel_l" placeholder="Verantwortliche Person..." name="responsible"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Teilnehmer</td>
                        <td><input type="text" class="cel_l" placeholder="Teilnehmer..." name="participant"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Zusatzinformationen</td>
                        <td><textarea class="cel_l" name="additionalInfo" placeholder="Zusatzinformationen..." style="resize: vertical;"></textarea></td>
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
        //$frameExtension = (isset($_GET['event'])) ? ('?datum='.$_GET['datum'].'#calenderInfo'.$_GET['event']) : ((isset($_GET['datum'])) ? ('?datum='.$_GET['datum']) : '');

        $frameExtension = '';

        if(isset($_GET['category']) and $_GET['category'] != 'alle') $frameExtension = "?category=".$_GET['category'];

        if(isset($_GET['event']))
        {
            $frameExtension = '?datum='.$_GET['datum'].'#calenderInfo'.$_GET['event'];

            if(isset($_GET['category'])) $frameExtension .= "&category=".$_GET['category'];
        }
        else if(isset($_GET['datum']))
        {
            $frameExtension = '?datum='.$_GET['datum'];

            if(isset($_GET['category'])) $frameExtension .= "&category=".$_GET['category'];
        }



        if(isset($_GET['editSC']))  $frameExtension = '?datum='.$_GET['datum'].'&edit='.$_GET['editSC'].'#calenderInfoAG-'.$_GET['editSC'];


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
                <div style="float: right;">
                    <select onchange="RedirectSelectBox(this,\'/kalender/\');">
                        <option value="" disabled selected>--- Kategorie ausw&auml;hlen ---</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'OEBVInternational') ? 'selected' : '').' value="OEBVInternational" style="color: '.Setting::Get("ColorOEBVInternational").'">&Ouml;BV-International</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'OEBV') ? 'selected' : '').' value="OEBV" style="color: '.Setting::Get("ColorOEBV").'">&Ouml;BV</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'LV') ? 'selected' : '').' value="LV" style="color: '.Setting::Get("ColorLV").'">LV</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'Landesmeisterschaft') ? 'selected' : '').' value="Landesmeisterschaft" style="color: '.Setting::Get("ColorLandesmeisterschaft").'">Landesmeisterschaft</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'Doppelturnier') ? 'selected' : '').' value="Doppelturnier" style="color: '.Setting::Get("ColorDoppelturnier").'">Doppelturnier</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'Nachwuchs') ? 'selected' : '').' value="Nachwuchs" style="color: '.Setting::Get("ColorNachwuchs").'">Nachwuchs</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'SchuelerJugend') ? 'selected' : '').' value="SchuelerJugend" style="color: '.Setting::Get("ColorSchuelerJugend").'">Sch&uuml;ler/Jugend</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'Senioren') ? 'selected' : '').' value="Senioren" style="color: '.Setting::Get("ColorSenioren").'">Senioren</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'Training') ? 'selected' : '').' value="Training" style="color: '.Setting::Get("ColorTraining").'">Training</option>
                    </select>
                </div>
            ';

            echo '
            <hr>
        ';
            $entriesPerPage = Setting::Get("PagerSizeCalendar");
            $offset = ((isset($_GET['page'])) ? $_GET['page']-1 : 0 ) * $entriesPerPage;
            $today = date("Y-m-d");

            if(isset($_GET['category'])) $sqlExtension = "AND category = '".$_GET['category']."'";
            else $sqlExtension = "";

            $strSQL = "SELECT id,date_begin,title,description,category,id AS isAG,NULL AS isZA FROM agenda WHERE date_begin >= '$today' $sqlExtension UNION ALL SELECT id,date_begin,CONCAT_WS(' ', title_line1, title_line2) AS title, NULL AS description,category,NULL AS isAG,id AS isZA FROM zentralausschreibungen WHERE date_begin >= '$today' $sqlExtension ORDER BY date_begin ASC LIMIT $offset,$entriesPerPage";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs))
            {
                $isZA = ($row['isZA']!=NULL) ? true : false;

                echo'
                    <div class="calendar_list" style="border-left-color: '.Setting::Get("Color".$row['category']).'">
                        '.($isZA ? '<span style="color: #696969"><i>Zentralausschreibung</i></span>' : '').'
                        <a onclick="window.sessionStorage.setItem(\'toggleCalendar\',1);" href="/kalender/event/'.($isZA ? 'ZA' : 'AG').$row['id'].'/'.$row['date_begin'].'"><h4 style="margin:0">'.$row['title'].'</h4></a>
                        <a onclick="window.sessionStorage.setItem(\'toggleCalendar\',1);" href="/kalender/datum/'.$row['date_begin'].'">'.str_replace('�','&auml;',strftime("%d. %B %Y",strtotime($row['date_begin']))).'</span></a>
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
                <div style="float: right;">
                    <select onchange="RedirectSelectBox(this,\'/kalender?addCategory=\');">
                        <option value="" disabled selected>--- Kategorie ausw&auml;hlen ---</option>
                        <option value="alle">Alle</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'OEBVInternational') ? 'selected' : '').' value="OEBVInternational" style="color: '.Setting::Get("ColorOEBVInternational").'">&Ouml;BV-International</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'OEBV') ? 'selected' : '').' value="OEBV" style="color: '.Setting::Get("ColorOEBV").'">&Ouml;BV</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'LV') ? 'selected' : '').' value="LV" style="color: '.Setting::Get("ColorLV").'">LV</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'Landesmeisterschaft') ? 'selected' : '').' value="Landesmeisterschaft" style="color: '.Setting::Get("ColorLandesmeisterschaft").'">Landesmeisterschaft</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'Doppelturnier') ? 'selected' : '').' value="Doppelturnier" style="color: '.Setting::Get("ColorDoppelturnier").'">Doppelturnier</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'Nachwuchs') ? 'selected' : '').' value="Nachwuchs" style="color: '.Setting::Get("ColorNachwuchs").'">Nachwuchs</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'SchuelerJugend') ? 'selected' : '').' value="SchuelerJugend" style="color: '.Setting::Get("ColorSchuelerJugend").'">Sch&uuml;ler/Jugend</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'Senioren') ? 'selected' : '').' value="Senioren" style="color: '.Setting::Get("ColorSenioren").'">Senioren</option>
                        <option '.((isset($_GET['category']) AND $_GET['category'] == 'Training') ? 'selected' : '').' value="Training" style="color: '.Setting::Get("ColorTraining").'">Training</option>
                    </select>
                </div>


            ';





            echo '

                <style>

                .calendarOptionBox{

                    border: 1px solid;
                    border-radius: 5px;
                    padding: 3px 10px;
                    margin: 0 5px;
                }

                </style>
            ';

            if(!isset($_SESSION['calenderSections'])) $_SESSION['calenderSections'] = '';

            if(SubStringFind($_SESSION['calenderSections'],'|OEBVInternational|')) echo '<span class="calendarOptionBox" style="border-color: '.Setting::Get("ColorOEBVInternational").'"><a href="'.ThisPage("+removeCategory=OEBVInternational").'">X</a> &Ouml;BV-International</span>';
            if(SubStringFind($_SESSION['calenderSections'],'|OEBV|')) echo '<span class="calendarOptionBox" style="border-color: '.Setting::Get("ColorOEBV").'"><a href="'.ThisPage("+removeCategory=OEBV").'">X</a> &Ouml;BV</span>';
            if(SubStringFind($_SESSION['calenderSections'],'|LV|')) echo '<span class="calendarOptionBox" style="border-color: '.Setting::Get("ColorLV").'"><a href="'.ThisPage("+removeCategory=LV").'">X</a> LV</span>';
            if(SubStringFind($_SESSION['calenderSections'],'|Landesmeisterschaft|')) echo '<span class="calendarOptionBox" style="border-color: '.Setting::Get("ColorLandesmeisterschaft").'"><a href="'.ThisPage("+removeCategory=Landesmeisterschaft").'">X</a> Landesmeisterschaft</span>';
            if(SubStringFind($_SESSION['calenderSections'],'|Doppelturnier|')) echo '<span class="calendarOptionBox" style="border-color: '.Setting::Get("ColorDoppelturnier").'"><a href="'.ThisPage("+removeCategory=Doppelturnier").'">X</a> Doppelturnier</span>';
            if(SubStringFind($_SESSION['calenderSections'],'|Nachwuchs|')) echo '<span class="calendarOptionBox" style="border-color: '.Setting::Get("ColorNachwuchs").'"><a href="'.ThisPage("+removeCategory=Nachwuchs").'">X</a> Nachwuchs</span>';
            if(SubStringFind($_SESSION['calenderSections'],'|SchuelerJugend|')) echo '<span class="calendarOptionBox" style="border-color: '.Setting::Get("ColorSchuelerJugend").'"><a href="'.ThisPage("+removeCategory=SchuelerJugend").'">X</a> Sch&uuml;ler/Jugend</span>';
            if(SubStringFind($_SESSION['calenderSections'],'|Senioren|')) echo '<span class="calendarOptionBox" style="border-color: '.Setting::Get("ColorSenioren").'"><a href="'.ThisPage("+removeCategory=Senioren").'">X</a> Senioren</span>';
            if(SubStringFind($_SESSION['calenderSections'],'|Training|')) echo '<span class="calendarOptionBox" style="border-color: '.Setting::Get("ColorTraining").'"><a href="'.ThisPage("+removeCategory=Training").'">X</a> Training</span>';

            echo '
            <iframe src="/graphic_calendar'.$frameExtension.'" frameborder="0" onload="ResizeIframe(this);" class="calender_iframe"></iframe>
        </div>
        ';


    }

include("footer.php");

?>