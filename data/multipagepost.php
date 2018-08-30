<?php
    if(isset($_POST['changeContent']))
    {
        $postParts = explode('||',$_POST['changeContent']);
        $page = $postParts[0];
        $pidx = $postParts[1];

        $content = $_POST['contentEdit'];

        MySQLNonQuery("UPDATE page_content SET text = '$content' WHERE page = '$page' AND paragraph_index = '$pidx'");

        Redirect(ThisPage("!editContent"));
    }
?>