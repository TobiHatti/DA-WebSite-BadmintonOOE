<?php

    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    require("data/mysql_connect.php");

    require("data/extension.lib.php");
    require("data/file.lib.php");
    //require("data/mysql.lib.php");
    require("data/mysql.lib.new.php");
    //require("data/property.lib.php");
    require("data/setting.lib.php");
    require("data/string.lib.php");
    require("data/notification.lib.php");

    require("data/functions.php");


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
        $val = SQL::FetchRow("news_templates","id",$_GET['template']);

        if($val['bgIsImage']) $backgroundStyle = "background-size: cover; background-image: url('content/newstemplates/".$val['bgPath']."')";
        else
        {
            if($val['bgIsGradient'])
            {
                $orientation = $val['bgDirection'];
                $orientation = str_replace('left','0%',$orientation);
                $orientation = str_replace('top','0%',$orientation);
                $orientation = str_replace('right','100%',$orientation);
                $orientation = str_replace('bottom','100%',$orientation);
                $orientation = explode('||',$orientation);
                $startOrientation = $orientation[0];
                $endOrientation = $orientation[1];
                $backgroundStyle = 'background: -webkit-gradient(linear,'.$startOrientation.','.$endOrientation.', from(#'.$val['bgColor1'].'), to(#'.$val['bgColor2'].'));';
            }
            else $backgroundStyle = 'background: #'.$val['bgColor1'].';';
        }

        echo '<div id="exportDiv" style="overflow: hidden; position: relative; width: 640px; height: 360px; '.$backgroundStyle.'">';


        if($val['actT1']) echo '<div style="position: absolute; width: 30px; white-space: nowrap; overflow: visible; top: '.$val['yT1'].'px; left: '.$val['xT1'].'px;"><span id="textField1" style="color: #'.$val['colorT1'].'; font-size: '.$val['sizeT1'].'pt;">Text #1</span></div>';
        if($val['actT2']) echo '<div style="position: absolute; width: 30px; white-space: nowrap; overflow: visible; top: '.$val['yT2'].'px; left: '.$val['xT2'].'px;"><span id="textField2" style="color: #'.$val['colorT2'].'; font-size: '.$val['sizeT2'].'pt;">Text #2</span></div>';
        if($val['actT3']) echo '<div style="position: absolute; width: 30px; white-space: nowrap; overflow: visible; top: '.$val['yT3'].'px; left: '.$val['xT3'].'px;"><span id="textField3" style="color: #'.$val['colorT3'].'; font-size: '.$val['sizeT3'].'pt;">Text #3</span></div>';
        if($val['actImg']) echo '<div style="position: absolute; width: 30px; white-space: nowrap; overflow: visible; top: '.$val['yImg'].'px; left: '.$val['xImg'].'px;"><img style="width: '.$val['sizeImg'].'px;" src="/content/newstemplates/img/'.$val['pathImg'].'" alt="" /></div>';

        echo '</div>';


        if($val['actT1']) echo '<input type="text" placeholder="Text 1..." oninput="CopyTextToSpan(this,\'textField1\')"/>';
        if($val['actT2']) echo '<input type="text" placeholder="Text 2..." oninput="CopyTextToSpan(this,\'textField2\')"/>';
        if($val['actT3']) echo '<input type="text" placeholder="Text 3..." oninput="CopyTextToSpan(this,\'textField3\')"/>';

        echo '<br><a onclick="window.top.location = \'/news/neu#\'"><button type="button" onclick="ConvertDiv2Base64Froala(\'exportDiv\');">Bild einf&uuml;gen</button></a>';


            echo '<br><span style="float: right;">'.DeleteButton("News","news_templates",$val['id'],false,true,"Vorlage L&ouml;schen").'</span>';

    }

    echo '</center>';



?>