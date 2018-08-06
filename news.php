<?php
    require("header.php");
    PageTitle("News");

    if(isset($_POST['send']))
    {
        echo '<textarea>'.nl2br($_POST['content']).'</textarea>';
    }

    // NOTE:
    // HTACCESS CONTAINS SPECIAL CONFIGURATION FOR THIS PAGE!
    // A rewrite-rule is active, where
    // /news?artikel=xy
    // can be written as:
    // /news/artikel/xy
    // "xy" can be a value between "0-9", "a-z", "A-Z" or a "-"

    if(isset($_GET['artikel']))
    {
        echo '<h1 class="stagfade1">Artikel-Name</h1>';
    }
    else if(isset($_GET['neu']))
    {
        echo '<h1 class="stagfade1">Neuer Artikel</h1>';

        echo '
            <hr>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <br>
                '.TextareaPlus("content").'

                <button type="submit" name="send">Senden</button>
            </form>
        ';
    }
    else
    {
        echo '<h1 class="stagfade1">Artikel-&Uuml;bersicht</h1>';
    }



    include("footer.php");
?>