<?php
    if(isset($_POST['changeContent']))
    {
        $postParts = explode('||',$_POST['changeContent']);
        $page = $postParts[0];
        $pidx = $postParts[1];

        $content = $_POST['contentEdit'];

        if(!SQL::Exist("SELECT id FROM page_content WHERE page = ? AND paragraph_index = ?",'@s',$page,$pidx))
        {
            SQL::NonQuery("INSERT INTO page_content (id, page, paragraph_index) VALUES ('',?,?)",'@s',$page,$pidx);
        }

        SQL::NonQuery("UPDATE page_content SET text = ? WHERE page = ? AND paragraph_index = ?",'@s',$content,$page,$pidx);

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

    if(isset($_GET['regenerateSlider']))
    {
        RefreshSliderContent();
        Redirect(ThisPage('-regenerateSlider'));
        die();
    }

?>