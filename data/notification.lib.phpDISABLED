<?php

function SetNotification($message,$icon='',$autoClose=true)
{
    // DESCRIPTION:
    // Sets a notification-banner for the next loaded Page
    // $message     Displayed Message
    // $autoClose  Defines if a Click on the notification is required to close it
    // $icon        Displayed Icon
    //        e.g.  asterisk
    //              error
    //              exclamation
    //              hand
    //              information
    //              question
    //              stop
    //              warning

    $_SESSION['NotificationMessage'] = $message;
    $_SESSION['NotificationIcon'] = $icon;
    $_SESSION['NotificationAutoClose'] = $autoClose;
}

function ExceptionNotification($title,$message,$icon='')
{
    // DESCRIPTION:
    // Loads the error-Page with given text and icon
    // $title       Displayed Title
    // $message     Displayed Message
    // $icon        Displayed Icon
    //        e.g.  asterisk
    //              error
    //              exclamation
    //              hand
    //              information
    //              question
    //              stop
    //              warning

    $_SESSION['ExNotificationTitle'] = $title;
    $_SESSION['ExNotificationMessage'] = $message;

    switch($icon)
    {
        case 'asterisk':    $_SESSION['ExNotificationIcon'] = "/content/notificationHandler/asterisk.png"; break;
        case 'error':       $_SESSION['ExNotificationIcon'] = "/content/notificationHandler/error.png"; break;
        case 'exclamation': $_SESSION['ExNotificationIcon'] = "/content/notificationHandler/exclamation.png"; break;
        case 'hand':        $_SESSION['ExNotificationIcon'] = "/content/notificationHandler/hand.png"; break;
        case 'information': $_SESSION['ExNotificationIcon'] = "/content/notificationHandler/information.png"; break;
        case 'question':    $_SESSION['ExNotificationIcon'] = "/content/notificationHandler/question.png"; break;
        case 'stop':        $_SESSION['ExNotificationIcon'] = "/content/notificationHandler/stop.png"; break;
        case 'warning':     $_SESSION['ExNotificationIcon'] = "/content/notificationHandler/warning.png"; break;
        case 'success':     $_SESSION['ExNotificationIcon'] = "/content/notificationHandler/success.png"; break;
        default:            $_SESSION['ExNotificationIcon'] = "/content/notificationHandler/blank.png"; break;
    }

    Redirect("/error");
    die();
}


function CatchNotification()
{
    // Unsetting Exception-Notification

    if(ThisPage()!="error")
    {
        $_SESSION['ExNotificationTitle'] = '';
        $_SESSION['ExNotificationMessage'] = '';
        $_SESSION['ExNotificationIcon'] = '';
    }

    // Catch Notification-Banners
    if(isset($_SESSION['NotificationMessage']) AND $_SESSION['NotificationMessage']!='')
    {
        switch($_SESSION['NotificationIcon'])
        {
            case 'asterisk':    $bgColor = '#FFFFFF'; $img = "/content/notificationHandler/asterisk.png"; break;
            case 'error':       $bgColor = '#FFC7C7'; $img = "/content/notificationHandler/error.png"; break;
            case 'exclamation': $bgColor = '#FFFFC2'; $img = "/content/notificationHandler/exclamation.png"; break;
            case 'hand':        $bgColor = '#FFC7C7'; $img = "/content/notificationHandler/hand.png"; break;
            case 'information': $bgColor = '#DBEDFF'; $img = "/content/notificationHandler/information.png"; break;
            case 'question':    $bgColor = '#DBEDFF'; $img = "/content/notificationHandler/question.png"; break;
            case 'stop':        $bgColor = '#FFC7C7'; $img = "/content/notificationHandler/stop.png"; break;
            case 'warning':     $bgColor = '#FFFFC2'; $img = "/content/notificationHandler/warning.png"; break;
            case 'success':     $bgColor = '#DAF6DA'; $img = "/content/notificationHandler/success.png"; break;
            default:            $bgColor = '#FFFFFF'; $img = "/content/notificationHandler/blank.png"; break;
        }

        $notificationIcon = (isset($_SESSION['NotificationIcon']) AND $_SESSION['NotificationIcon']!='') ? '' : '';

        $message = $_SESSION['NotificationMessage'];

        $_SESSION['NotificationMessage'] = '';
        $_SESSION['NotificationIcon'] = '';
        $_SESSION['NotificationAutoClose'] = '';

        return '
            <div id="notification_banner" class="notigication_banner" style="background:'.$bgColor.'">
                <img src="'.$img.'" class="notification_icon_blur"><img src="'.$img.'" class="notification_icon">
                '.$message.'
            </div>
        ';
    }
}



?>