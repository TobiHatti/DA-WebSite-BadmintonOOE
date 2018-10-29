<?php
    if(isset($_POST['changeContent']))
    {
        $postParts = explode('||',$_POST['changeContent']);
        $page = $postParts[0];
        $pidx = $postParts[1];

        $content = $_POST['contentEdit'];

        if(!MySQLExists("SELECT id FROM page_content WHERE page = '$page' AND paragraph_index = '$pidx'"))
        {
            MySQLNonQuery("INSERT INTO page_content (id, page, paragraph_index) VALUES ('','$page','$pidx')");
        }

        MySQLNonQuery("UPDATE page_content SET text = '$content' WHERE page = '$page' AND paragraph_index = '$pidx'");

        Redirect(ThisPage("!editContent"));
        die();
    }

    if(isset($_GET['toggleBroadcast']))
    {
        $current = GetProperty("ShowBroadcast");

        if($current == 'true') SetProperty("ShowBroadcast","false");
        else SetProperty("ShowBroadcast","true");

        Redirect(ThisPage("-toggleBroadcast"));
        die();
    }

    if(isset($_GET['toggleEvents']))
    {
        $current = GetProperty("ShowHomeEvents");

        if($current == 'true') SetProperty("ShowHomeEvents","false");
        else SetProperty("ShowHomeEvents","true");

        Redirect(ThisPage("-toggleEvents"));
        die();
    }
?>