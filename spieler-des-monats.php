<?php
    require("header.php");
    PageTitle("Gamer of the Month");

    if(isset($_POST['publish']) AND CheckPermission("AddNews"))
    {
        $article_url = $_POST['article_id'];
        $article = $_POST['article'];
        $tags = SReplace($_POST['tags']);
        $release = $_POST['release_date'];
        $thumb = $_POST['thumbnail'];
        $title = $_POST['title'];
        $author = (isset($_SESSION['userID'])) ? $_SESSION['userID'] : 0 ;

        // In case the ID already exists, cycle
        if(MySQLExists("SELECT article_url FROM news WHERE article_url = '$article_url'"))
        {
            $i=2;
            do
            {
                $newID = $article_url.'-'.$i;
                $i++;
            }
            while(MySQLExists("SELECT article_url FROM news WHERE article_url = '$newID'"));

            $article_url = $newID;
        }

        MySQLNonQuery("INSERT INTO news (id,article_url,title, author,tags,article,release_date,thumbnail) VALUES ('','$article_url','$title','$author','$tags','$article','$release','$thumb')") or die("<h1>Ein fehler ist aufgetreten</h1>");

        // Changing the News-Slider in Index
        //RefreshSliderContent();

        //Redirect("/news/artikel/".$article_url);
        //die();
    }
    else if(isset($_GET['neu']) AND CheckPermission("AddNews"))
    {
        echo '
            <h2 class="stagfade1">Spieler des Monats Artikel verfassen</h2>
            <hr>
            <form action="/news?check" method="post" accept-charset="utf-8" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
                <div class="stagfade2">
                    <p>Verfassen Sie den neuen Artikel in dem untenstehenden Textfeld:</p>
                    '.TextareaPlus("content","article","<h2>Artikel-Titel</h2>Hier den Artikel verfassen...",true).'
                    <br>
                    <hr>
                </div>

                <!--

                <div class="stagfade3">
                    <h3>Gallerie-Fotos</h3>
                    W&auml;hlen Sie ein oder mehrere Fotos f&uuml;r diesen Bericht aus: <br><br>
                    '.FileButton('gallery_images', 'gimg',true).'
                    <br>
                    <hr>
                </div>

                -->

                
                <div class="stagfade5">
                    <br><br>
                    <button type="submit">&#10148; Vorschau</button>
                </div>
            </form>
        ';
    }
    else if(isset($_GET['check']) AND CheckPermission("AddNews") )
    {
        $article = $_POST['content'];

        list($article,$nameid,$imgs,$title) = ArticlePreProcessRoutine($article);

        // START OF PREVIEW

        echo '<h2 class="stagfade1">Artikel-Vorschau</h2><hr>';

        echo '
            <span style="color: #A9A9A9">'.date_format(date_create($_POST['release_date']),"d. F Y").'</span>
            |';

            foreach($tags = explode('||',$_POST['tags']) as $tag)
            {
                if($tag != "" AND $tag != $tags[0]) echo ',&nbsp;&nbsp;<a href="#">'.$tag.'</a>';
                if($tag == $tags[0]) echo '&nbsp;&nbsp;<a href="#">'.$tag.'</a>';
            }

            echo '
            <div class="fr-view fr-element">'.$article.'</div>
            <hr>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">

                <textarea name="article" style="display: none">'.$article.'</textarea>
                <input type="hidden" value="'.$_POST['tags'].'" name="tags"/>
                <input type="hidden" value="'.$_POST['release_date'].'" name="release_date"/>
                <input type="hidden" value="'.$nameid.'" name="article_id"/>
                <input type="hidden" value="'.$imgs.'" name="thumbnail"/>
                <input type="hidden" value="'.$title.'" name="title"/>

                <button type="submit" name="publish">'.(($_POST['release_date'] == date("Y-m-d")) ? 'Jetzt ver&ouml;ffentlichen' : 'Am '.date_format(date_create($_POST['release_date']),"d.m.Y").' ver&ouml;ffentlichen').'</button>
            </form>
        ';
    }
    include("footer.php");
?>

