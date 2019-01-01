<?php
    require("../../headerincludes.php");

    if(isset($_GET['query']))
    {
        $sqlQuery = $_GET['query'];

        $sqlResult = -1;
        if($_GET['action']=='scalar') $sqlResult = DynLoad::Scalar($sqlQuery);
        if($_GET['action']=='count') $sqlResult = DynLoad::Count($sqlQuery);
        if($_GET['action']=='exist') $sqlResult = DynLoad::Exist($sqlQuery) ? 1 : 0;
        if($_GET['action']=='list')
        {
            $sqlResult = "";
            $sqlResultArray = DynLoad::Cluster($sqlQuery);
            foreach($sqlResultArray as $rs) $sqlResult .= $rs['dynLoadValue'].'|=|'.$rs['dynLoadText'].'|==|';
        }


        echo '
            <head>
                <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            </head>
            <script type="text/javascript">

            $(window).load(function () {
                document.getElementById("dynloadOutput").value = "'.$sqlResult.'";
                document.getElementById("loadCompleted").value = "1";
            });

            </script>
            <input type="" id="dynloadOutput"/>
        ';
    }

    echo '<input type="" id="loadCompleted"/>';

?>