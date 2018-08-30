<?php

function Checkbox($name, $id, $checked = 0,$onchange="")
{
    // DESCRIPTION:
    // Returns a Checkbox Form-Element
    // $name    Form-Element-Name
    // $id      Unique ID. Required since it uses a label
    // $checked Default: 0. Sets the checkbox to checked (1)

    return '<input type="checkbox" name="'.$name.'" id="'.$id.'" onchange="'.$onchange.'" class="slidecheckbox" '.(($checked) ? 'checked' : '').'/><label class="checkbox_toggle_lable" for="'.$id.'">Toggle</label>';
}

function RadioButton($title, $name, $checked = 0)
{
    // DESCRIPTION:
    // Returns a Radio-Button Form-Element
    // $title   Text beside the Radio-Button
    // $name    Form-Element-Name
    // $checked Default: 0. Sets the Button to checked (1)

    return '
        <label class="radiolabel">'.$title.'
            <input type="radio" name="'.$name.'" '.(($checked) ? 'checked' : '').'>
            <span class="radiocheckmark"></span>
        </label>
    ';
}

function FileButton($name, $id, $multiple=0)
{
    // DESCRIPTION:
    // Returns a File-Upload Form-Element
    // $name      Form-Element-Name
    // $id        Unique ID. Required since it uses a label
    // $multiple  Default: 0. Defines if multiple files can be selected

    return  '
        <input type="file" name="'.$name.'[]" id="'.$id.'" class="inputfile" data-multiple-caption="{count} Dateien" '.(($multiple) ? 'multiple' : '').' hidden/>
        <label for="'.$id.'"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span style="color:#FEFEFE;">Datei ausw&auml;hlen</span></label>
        <script src="/js/filebutton.js"></script>
    ';
}

function TextareaPlus($name, $id="edit", $placeholder="")
{
    return '

        <textarea name="'.$name.'" id="'.$id.'" style="margin-top: 30px;" required>
            '.$placeholder.'
        </textarea>


        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

        <script type="text/javascript" src="/js/froala/froala_editor.min.js" ></script>
        <script type="text/javascript" src="/js/froala/plugins/align.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/char_counter.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/code_beautifier.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/code_view.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/colors.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/draggable.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/emoticons.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/entities.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/file.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/font_size.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/font_family.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/fullscreen.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/image.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/image_manager.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/line_breaker.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/inline_style.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/link.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/lists.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/paragraph_format.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/paragraph_style.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/quick_insert.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/quote.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/table.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/save.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/url.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/video.min.js"></script>

        <script>
            $(function(){
                $("#'.$id.'").froalaEditor()
                .on("froalaEditor.image.beforeUpload", function (e, editor, files) {
                  if (files.length) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                      var result = e.target.result;

                      editor.image.insert(result, null, null, editor.image.get());
                    };

                    reader.readAsDataURL(files[0]);
                  }

                  return false;
                })
            });
        </script>
    ';
}

function PageTitle($string)
{
    // DESCRIPTON:
    // Changes the Page-Title in the Tab
    // Since it is changed in the Middle of the page, and not in the <head>-part,
    // Javascript is required to do so.

    echo '<script>document.title = "'.$string.' | O\u00d6. Badmintonverband";</script>';
}

function FroalaContent($content)
{
    return '<div class="fr-view fr-element">'.$content.'</div>';
}

function PageContent($paragraph_index,$allowEdit=false)
{
    // DESCRIPTION:
    // Gets the text/description for the current page
    // With $paragraph_index, several entries can be saved in one page.

    $page = ThisPage("!editContent");
    $content = nl2br(MySQLSkalar("SELECT text AS x FROM page_content WHERE page = '$page' AND paragraph_index = '$paragraph_index'"));

    if(!$allowEdit)
    {
        $retval = FroalaContent($content);
    }
    else if(($allowEdit AND !isset($_GET['editContent'])) OR ($allowEdit AND isset($_GET['editContent']) AND $_GET['editContent']!=$paragraph_index))
    {
        $retval = FroalaContent($content).'<p style="margin: 0;"><a href="'.ThisPage('+editContent='.$paragraph_index).'">Bearbeiten</a></p>';
    }
    else if($allowEdit AND isset($_GET['editContent']) AND $_GET['editContent']==$paragraph_index)
    {
        $retval = TextareaPlus("contentEdit","contentEdit",$content).'<br><button type="submit" name="changeContent" value="'.$page.'||'.$paragraph_index.'">&Auml;ndern</button>';
    }

    return $retval;
}

function Loader()
{
    // DESCRIPTION:
    // Custom PreLoader
    // Triggered by onclick="LoadAnimation();"

    return '
        <center>
            <img src="/content/silhouette.png" alt="" class="ease10" style="height: 100px;" id="loadAnim1"/>
            <img src="/content/loader.gif" alt="" class="ease10" style="height: 60px; opacity: 0; margin-left:-80px;margin-bottom:20px;" id="loadAnim2"/>
        </center>
    ';
}

function ShowTags($tagstr,$disableLinks = false, $targetTop = false)
{
    // DESCRIPTION:
    // Lists all Tags with or without link of
    // an article
    // $tagstr          String of tags. Seperated by "||"
    // $disableLinks    Enable/Disable links (default: false)

    $retval = '';

    foreach($tags = explode('||',$tagstr) as $tag)
    {
        if($tag != "" AND $tag != $tags[0]) $retval .= ',&nbsp;&nbsp;<a '.(($targetTop) ? 'target="_top"' : '').' href="'.(($disableLinks) ? '#' : '/news/kategorie/'.$tag).'">'.((Fetch("news_tags","name","id",$tag)!="") ? Fetch("news_tags","name","id",$tag) : $tag).'</a>';
        if($tag == $tags[0]) $retval .= '<a '.(($targetTop) ? 'target="_top"' : '').' href="'.(($disableLinks) ? '#' : '/news/kategorie/'.$tag).'">'.((Fetch("news_tags","name","id",$tag)!="") ? Fetch("news_tags","name","id",$tag) : $tag).'</a>';
    }

   return $retval;
}

function NewsTile($strSQL, $targetTop = false)
{
    require("mysql_connect.php");

    $retval = '';
    $i=1;
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        $retval .= '
            <div class="news_article stagfade'.$i++.'">
                <div class="news_imagecontainer">
                    <a '.(($targetTop) ? 'target="_top"' : '').' href="/news/artikel/'.$row['article_url'].'">
                        <img src="'.(($row['thumbnail']=="") ? '/content/no-image.png' : $row['thumbnail'] ).'" alt="" class="news_image"/>
                    </a>
                </div>
                <div style="float:none;">
                    <span style="font-size: 10pt;color: #808080">'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['release_date']))).' &#10649;</span>
                    '.ShowTags($row['tags'],false,$targetTop).'
                    <a '.(($targetTop) ? 'target="_top"' : '').' href="/news/artikel/'.$row['article_url'].'"><h2>'.$row['title'].'</h2></a>
                    '.str_replace('<p></p>',' ',str_replace($row['title'],'',strip_tags($row['article'],'<p><s><b><i><u><strong><em><span><sub><sup><a><pre><code><ol><li><ul>'))).'
                </div>
            </div>
        ';
    }

    return $retval;
}

function NewsTileSlim($strSQL, $targetTop = false)
{
    require("mysql_connect.php");

    $retval = '';
    $i=1;
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        $retval .= '
            <div class="news_article_slim stagfade'.$i++.'">
                <div class="news_imagecontainer">
                    <a '.(($targetTop) ? 'target="_top"' : '').' href="/news/artikel/'.$row['article_url'].'">
                        <img src="'.(($row['thumbnail']=="") ? '/content/no-image.png' : $row['thumbnail'] ).'" alt="" class="news_image"/>
                    </a>
                </div>
                <div>
                    '.ShowTags($row['tags'],false,$targetTop).'
                    <a '.(($targetTop) ? 'target="_top"' : '').' href="/news/artikel/'.$row['article_url'].'"><h3>'.$row['title'].'</h3></a>
                    <span style="font-size: 10pt;color: #808080">'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['release_date']))).'</span>
                </div>
            </div>
        ';
    }

    return $retval;
}

function Pager($sqlQuery,$entriesPerPage = 10)
{
    $retval = '';

    $currentPage = (isset($_GET['page']) ? $_GET['page'] : 1 );
    $entryCounts = MySQLCount($sqlQuery);

    $pages = 0;

    while($entryCounts > 0)
    {
        $pages++;
        $entryCounts -= $entriesPerPage;
    }

    // What does this line below? It checks if the url contains a "?page=x" or a "&page=x" and replaces it with "?page=" or "&page=", depending on if a "?" already exists inside the manipulated URL
    $URLEx = (SubStringFind(str_replace('?page='.$currentPage,'',str_replace('&page='.$currentPage,'',ThisPage())),'?') ? (str_replace('?page='.$currentPage,'',str_replace('&page='.$currentPage,'',ThisPage())).'&page=') : (str_replace('?page='.$currentPage,'',str_replace('&page='.$currentPage,'',ThisPage())).'?page='));

    $back = ($currentPage == 1) ? true : false;
    $next = ($currentPage >= $pages) ? true : false;

    $retval .= '<div class="pager">';

    $retval .= ($back) ? '<span style="color: #696969;" title="Zur ersten Seite">&#9664;&#9664;</span>' : '<span title="Zur ersten Seite"><a href="'.$URLEx.'1">&#9664;&#9664;</a></span>' ;
    $retval .= ($back) ? '<span style="color: #696969;" title="Zur vorherigen Seite">&#9664;</span>' : '<span title="Zur vorherigen Seite"><a href="'.$URLEx.($currentPage-1).'">&#9664;</a></span>' ;

    for($i=1;$i<=$pages;$i++)
    {
        $retval .= ($currentPage == $i) ? '<span title="Zu Seite '.$i.'" style="color: #696969; font-size: 16pt;">'.$i.'</span>' : '<span title="Zu Seite '.$i.'" style="font-size: 14pt;"><a href="'.$URLEx.$i.'">'.$i.'</a></span>' ;
    }

    $retval .= ($next) ? '<span title="Zur n&auml;chsten Seite" style="color: #696969;">&#9654;</span>' : '<span title="Zur n&auml;chsten Seite"><a href="'.$URLEx.($currentPage+1).'">&#9654;</a></span>' ;
    $retval .= ($next) ? '<span  title="Zur letzten Seite" style="color: #696969;">&#9654;&#9654;</span>' : '<span title="Zur letzten Seite"><a href="'.$URLEx.$pages.'">&#9654;&#9654;</a></span>' ;

    $retval .= '</div>';

    return $retval;
}

function NewsSidebar()
{
    require("mysql_connect.php");

    $retval ='
        <div class="home_tile_container_l stagfade1">
            <div class="home_tile_title">Neueste Beitr&auml;ge</div>
            <div class="home_tile_content">
                <ul>
                    ';
                    $today = date("Y-m-d");
                    $strSQL = "SELECT article_url, title FROM news WHERE release_date <= '$today' ORDER BY release_date DESC LIMIT 0,4";
                    $rs=mysqli_query($link,$strSQL);
                    while($row=mysqli_fetch_assoc($rs))
                    {
                        $retval .= '<li><a href="/news/artikel/'.$row['article_url'].'">'.$row['title'].'</a></li>';
                    }
                    $retval .= '
                </ul>
            </div>
        </div>
        <div class="home_tile_container_l stagfade2">
            <div class="home_tile_title">Kategorien</div>
            <div class="home_tile_content">
                <ul>';
                    $strSQL = "SELECT * FROM news_tags";
                    $rs=mysqli_query($link,$strSQL);
                    while($row=mysqli_fetch_assoc($rs)) { $retval .= '<li><a href="/news/kategorie/'.$row['id'].'">'.$row['name'].'</a></li>'; }
                    $retval .= '
                </ul>
            </div>
        </div>
    ';

    return $retval;
}

function ArticleImgFilter($article,$path)
{
    // DESCRIPTION:
    // Filters out images (BASE64) from the article,
    // uploads them to the server and replaces the
    // article with image paths
    // $article     Article to be filtered
    // $path        Path to the news-directory


    // Change the default image tags
    // Only required for the following loop
    $article = str_replace('src="data:image/','SRCX="DATA:IMAGE/',$article);

    // Run as long as Base64 images are detected
    while(SubStringFind($article,'SRCX="DATA:IMAGE/'))
    {
        // Find and extract the first found Base64 Code
        $img_start_pos = substr($article,strpos($article,'SRCX="'));
        $img_end_pos = strpos($img_start_pos,'" ');
        $img_string = str_replace('SRCX="','',substr($img_start_pos,0,$img_end_pos));

        // Change string so it can be converted
        $img_string_to_path = str_replace('DATA:IMAGE','data:image',$img_string);

        //Convert from Base64 to PNG and Upload to Server
        $img_path = Base64toIMG($img_string_to_path,$path);

        // replace Base64 image with image-path and change back
        // the first found src-identifier
        $article = str_replace($img_string,$img_path,$article);
        $article = str_replace_first("SRCX", "src", $article);
    }

    return $article;
}


?>