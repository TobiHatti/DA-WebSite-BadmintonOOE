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

function Tickbox($name, $id, $text, $checked = false,$onchange="", $disabled = false)
{
    // DESCRIPTION:
    // Returns a Checkbox Form-Element
    // $name    Form-Element-Name
    // $id      Unique ID. Required since it uses a label
    // $text    Text shown next to the Tickbox
    // $checked Default: 0. Sets the checkbox to checked (1)

    return '<div class="tickbox"><input '.($disabled ? 'disabled' : '').' type="checkbox" name="'.$name.'" id="'.$id.'" onchange="'.$onchange.'" '.(($checked) ? 'checked' : '').'/><label for="'.$id.'">'.$text.'</label></div>';
}

function Togglebox($name, $id, $checked = 0,$onchange="",$sessionName="")
{
    // DESCRIPTION:
    // Returns a Checkbox Form-Element
    // $name    Form-Element-Name
    // $id      Unique ID. Required since it uses a label
    // $checked Default: 0. Sets the checkbox to checked (1)

    return '
        <script>
            $(window).on("load", function () {
                if(window.sessionStorage.getItem("'.$sessionName.'")==null) window.sessionStorage.setItem("'.$sessionName.'",'.$checked.');
                CheckToggleSession("'.$id.'","'.$sessionName.'");
                '.$onchange.'
            });
        </script>

        <input type="checkbox" name="'.$name.'" id="'.$id.'" onchange="'.$onchange.'" class="togglecheckbox" '.(($checked) ? 'checked' : '').'/><label class="checkbox_toggle2_lable" for="'.$id.'">Toggle</label>
    ';
}

function RadioButton($title, $name, $checked = 0,$value="",$id="")
{
    // DESCRIPTION:
    // Returns a Radio-Button Form-Element
    // $title   Text beside the Radio-Button
    // $name    Form-Element-Name
    // $checked Default: 0. Sets the Button to checked (1)

    return '
        <label class="radiolabel">'.$title.'
            <input type="radio" id="'.$id.'" value="'.$value.'" name="'.$name.'" '.(($checked) ? 'checked' : '').'>
            <span class="radiocheckmark"></span>
        </label>
    ';
}

function FileButton($name, $id, $multiple=false, $onchange="", $onclick="",$style="")
{
    // DESCRIPTION:
    // Returns a File-Upload Form-Element
    // $name      Form-Element-Name
    // $id        Unique ID. Required since it uses a label
    // $multiple  Default: 0. Defines if multiple files can be selected

    return  '
        <input type="file" name="'.$name.'[]" id="'.$id.'" class="inputfile" data-multiple-caption="{count} Dateien" '.(($multiple) ? 'multiple' : '').' '.(($onchange!="") ? ('onchange="'.$onchange.'"') : '').' hidden/>
        <label for="'.$id.'" '.(($onclick!="") ? ('onclick="'.$onclick.'"') : '').' style="'.$style.'"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span style="color:#FEFEFE;">Datei ausw&auml;hlen</span></label>
        <script src="/js/filebutton.js"></script>
    ';
}

function ColorPicker($name, $id, $text, $value, $lifeChangeId="",$DOMproperty="style.color",$onchange="")
{
    // DESCRIPTION:
    // Returns a Color-Picker Form-Element
    // $name            Form-Element-Name
    // $id              Unique ID. Required
    // $text            Text inside the Button
    // $value           Color-Value (Hex)
    // $lifeChangeId    ID of the element to which the color should instantly be applied
    // $DOMproperty     DOM-Property to set the target that should be changed

    $idu = uniqid();

    return '
        '.(($lifeChangeId != "") ? ('<script> function JSColorUpdate'.$idu.'(jscolor) { document.getElementById("'.$lifeChangeId.'").'.$DOMproperty.' = "#" + jscolor } </script>') : '').'
        <input name="'.$name.'" type="hidden" id="'.$id.'" value="'.$value.'" '.(($onchange!="") ? ('onchange="'.$onchange.'"') : '').'>
        <button class="jscolor {'.(($lifeChangeId != "") ? ('onFineChange:\'JSColorUpdate'.$idu.'(this);\',') : '').'valueElement: \''.$id.'\',width:260, height:200, position:\'right\',borderColor:\'#FFF\', insetColor:\'#FFF\', backgroundColor:\'#666\'}" value="'.$value.'">'.$text.'</button>
    ';


}

function TextareaPlus($name, $id="edit", $placeholder="",$required = false)
{
    return '

        <textarea name="'.$name.'" id="'.$id.'" style="margin-top: 30px;display:none" '.($required ? 'required' : '').'>
            '.$placeholder.'
        </textarea>


        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

        <script type="text/javascript" src="/js/froala/froala_editor.min.js" ></script>
        <script type="text/javascript" src="/js/froala/plugins/align.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/char_counter.min.js"></script>
        <!--<script type="text/javascript" src="/js/froala/plugins/code_beautifier.min.js"></script>-->
        <!--<script type="text/javascript" src="/js/froala/plugins/code_view.min.js"></script>-->
        <script type="text/javascript" src="/js/froala/plugins/colors.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/draggable.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/emoticons.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/entities.min.js"></script>
        <!--<script type="text/javascript" src="/js/froala/plugins/file.min.js"></script>-->
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

        <script>
            window.setInterval(function(){

                    $( document ).ready(function() {
                        var doc = document.getElementsByClassName("fr-wrapper");
                        var nodes = doc[0].childNodes;
                        var nodeNodes = nodes[0].childNodes;
                        if(nodeNodes[0].tagName == "A")
                        {
                            nodeNodes[0].style.opacity = "0";
                            nodeNodes[0].href = "#";
                            nodeNodes[0].target = "";
                            nodeNodes[0].padding = "0px 0px 0px 0px";
                        }
                    });

            }, 100);
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

function PageContent($paragraph_index,$allowEdit=false,$reactToCustomPage="",$isIndex = false)
{
    // DESCRIPTION:
    // Gets the text/description for the current page
    // With $paragraph_index, several entries can be saved in one page.

    // !editContent for PageContent()-function
    // !editSC for Special Containers
    $page = ThisPage("!editSC","!editContent");

    if($reactToCustomPage!="") $page = $reactToCustomPage;

    $content = nl2br(MySQL::Scalar("SELECT text AS x FROM page_content WHERE page = ? AND paragraph_index = ?",'@s',$page,$paragraph_index));

    if(!$allowEdit)
    {
        $retval = FroalaContent($content);
    }
    else if(($allowEdit AND !isset($_GET['editContent'])) OR ($allowEdit AND isset($_GET['editContent']) AND $_GET['editContent']!=$paragraph_index))
    {
        if($isIndex) $retval = FroalaContent($content).EditButton('index'.str_replace('index','',ThisPage("!editSC","editContent",'+editContent='.$paragraph_index)));
        else $retval = FroalaContent($content).EditButton(ThisPage("!editSC","editContent",'+editContent='.$paragraph_index));
    }
    else if($allowEdit AND isset($_GET['editContent']) AND $_GET['editContent']==$paragraph_index)
    {
        $uniqID = uniqid();
        $retval = '
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                '.TextareaPlus("contentEdit","contentEdit",$content).'
                <br>
                <button type="submit" name="changeContent" value="'.$page.'||'.$paragraph_index.'">&Auml;ndern</button>
                <a href="'.ThisPage("!editContent").'"><button type="button">Abbrechen</button></a>

                <a href="#editFileUpload'.$uniqID.'"><button type="button" style="float: right;"><i class="fas fa-upload"></i> Datei hochladen</button></a>

                <div class="modal_wrapper" id="editFileUpload'.$uniqID.'">
                    <a href="#c">
                        <div class="modal_bg"></div>
                    </a>
                    <div class="modal_container" style="width: 500px; height: 360px">
                        <iframe src="/froalapl-upload-file?parent='.urlencode(ThisPage()).'" frameborder="0" style="width: 100%; height: 100%;"></iframe>
                    </div>
                </div>

            </form>';
    }


    return $retval;
}

function EditButton($link,$short = false,$targetTop = false,$customText = "")
{
    return '<a title="Bearbeiten" '.(($targetTop) ? 'target="_top"' : '').' style="margin: 0px 3px" href="'.$link.'"> &#9998; '.((!$short) ? (($customText!="") ? $customText : 'Bearbeiten') : '').'</a>';
}

function AddButton($link,$short = false,$targetTop = false,$customText = "")
{
    return '<a title="Hinzuf&uuml;gen" '.(($targetTop) ? 'target="_top"' : '').' style="margin: 0px 3px" href="'.$link.'"> &#65291; '.((!$short) ? (($customText!="") ? $customText : 'Hinzuf&uuml;gen') : '').'</a>';
}

function DeleteButton($permissionSuffix,$table,$id,$short = false,$targetTop = false,$customText = "")
{
    return '<a title="L&ouml;schen" '.(($targetTop) ? 'target="_top"' : '').' style="margin: 0px 3px; color: red;" href="/delete/'.$table.'/'.$permissionSuffix.'/'.$id.'"> &#10006; '.((!$short) ? (($customText!="") ? $customText : 'L&ouml;schen') : '').' </a>';
}

function CheckPermission($permission)
{
    // DESCRIPTION
    // Checks if the given permission is true/false
    // for the logged in user
    // $permission  Permission-Keyword

    if(!isset($_SESSION['userID']) OR $_SESSION['rank']!="administrative") return false;
    else
    {
        $uid = $_SESSION['userID'];

        if(MySQL::Scalar("SELECT allowed AS x FROM permissions WHERE permission = ? AND user_id = ?",'@s',$permission,$uid)==1) return true;
        else return false;
    }
}

function CheckRank()
{
    if(isset($_SESSION['rank']) AND $_SESSION['rank'] == MySQL::Scalar("SELECT rank FROM users WHERE id = ?",'s',$_SESSION['userID']))
    {
        return $_SESSION['rank'];
    }
    else return "user";
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

    $first = true;
    foreach($tags = explode('||',$tagstr) as $tag)
    {
        $tag = str_replace('|','',$tag);

        $ntname = MySQL::Scalar("SELECT name FROM news_tags WHERE id = ?",'s',$tag);

        if($tag != "" AND !$first) $retval .= ',&nbsp;&nbsp;<a '.(($targetTop) ? 'target="_top"' : '').' href="'.(($disableLinks) ? '#' : '/news/kategorie/'.$tag).'">'.(($ntname!="") ? $ntname : $tag).'</a>';
        if($first) $retval .= '<a '.(($targetTop) ? 'target="_top"' : '').' href="'.(($disableLinks) ? '#' : '/news/kategorie/'.$tag).'">'.(($ntname!="") ? $ntname : $tag).'</a>';

        $first = false;
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
                    '.TrimText(NBSPClean(str_replace('<p></p>',' ',str_replace($row['title'],'',strip_tags($row['article'],'<p><s><b><i><u><strong><em><span><sub><sup><a><pre><code><ol><li><ul>')))), 400).'
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

function Pager($sqlQuery,$entriesPerPage = 10,$customURL="")
{
    $retval = '';

    $thisPage = ($customURL == "") ? ThisPage() : $customURL;

    $currentPage = (isset($_GET['page']) ? $_GET['page'] : 1 );
    $entryCounts = MySQL::Count($sqlQuery);

    $pages = 0;

    while($entryCounts > 0)
    {
        $pages++;
        $entryCounts -= $entriesPerPage;
    }

    // What does this line below? It checks if the url contains a "?page=x" or a "&page=x" and replaces it with "?page=" or "&page=", depending on if a "?" already exists inside the manipulated URL
    $URLEx = (SubStringFind(str_replace('?page='.$currentPage,'',str_replace('&page='.$currentPage,'',$thisPage)),'?') ? (str_replace('?page='.$currentPage,'',str_replace('&page='.$currentPage,'',$thisPage)).'&page=') : (str_replace('?page='.$currentPage,'',str_replace('&page='.$currentPage,'',$thisPage)).'?page='));

    $back = ($currentPage == 1) ? true : false;
    $next = ($currentPage >= $pages) ? true : false;

    $retval .= '<div class="pager">';

    $retval .= ($back) ? '<span style="color: #696969;" title="Zur ersten Seite">&#9664;&#9664;</span>' : '<span title="Zur ersten Seite"><a href="'.$URLEx.'1">&#9664;&#9664;</a></span>' ;
    $retval .= ($back) ? '<span style="color: #696969;" title="Zur vorherigen Seite">&#9664;</span>' : '<span title="Zur vorherigen Seite"><a href="'.$URLEx.($currentPage-1).'">&#9664;</a></span>' ;

    if($pages > 7)
    {
        $showEllipsisLeft = true;
        $showEllipsisRight = true;
        // Propper page display at the ends of each sides
        // beginning of list
        if($currentPage == 1)
        {
            $startPage = 1;
            $endPage = $currentPage + 6;
            $showEllipsisLeft = false;
        }
        else if($currentPage == 2)
        {
            $startPage = 1;
            $endPage = $currentPage + 5;
            $showEllipsisLeft = false;
        }
        else if($currentPage == 3)
        {
            $startPage = 1;
            $endPage = $currentPage + 4;
            $showEllipsisLeft = false;
        }
        else if($currentPage == 4)
        {
            $startPage = 1;
            $endPage = $currentPage + 3;
            $showEllipsisLeft = false;
        }
        // end of list
        else if($currentPage == $pages)
        {
            $startPage = $currentPage - 6;
            $endPage = $currentPage;
            $showEllipsisRight = false;
        }
        else if($currentPage == $pages - 1)
        {
            $startPage = $currentPage - 5;
            $endPage = $currentPage + 1;
            $showEllipsisRight = false;
        }
        else if($currentPage == $pages - 2)
        {
            $startPage = $currentPage - 4;
            $endPage = $currentPage + 2;
            $showEllipsisRight = false;
        }
        else if($currentPage == $pages - 3)
        {
            $startPage = $currentPage - 3;
            $endPage = $currentPage + 3;
            $showEllipsisRight = false;
        }
        // default
        else
        {
            $startPage = $currentPage - 3;
            $endPage = $currentPage + 3;
        }

        // Show ellipsis and first pages left
        if($showEllipsisLeft)
        {
            if($currentPage - 5 > 1) $retval .= '<span title="Zu Seite 1" style="font-size: 14pt;"><a href="'.$URLEx.'1">1</a></span><span title="Zu Seite 2" style="font-size: 14pt;"><a href="'.$URLEx.'2">2</a></span>';
            $retval .= '<span style="color: #696969; font-size: 16pt;">...</span>';
        }

        for($i=$startPage;$i<=$endPage;$i++)
        {
            $retval .= ($currentPage == $i) ? '<span title="Zu Seite '.$i.'" style="color: #696969; font-size: 16pt;">'.$i.'</span>' : '<span title="Zu Seite '.$i.'" style="font-size: 14pt;"><a href="'.$URLEx.$i.'">'.$i.'</a></span>' ;
        }

        // Show ellipsis and last pages right 
        if($showEllipsisRight)
        {
            $retval .= '<span style="color: #696969; font-size: 16pt;">...</span>';
            if($currentPage + 5 < $pages) $retval .= '<span title="Zu Seite '.($pages-1).'" style="font-size: 14pt;"><a href="'.$URLEx.($pages-1).'">'.($pages-1).'</a></span><span title="Zu Seite '.$pages.'" style="font-size: 14pt;"><a href="'.$URLEx.$pages.'">'.$pages.'</a></span>';
        }
    }
    else
    {
        for($i=1;$i<=$pages;$i++)
        {
            $retval .= ($currentPage == $i) ? '<span title="Zu Seite '.$i.'" style="color: #696969; font-size: 16pt;">'.$i.'</span>' : '<span title="Zu Seite '.$i.'" style="font-size: 14pt;"><a href="'.$URLEx.$i.'">'.$i.'</a></span>' ;
        }
    }

    $retval .= ($next) ? '<span title="Zur n&auml;chsten Seite" style="color: #696969;">&#9654;</span>' : '<span title="Zur n&auml;chsten Seite"><a href="'.$URLEx.($currentPage+1).'">&#9654;</a></span>' ;
    $retval .= ($next) ? '<span  title="Zur letzten Seite" style="color: #696969;">&#9654;&#9654;</span>' : '<span title="Zur letzten Seite"><a href="'.$URLEx.$pages.'">&#9654;&#9654;</a></span>' ;

    $retval .= '</div>';

    return $retval;
}

function ManualPager()
{
    $amt = func_num_args();
    $links = func_get_args();

    $retval = '<div class="pager">';

    $retval .= '<span title="Zur ersten Seite"><a href="'.$links[0].'">&#9664;&#9664;</a></span>';

    $i=0;
    foreach($links as $link)
    {
        $retval .= '<span title="Zu Seite '.($i + 1).'" style="color: #696969; font-size: 16pt;"><a href="'.$links[$i].'">'.($i++ + 1).'</a></span>';
    }

    $retval .= '<span title="Zur letzten Seite"><a href="'.$links[$amt-1].'">&#9654;&#9654;</a></span>';

    $retval .= '</div>';

    return $retval;

}

function NewsSidebar()
{
    require("mysql_connect.php");

    $sidebarLimit = Setting::Get("NewsAmountTile");

    $retval ='
        <div class="home_tile_container_l stagfade1">
            <div class="home_tile_title">Neueste Beitr&auml;ge</div>
            <div class="home_tile_content">
                <ul>
                    ';
                    $today = date("Y-m-d");
                    $strSQL = "SELECT article_url, title FROM news WHERE release_date <= '$today' ORDER BY release_date DESC LIMIT 0,$sidebarLimit";
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
                    $strSQL = "SELECT * FROM news_tags WHERE active = '1'";
                    $rs=mysqli_query($link,$strSQL);
                    while($row=mysqli_fetch_assoc($rs)) { $retval .= '<li><a href="/news/kategorie/'.$row['id'].'">'.$row['name'].'</a></li>'; }
                    $retval .= '
                </ul>
            </div>
        </div>
    ';

    return $retval;
}

function ArticlePreProcessRoutine($article)
{
    // Finds out the title of the article
    $posh1 = strpos($article,'</h1>');
    $posh2 = strpos($article,'</h2>');
    $posh3 = strpos($article,'</h3>');
    $posh4 = strpos($article,'</h4>');
    $posh5 = strpos($article,'</h5>');
    $posbr = strpos($article,'</p>');
    $posp = strpos($article,'<br>');

    // Converts values to array, filters it and finds the right lenght of the title
    $cpos = min(array_filter(array(intval($posh1),intval($posh2),intval($posh3),intval($posh4),intval($posh5),intval($posbr),intval($posp))));

    // Remove HTML-Tags, exchange whitespaces and so on.
    $title = strip_tags(substr($article,0,$cpos));
    $nameid = SReplace(strip_tags(substr($article,0,$cpos)));

    $path = "content/news/$nameid/";
    $article = ArticleImgFilter($article,$path);

    // Finds Thumbnail-Photo
    $imgs = substr($article,strpos($article,'src="'));
    $tnepos = strpos($imgs,'" ');
    $imgs = str_replace('src="','',substr($imgs,0,$tnepos));

    return array($article,$nameid,$imgs,$title);
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

function SettingOption($type, $description, $property, $selectArray='')
{
    $id = uniqid();
    $retval = '
        <tr>
            <td>
    ';

    if($type=="C")
    {
        $retval .= Checkbox($property.'_val',$id,Setting::Get($property),'CheckChange'.$id.'();').'
            <input type="checkbox" id="changeCheck'.$id.'" '.((Setting::Get($property)) ? 'checked' : '').' hidden/>
            <script>
                function CheckChange'.$id.'()
                {
                    var settVal = document.getElementById("'.$id.'").checked;
                    var contVal = document.getElementById("changeCheck'.$id.'").checked;

                    if(settVal != contVal) document.getElementById("submitRow'.$id.'").style.display = "table-row";
                    else document.getElementById("submitRow'.$id.'").style.display = "none";
                }
            </script>
        ';
    }
    if($type=="T")
    {
        $retval .= '
            <input type="text"   id="'.$id.'" name="'.$property.'_val" value="'.Setting::Get($property).'" oninput="CheckChange'.$id.'();">
            <input type="hidden" id="changeCheck'.$id.'" value="'.Setting::Get($property).'"/>
            <script>
                function CheckChange'.$id.'()
                {
                    var settVal = document.getElementById("'.$id.'").value;
                    var contVal = document.getElementById("changeCheck'.$id.'").value;

                    if(settVal != contVal) document.getElementById("submitRow'.$id.'").style.display = "table-row";
                    else document.getElementById("submitRow'.$id.'").style.display = "none";
                }
            </script>
        ';
    }
    if($type=="N")
    {
        $retval .= '
            <input type="number" id="'.$id.'" name="'.$property.'_val" class="cel_xs" value="'.Setting::Get($property).'" oninput="CheckChange'.$id.'();">
            <input type="hidden" id="changeCheck'.$id.'" value="'.Setting::Get($property).'"/>
            <script>
                function CheckChange'.$id.'()
                {
                    var settVal = document.getElementById("'.$id.'").value;
                    var contVal = document.getElementById("changeCheck'.$id.'").value;

                    if(settVal != contVal) document.getElementById("submitRow'.$id.'").style.display = "table-row";
                    else document.getElementById("submitRow'.$id.'").style.display = "none";
                }
            </script>
        ';
    }
    if($type=="S")
    {
        $retval .= '<select name="'.$property.'_val" id="'.$id.'" onchange="CheckChange'.$id.'();">';

        foreach($selectArray as $opt)
        {
            $retval.= '<option value="'.$opt.'" '.((Setting::Get($property) == $opt) ? 'selected' : '').'>'.MySQL::Scalar("SELECT name FROM slides WHERE filename = ?",'s',$opt).'</option>';
        }

        $retval .= '
            </select>
            <input type="hidden" id="changeCheck'.$id.'" value="'.Setting::Get($property).'"/>
            <script>
                function CheckChange'.$id.'()
                {

                    var listpre = document.getElementById("'.$id.'");
                    var settVal = listpre.options[listpre.selectedIndex].value;

                    var contVal = document.getElementById("changeCheck'.$id.'").value;

                    if(settVal != contVal) document.getElementById("submitRow'.$id.'").style.display = "table-row";
                    else document.getElementById("submitRow'.$id.'").style.display = "none";
                }
            </script>
        ';
    }


    $retval .= '
            </td>
            <td>'.$description.'</td>
        </tr>
        <tr id="submitRow'.$id.'" style="display: none;">
            <td colspan="3">
                <input type="hidden" name="'.$property.'_inputType" value="'.$type.'"/>
                <button type="submit" class="cel_m cel_h25" name="updateSetting" value="'.$property.'">&Auml;nderungen Speichern</button>
                <button type="button" class="cel_m cel_h25">Zur&uuml;cksetzen</button>
            </td>
        </tr>
    ';



    return $retval;
}

function RefreshSliderContent()
{
    require("mysql_connect.php");
    
    $i=1;
    $sliderImages = '';
    $sliderLimit = Setting::Get("SliderImageCount");
    $today = date("Y-m-d");

    // Default news-images
    $strSQL = "SELECT * FROM news WHERE release_date <= '$today' AND thumbnail NOT LIKE '' AND tags NOT LIKE 'Spieler-des-Monats' ORDER BY release_date DESC, id DESC LIMIT 0,$sliderLimit";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        $uniqID = uniqid();
        $articleUrl = $row['article_url'];
        $thumbnail = $row['thumbnail'];

        $secureSize = 10;
        $qualityMultiplier = 2;

        ResizeImage($thumbnail,"content/news/_slideshow/slide_temp_$i.jpg",(480 + $secureSize) * $qualityMultiplier , (273 + $secureSize) * $qualityMultiplier);
        CropImage("content/news/_slideshow/slide_temp_$i.jpg", "content/news/_slideshow/slide_$i.jpg", (480) * $qualityMultiplier , (273) * $qualityMultiplier);

        $i++;
    }


    // Player of the month
    $strSQL = "SELECT * FROM news WHERE tags = 'Spieler-des-Monats' ORDER BY id DESC LIMIT 0,1";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        $uniqID = uniqid();
        $articleUrl = $row['article_url'];
        $thumbnail = $row['thumbnail'];

        $secureSize = 10;
        $qualityMultiplier = 2;

        ResizeImage($thumbnail,"content/news/_slideshow/slide_temp_sdm.jpg",(480 + $secureSize) * $qualityMultiplier , (273 + $secureSize) * $qualityMultiplier);
        CropImage("content/news/_slideshow/slide_temp_sdm.jpg", "content/news/_slideshow/slide_sdm.jpg", (480) * $qualityMultiplier , (273) * $qualityMultiplier);
    }

}

function ExportCSVAgenda($db,$id="",$multiple="")
{
    // DESCRIPTION:
    // Exports a csv-file that can later be imported into a calendar
    // $db    zentralausschreibungen/agenda
    // $id          id of the entry
    // $multiple    export an entire month/year (format = 2018-02 / 2018)

    $inhalt = "Subject;Start Date;Start Time;End Date;End Time;All Day Event;Description;Location;Private\r\n";
    $path = "files/kalender/";

    require("mysql_connect.php");

    if($multiple == "")
    {
        $strSQL = "SELECT * FROM $db WHERE id = '$id'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            if($db == 'agenda')
            {
                $inhalt .= $row['title'].';'.$row['date_begin'].';'.$row['time_start'].';;;FALSE;'.$row['description'].';'.$row['location'].';FALSE'."\r\n";
                $filename = "Termin-".$row['date_begin'].".csv";
            }
            else
            {
                $inhalt .= $row['title_line1'].$row['title_line2'].';'.$row['date_begin'].';;;;FALSE;;'.$row['hallenname'].' - '.$row['anschrift_halle'].';FALSE'."\r\n";
                $filename = "Termin-".$row['date_begin'].".csv";
            }
        }

        $handle = fopen($path.$filename, 'w');
    }
    else
    {
        if($db == 'agenda') $strSQL = "SELECT * FROM $db WHERE date_begin LIKE '$multiple%'";
        else $strSQL = "SELECT * FROM $db WHERE date_begin LIKE '$multiple%'";

        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            if($db == 'agenda') $inhalt .= $row['title'].';'.$row['date_begin'].';'.$row['time_start'].';;;FALSE;'.$row['description'].';'.$row['location'].';FALSE'."\r\n";
            else $inhalt .= $row['title_line1'].$row['title_line2'].';'.$row['date_begin'].';;;;FALSE;;'.$row['hallenname'].' - '.$row['anschrift_halle'].';FALSE'."\r\n";
        }

        $filename = "Termine-".$multiple.".csv";
        $handle = fopen ($path.$filename, 'w');
    }

    fwrite ($handle, $inhalt);
    fclose ($handle);

    return $path.$filename;
}

function SeachTile($kategory, $id)
{
    if($kategory=="News")
    {
        $val = MySQL::Row("SELECT * FROM news WHERE id = ?",'s',$id);

        echo '
            <a href="/news/artikel/'.$val['article_url'].'" style="text-decoration: none;">
                <div class="search_tile">
                    <div class="prevImg">
                        <img src="'.$val['thumbnail'].'" alt="" />
                    </div>
                    <div class="content_s">
                        <i>News</i><br>
                        <h3>'.$val['title'].'</h3>
                        <p>
                            <span>'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($val['release_date']))).': </span>'.TrimText(str_replace('</p><p>','<br>',NBSPClean(str_replace($val['title'],'',$val['article']))), 400).'
                        </p>
                    </div>
                </div>
            </a>
        ';

    }
    else if($kategory=="Zentralausschreibungen")
    {
        $val = MySQL::Row("SELECT * FROM zentralausschreibungen WHERE id = ?",'s',$id);

        echo '
            <a href="/zentralausschreibung#'.SReplace($val['title_line1'].' '.$val['title_line2']).'" style="text-decoration: none;">
                <div class="search_tile">
                    <div class="content_l">
                        <div class="double_container">
                            <div>
                                <i>Zentralausschreibungen</i><br>
                                <h2 style="color: '.Setting::Get("Color".$val['category']).'">'.$val['title_line1'].'</h2>
                                <h2 style="color: '.Setting::Get("Color".$val['category']).'">'.$val['title_line2'].'</h2>
                                <h3 style="color: #000000">'.str_replace('ä','&auml;',strftime("%A, %d. %B %Y",strtotime($val['date_begin']))).'</h3>
                            </div>
                            <div>
                                <p>
                                    '.ShowZATable($id).'
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        ';
    }
    else if($kategory=="Fotogalerie")
    {
        $val = MySQL::Row("SELECT * FROM fotogalerie WHERE id = ?",'s',$id);

        echo '
            <a href="/fotogalerie/album/'.$val['album_url'].'" style="text-decoration: none;">
                <div class="search_tile">
                    <div class="prevImg">
                        <img src="/content/gallery/'.$val['album_url'].'/'.MySQL::Scalar("SELECT image FROM gallery_images WHERE album_id = ?",'s',$id).'" alt="" />
                    </div>
                    <div class="content_s">
                        <i>Fotogalerie</i><br>
                        <h3>'.$val['album_name'].'</h3>
                        <p>
                            <span>'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($val['event_date']))).': </span>
                            '.str_replace('<p>','',str_replace('</p>','',$val['album_description'])).'
                        </p>
                    </div>
                </div>
            </a>
        ';
    }
    else if($kategory=="Kalender")
    {
        $val = MySQL::Row("SELECT * FROM agenda WHERE id = ?",'s',$id);

        echo '
            <a href="/kalender/event/AG'.$id.'/'.$val['date_begin'].'" style="text-decoration: none;">
                <div class="search_tile">
                    <div class="content_l">
                        <div class="double_container">
                            <div>
                                <i>Kalender</i><br>
                                <h2 style="color: '.Setting::Get("Color".$val['category']).'">'.$val['title'].'</h2>
                                <h3 style="color: #000000">'.str_replace('ä','&auml;',strftime("%A, %d. %B %Y",strtotime($val['date_begin']))).'</h3>
                                <h3 style="color: #000000">'.date_format(date_create($val['time_start']), "H:i").' Uhr</h3>
                            </div>
                            <div>
                                <p>
                                    '.$val['description'].'
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        ';
    }
}

function ShowZATable($id)
{
    $row = MySQL::Row("SELECT * FROM zentralausschreibungen WHERE id = ?",'s',$id);

    $retval = '<table>';

    if($row['act_verein'])
    {
        $retval .= '
            <tr>
                <td class="ta_r"><b>Verein:</b></td>
                <td class="ta_l"><b>'.$row['verein'].'</b></td>
            </tr>
        ';
    }
    if($row['act_uhrzeit'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Uhrzeit:</td>
                <td class="ta_l">'.$row['uhrzeit'].'</td>
            </tr>
        ';
    }
    if($row['act_auslosung'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Auslosung:</td>
                <td class="ta_l">'.$row['auslosung'].'</td>
            </tr>
        ';
    }
    if($row['act_hallenname'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Hallenname:</td>
                <td class="ta_l">'.$row['hallenname'].'</td>
            </tr>
        ';
    }
    if($row['act_anschrift_halle'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Anschrift Halle:</td>
                <td class="ta_l">'.$row['anschrift_halle'].'</td>
            </tr>
        ';
    }
    if($row['act_anzahl_felder'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Anzahl Felder:</td>
                <td class="ta_l">'.$row['anzahl_felder'].'</td>
            </tr>
        ';
    }
    if($row['act_turnierverantwortlicher'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Turnierverantwortlicher:</td>
                <td class="ta_l">'.$row['turnierverantwortlicher'].'</td>
            </tr>
        ';
    }
    if($row['act_oberschiedsrichter'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Oberschiedsrichter:</td>
                <td class="ta_l">'.$row['oberschiedsrichter'].'</td>
            </tr>
        ';
    }
    if($row['act_telefon'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Telefon:</td>
                <td class="ta_l">'.$row['telefon'].'</td>
            </tr>
        ';
    }
    if($row['act_anmeldung_online'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Anmeldung Online:</td>
                <td class="ta_l"><a href="'.$row['anmeldung_online'].'" target="_blank"><b><i>Online-Anmeldung</i></b></a></td>
            </tr>
        ';
    }
    if($row['act_anmeldung_email'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Anmeldung E-Mail:</td>
                <td class="ta_l"><a href="mailto: '.$row['anmeldung_email'].'">'.$row['anmeldung_email'].'</a></td>
            </tr>
        ';
    }
    if($row['act_nennungen_email'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Nennungen E-Mail:</td>
                <td class="ta_l"><a href="mailto: '.$row['nennungen_email'].'">'.$row['nennungen_email'].'</a></td>
            </tr>
        ';
    }
    if($row['act_nennschluss'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Nennschluss:</td>
                <td class="ta_l">'.str_replace('ä','&auml;',strftime("%A, %d. %B %Y",strtotime($row['nennschluss']))).'</td>
            </tr>
        ';
    }
    if($row['act_zusatzangaben'])
    {
        $retval .= '
            <tr>
                <td class="ta_r">Zusatzangaben:</td>
                <td class="ta_l">'.$row['zusatzangaben'].'</td>
            </tr>
        ';
    }

    $retval .= '</table>';

    return $retval;
}


function TagSelector($phpFormName)
{
    require('mysql_connect.php');

    $retval = '
        <input type="search" class="cel_l" id="tagText" placeholder="Tags eingeben... (Mit [Enter] best&auml;tigen)" onkeypress="return TagInsert(event)"/>
        oder   
        <select onchange="TagList();" id="tagList">
            <option value="none" disabled selected>--- Kategorie Ausw&auml;hlen ---</option>
            <optgroup label="Hauptkategorien">
    ';

    $strSQL = "SELECT * FROM news_tags WHERE active = '1'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs)) { $retval .= '<option value="'.$row['name'].'##'.$row['id'].'">'.$row['name'].'</option>'; }

    $retval .= '
            </optgroup>
        </select>

        <input type="hidden" id="tag_nr" value="1"/>
        <input type="hidden" id="tag_str" name="'.$phpFormName.'"/>

        <div class="tag_container" id="tagContainer"></div>
    ';

    return $retval;
}

function Age($birthDate)
{
    $age = date_diff(date_create($birthDate), date_create('now'))->y;

    return $age;
}


function LetterCorrection($input_string)
{
    $input_string = str_replace("Ã¤","ä",$input_string);
    $input_string = str_replace("Ã„","Ä",$input_string);
    $input_string = str_replace("Ã¶","ö",$input_string);
    $input_string = str_replace("Ã–","Ö",$input_string);
    $input_string = str_replace("Ã¼","ü",$input_string);
    $input_string = str_replace("Ãœ","Ü",$input_string);
    $input_string = str_replace("ÃŸ","ß",$input_string);

    return $input_string;
}

function PlayerDisplayClubInfo($row,$editExtension = "", $adminEdit = false, $showControlls = true)
{
    if(isset($_GET['edit']) AND $_GET['edit']==$row['id'].$editExtension)
    {
        $retval = '
            <div class="member_info" style="border-left: 5px groove '.(($row['gender']=='M') ? 'blue' : (($row['gender']=='F') ? 'red' : 'black')).'; height: 150px;">
                <div class="member_image"></div>
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table>
                        </tr>
                            <td>Geschl.: </td>
                            <td>
                                <input type="radio" name="gender" value="M" '.($row['gender'] == 'M' ? 'checked' : '').'/> M&auml;nnl.
                                <input type="radio" name="gender" value="F" '.($row['gender'] == 'F' ? 'checked' : '').'/> Weibl.
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 100px;">Vorn.: </td>
                            <td><input type="text" name="firstname" style="width:160px; margin: 0;" class="cel_h18" value="'.$row['firstname'].'" placeholder="Vorname..."/></td>
                            <td rowspan=5>
                            <button type="submit" name="updateMembers" style="padding: 3px;" value="'.$row['id'].'"><i class="fa fa-floppy-o" style="font-size:24px"></i></button></td>
                        </tr>
                            <td>Nachn.: </td>
                            <td><input type="text" name="lastname" style="width:160px; margin: 0;" class="cel_h18" value="'.$row['lastname'].'" placeholder="Nachname..."/></td>
                        </tr>
                        <tr>
                            <td>Geb.: </td>
                            <td><input type="date" name="birthdate" style="width:160px; margin: 0; font-size: 10pt;" class="cel_h18" value="'.$row['birthdate'].'"/></td>
                        </tr>
                        <tr>
                            <td style="width: 70px;">Mg.Nr.: </td>
                            <td><input type="text" name="number" style="width:160px; margin: 0;" class="cel_h18" value="'.$row['playerID'].'" placeholder="Mitgl. Nr..."/></td>
                        </tr>
                        <tr>
                            <td style="width: 70px;">Bild: </td>
                            <td>'.FileButton("image", "image",false,"","","width: 120px;")  .'</td>
                        </tr>
                    </table>
                </form>
            </div>
        ';
    }
    else
    {
        $retval = '
            <div class="member_info" style="border-left: 5px groove '.(($row['gender']=='M') ? 'blue' : (($row['gender']=='F') ? 'red' : 'black')).';">
                <div class="member_image">
                    <img src="'.(($row['image'] != "") ? ('/content/members/'.$row['image']) : '/content/user.png' ).'" alt="" />
                </div>
                <table>
                    <tr>
                        <td>Vorname: </td>
                        <td>'.$row['firstname'].'</td>
                    </tr>
                    <tr>
                        <td>Nachname: </td>
                        <td>'.$row['lastname'].'</td>
                    </tr>
                    <tr>
                        <td>Geb. Datum: </td>
                        <td>'.str_replace('ä','&auml;',strftime("%d. %b. %Y",strtotime($row['birthdate']))).' ('.Age($row['birthdate']).')</td>
                    </tr>
                    <tr>
                        <td>Mitgl. Nr.: </td>
                        <td>'.(StartsWith($row['playerID'],"TMP") ? '<i>-</i>' : $row['playerID']).'</td>
                    </tr>
                </table>

                <div style="position: absolute; bottom: 0px; right: 0px;">
                ';
                    if($showControlls)
                    {
                        if($adminEdit) $editLink = "/mitglieder/anzeigen?verein=".$_GET['verein']."&edit=".$row['id'].$editExtension;
                        else $editLink = "/verein-info/mitglieder?edit=".$row['id'].$editExtension;

                        $retval .= '<a title="Verein wechseln" href="/verein-info/mitglieder/verein-wechseln/'.$row['id'].'"><i class="fas fa-bus"></i></a>';
                        $retval .= EditButton($editLink,true);
                        if(StartsWith($row['playerID'],"TMP")) $retval .= '<a title="Zusammenf&uuml;hren" href="/verein-info/mitglieder/zusammenfuehren/'.$row['id'].'"><i class="fas fa-compress"></i></a>';
                        $retval .= DeleteButton("ClubManager","members",$row['id'],true);
                    }
                $retval .= '
                </div>
            </div>
        ';
    }

    return $retval;
}

function SRLLockUpdater()
{
    // Auto-Lock SRL at the end of seasons

    for($i = intval(date("Y")); $i > 2011 ; $i --)
    {
        $year = ($i-1).'-'.($i);
        if(intval(date("Y")) > $i) $locked = '1';
        else
        {
            // Saisonwechsel mit 1. September
            // Sperren der Spielerrangliste mit 1. Oktober
            if(intval(date("m"))>= 10) $locked = '1';
            else $locked = '0';
        }


        if(MySQL::Exist("SELECT * FROM ranglisten_settings WHERE setting = 'Y".$year."IsLocked'"))
        {
            MySQL::NonQuery("UPDATE ranglisten_settings SET value = '$locked' WHERE setting = 'Y".$year."IsLocked'");
        }
        else MySQL::NonQuery("INSERT INTO ranglisten_settings (setting,value) VALUES ('Y".$year."IsLocked','$locked')");

    }
}

function SRLIsLocked($year)
{
    if(MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = 'Y".$year."IsLocked'") == '1') return true;
    else return false;
}

?>