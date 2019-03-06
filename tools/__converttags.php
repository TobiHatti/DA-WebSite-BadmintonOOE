<?php
    require("header.php");

    $newsAll = MySQL::Cluster("SELECT * FROM news");


    foreach($newsAll as $news)
    {
        echo 'Original: '.$news['tags'].'<br>';
        $newTag = '';
        foreach($tags = explode('||',$news['tags']) as $tag)
        {
            $tag = str_replace('|','',$tag);



            if($tag != "") $newTag .= '|'.$tag.'|';
        }

        echo 'New: '.$newTag.'<br><br>';

        MySQL::NonQuery("UPDATE news SET tags = ? WHERE id = ?",'ss',$newTag,$news['id']);
    }

?>