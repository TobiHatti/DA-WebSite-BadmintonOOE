<?php
    require("header.php");
    PageTitle("Spieler des Monats");

    if(isset($_POST['publish']) AND CheckPermission("AddNews"))
    {
        $article_url = $_POST['article_id'];
        $article = $_POST['article'];
        $tags = "Spieler-des-Monats";
        $release = date("Y-m-d");
        $thumb = $_POST['thumbnail'];
        if($thumb=='') $thumb = '/content/user.png';
        $title = $_POST['title'];
        $author = (isset($_SESSION['userID'])) ? $_SESSION['userID'] : 0 ;

        // In case the ID already exists, cycle
        if(MySQL::Exist("SELECT article_url FROM news WHERE article_url = ?",'@s',$article_url))
        {
            $i=2;
            do
            {
                $newID = $article_url.'-'.$i;
                $i++;
            }
            while(MySQL::Exist("SELECT article_url FROM news WHERE article_url = ?",'@s',$newID));

            $article_url = $newID;
        }

        MySQL::NonQuery("INSERT INTO news (id,article_url,title, author,tags,article,release_date,thumbnail) VALUES ('',?,?,?,?,?,?,?)",'@s',$article_url,$title,$author,$tags,$article,$release,$thumb);

        //Changing the News-Slider in Index
        RefreshSliderContent();

        Redirect("/spieler-des-monats/".$article_url);
        die();
    }

    if(isset($_GET['neu']) AND CheckPermission("AddNews"))
    {
        echo '
            <h2 class="stagfade1">Spieler des Monats - Artikel verfassen</h2>
            <hr>
            <form action="/spieler-des-monats?check" method="post" accept-charset="utf-8" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
                <div class="stagfade2">
                    <p>Verfassen Sie den Artikel in dem untenstehenden Textfeld:</p>
                    '.TextareaPlus("content","article","<h2>Spieler des Monats</h2>Infos zu Person...",true).'
                    <br>
                </div>
                <div class="stagfade3">
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

        echo '<h2 class="stagfade1">Vorschau</h2><hr>';

        echo '
            <span style="color: #A9A9A9">'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime(date("Y-m-d")))).'</span>

            <div class="fr-view fr-element">'.$article.'</div>
            <hr>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">

                <textarea name="article" style="display: none">'.$article.'</textarea>
                <input type="hidden" value="'.$nameid.'" name="article_id"/>
                <input type="hidden" value="'.$imgs.'" name="thumbnail"/>
                <input type="hidden" value="'.$title.'" name="title"/>

                <button type="submit" name="publish">Spieler des Monats hinzuf&uuml;gen</button>
            </form>
        ';
    }
    else
    {
        if(!isset($_GET['article'])) $article = $_GET['article'];
        else $article = MySQL::Scalar("SELECT article_url FROM news WHERE tags = 'Spieler-des-Monats' ORDER BY id DESC LIMIT 0,1");

        MySQL::NonQuery("UPDATE news SET views = views + 1 WHERE article_url = ?",'@s',$article);

        $newsData = MySQL::Row("SELECT * FROM news WHERE article_url = ?",'s',$article);

        echo '
            <div class="doublecol_singletile">
                <article>
                    <span style="color: #A9A9A9">'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($newsData['release_date']))).' |</span>
                    '.ShowTags($newsData['tags']).'
                    <div class="fr-view fr-element">'.$newsData['article'].'</div>
                    <span>'.$newsData['views'].' Aufrufe</span><br><br>
                    ';

                    if(CheckPermission("EditNews"))
                    {
                        echo '<span style="float: left;"> '.EditButton(ThisPage("!editContent","!editSC","+editSC=".$newsData['id'])).' </span>';
                    }

                    if(CheckPermission("DeleteNews"))
                    {
                        echo '<span style="float: left;"> '.DeleteButton("News","news",$newsData['id']).' </span>';
                    }

                    echo '
                </article>
                <aside>
                    '.NewsSidebar() .'
                </aside>
            </div>
        ';
    }



    include("footer.php");
?>

