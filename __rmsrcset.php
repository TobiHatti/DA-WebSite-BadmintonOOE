<?php

    require("header.php");

    $newsArr = MySQL::Cluster("SELECT * FROM news");

    foreach($newsArr as $news)
    {
        $newArticle = str_replace("srcset=","oldsource=",$news['article']);
        MySQL::NonQuery("UPDATE news SET article = ? WHERE id = ?",'ss',$newArticle,$news['id']);
    }

?>