<?php
    session_start();
    session_destroy();


    if(isset($_COOKIE['user_id']))
    {
        unset($_COOKIE['user_id']);
        setcookie("user_id",'',time() - 3600);
    }

    echo '<script>window.history.back();</script>';
?>