<?php
    require("header.php");
    PageTitle("Schiedsrichter");

    if(!isset($_GET['seite'])) Redirect("/schiedsrichter/seite/1");

    if(isset($_GET['seite']) AND $_GET['seite']==1)
    {
        echo PageContent('1',CheckPermission("ChangeContent"));
    }
    if(isset($_GET['seite']) AND $_GET['seite']==2)
    {
        echo PageContent('2',CheckPermission("ChangeContent"));
    }

    echo ManualPager("/schiedsrichter/seite/1","/schiedsrichter/seite/2");

    include("footer.php");
?>