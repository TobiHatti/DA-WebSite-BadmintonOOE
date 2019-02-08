<?php
    if(isset($_POST['changeContent']))
    {
        $postParts = explode('||',$_POST['changeContent']);
        $page = $postParts[0];
        $pidx = $postParts[1];

        $content = $_POST['contentEdit'];

        if(!MySQL::Exist("SELECT id FROM page_content WHERE page = ? AND paragraph_index = ?",'@s',$page,$pidx))
        {
            MySQL::NonQuery("INSERT INTO page_content (page, paragraph_index) VALUES (?,?)",'@s',$page,$pidx);
        }

        MySQL::NonQuery("UPDATE page_content SET text = ? WHERE page = ? AND paragraph_index = ?",'@s',$content,$page,$pidx);

        Redirect(ThisPage("!editContent"));
        die();
    }

    if(isset($_GET['toggleBroadcast']))
    {
        $current = Setting::Get("ShowBroadcast");

        if($current == 'true') Setting::Set("ShowBroadcast","false");
        else Setting::Set("ShowBroadcast","true");

        Redirect(ThisPage("-toggleBroadcast"));
        die();
    }

    if(isset($_GET['toggleEvents']))
    {
        $current = Setting::Get("ShowHomeEvents");

        if($current == 'true') Setting::Set("ShowHomeEvents","false");
        else Setting::Set("ShowHomeEvents","true");

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