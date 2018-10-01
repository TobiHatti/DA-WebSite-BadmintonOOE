<?php

    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    require("data/mysql_connect.php");

    require("data/extension.lib.php");
    require("data/file.lib.php");
    require("data/mysql.lib.php");
    require("data/property.lib.php");
    require("data/string.lib.php");


    echo '<head>';
    require("headerlinks.php");
    echo '</head>';



    echo '<center>';

    if(!isset($_GET['template']))
    {
        echo '<h3>Bitte Vorlage ausw&auml;hlen</h3>';
    }
    else
    {
        $val = FetchArray("news_templates","id",$_GET['template']);

        echo '<div style="position: relative; width: 640px; height: 360px; background-image: url(\'content/newstemplates/'.$val['image'].'\'); background-size: cover;" id="exportDiv">';

        if($val['actText1']) echo '<span id="textField1" style="position:absolute; top: '.$val['t1_y'].'px; left: '.$val['t1_x'].'px; font-size: '.$val['t1_size'].'pt; color: '.$val['t1_color'].';">Text #1</span>';
        if($val['actText2']) echo '<span id="textField2" style="position:absolute; top: '.$val['t2_y'].'px; left: '.$val['t2_x'].'px; font-size: '.$val['t2_size'].'pt; color: '.$val['t2_color'].';">Text #2</span>';
        if($val['actText3']) echo '<span id="textField3" style="position:absolute; top: '.$val['t3_y'].'px; left: '.$val['t3_x'].'px; font-size: '.$val['t3_size'].'pt; color: '.$val['t3_color'].';">Text #3</span>';
        if($val['actImg']) echo '<img src="/content/newstemplates/img/'.$val['img_file'].'" style="position: absolute; top: '.$val['img_y'].'; left: '.$val['img_x'].'; width: '.$val['img_size'].';" />';

        echo '</div>';

        if($val['actText1']) echo '<input type="text" placeholder="Text 1..." oninput="CopyTextToSpan(this,\'textField1\')"/>';
        if($val['actText2']) echo '<input type="text" placeholder="Text 2..." oninput="CopyTextToSpan(this,\'textField2\')"/>';
        if($val['actText3']) echo '<input type="text" placeholder="Text 3..." oninput="CopyTextToSpan(this,\'textField3\')"/>';

        echo '<br><a onclick="window.top.location = \'/news/neu#\'"><button type="button" onclick="ConvertDiv2Base64Froala(\'exportDiv\');">Bild einf&uuml;gen</button></a>';
    }

    echo '</center>';



?>