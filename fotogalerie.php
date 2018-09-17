<?php
    require("header.php");
    PageTitle("Fotogalerie");


     if(isset($_POST['add_album']))
     {
        $albumName = $_POST['album_name'];
        $albumUrl = SReplace($_POST['album_name']);
        $today = date("Y-m-d");
        $tags = SReplace($_POST['tags']);
        $album_description= $_POST['description'];
        $album_ort=(isset($_POST['OrtAlbum'])) ? $_POST['OrtAlbum'] : '';
        $album_date=(isset($_POST['DateAlbum'])) ? $_POST['DateAlbum'] : '';
        $download = (isset($_POST['download'])) ? 1 : 0 ;
        $showDateLoc = (isset($_POST['showDateLoc'])) ? 1 : 0 ;
        $user = $_SESSION['userID'];


        MySQLNonQuery("INSERT INTO fotogalerie (id, album_url, album_name, album_description, event_location, event_date, allowDownload, creation_date, tags, author,show_dateloc) VALUES ('','$albumUrl','$albumName','$album_description','$album_ort','$album_date','$download','$today','$tags','$user','$showDateLoc')");

        $albID = Fetch("fotogalerie","id","album_name",$albumName);
        FileUpload("content/gallery/".$albumUrl."/", "images" ,"","","INSERT INTO gallery_images (id,album_id,image) VALUES ('','$albID','FNAME')");

        Redirect("/fotogalerie");
        die();
    }

    if(isset($_POST['updateGallery']))
    {
        $id = $_POST['updateGallery'];
        $albumName = $_POST['album_name'];
        $tags = SReplace($_POST['tags']);
        $album_description= $_POST['description'];
        $album_ort=(isset($_POST['OrtAlbum'])) ? $_POST['OrtAlbum'] : '';
        $album_date=(isset($_POST['DateAlbum'])) ? $_POST['DateAlbum'] : '';
        $download = (isset($_POST['download'])) ? 1 : 0 ;
        $showDateLoc = (isset($_POST['showDateLoc'])) ? 1 : 0 ;

        $strSQL = "UPDATE fotogalerie SET
        album_name = '$albumName',
        album_description = '$album_description',
        event_date = '$album_date',
        event_location = '$album_ort',
        allowDownload = '$download',
        show_dateloc = '$showDateLoc',
        tags = '$tags'
        WHERE id = '$id';
        ";

        MySQLNonQuery($strSQL);
        Redirect("/fotogalerie");
        die();
    }

    if(isset($_POST['addMoreImg']))
     {
        $albID = $_POST['addMoreImg'];
        $albumUrl = $_POST['album_url'];

        FileUpload("content/gallery/".$albumUrl."/", "addImages" ,"","","INSERT INTO gallery_images (id,album_id,image) VALUES ('','$albID','FNAME')");

        Redirect(ThisPage("!#"));
        die();
    }

    if(isset($_POST['download_zip']))
    {
        $album_path = $_POST['download_zip'];

        $verzeichnis = 'content/gallery/'.$album_path.'/';
        $zip_name = 'content/gallery/'.$album_path.".zip";

        $dateien = array_slice(scanDir($verzeichnis), 2);

        $zip = new ZipArchive;

        if (!file_exists($zip_name)) $status = $zip->open($zip_name, ZipArchive::CREATE);
        else $status = $zip->open($zip_name, ZipArchive::OVERWRITE);

        if($status)
        {
            foreach ($dateien as $datei) $zip->addFile($verzeichnis . $datei, $datei);

            $zip->close();

            if(file_exists($zip_name)) Redirect("/forceDownload?file=".urlencode($zip_name));
        }
    }


    if(CheckPermission("AddGallery") AND isset($_GET['neu']))
    {
        echo '
            <h2 class="stagfade1">Neue Fotogalerie erstellen</h1>
            <hr>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
                <div class="stagfade2">
                    <p>Vergeben Sie einen Namen und bei wunsch auch eine Beschreibung an das Album:</p>
                    <input type="text" placeholder="Titel" name="album_name" class="cel_100"/>
                    <br><br>
                    '.TextareaPlus("description", "description").'
                    <br>
                    <hr>
                </div>
                <div class="stagfade3">
                    <h3>Ort und Datum angeben</h3>
                    Geben Sie an, wann und wo das Event stattgefunden hat:<br>
                    <br>
                    <table>
                        <tr>
                            <td class="ta_r" style="width: 10%">Ort: </td>
                            <td style="width: 40%"><input id="gallocation" type="text" placeholder="Ort" name="OrtAlbum"/></td>
                            <td class="ta_r" style="width: 10%">Datum: </td>
                            <td style="width: 40%"><input id="galdate" type="date" name="DateAlbum" value="'.date("Y-m-d").'"/> </td>
                        </tr>
                        <tr>
                            <td colspan=2 class="ta_r">Ort und Datum anzeigen:</td>
                            <td colspan=2>'.Checkbox("showDateLoc","dateloc",true,"ChangeDateLocActive(this);").'</td>
                        </tr>
                    </table>
                    <br>
                    <hr>
                </div>

                <div class="stagfade4">
                    <h3>Tags</h3>
                        F&uuml;gen Sie dem Album Tags hinzu, um es schneller finden und sortieren zu k&ouml;nnen:<br><br>
                        <input type="search" class="cel_l" id="tagText" placeholder="Tags eingeben... (Mit [Enter] best&auml;tigen)" onkeypress="return TagInsert(event)"/>
                        oder
                        <select onchange="TagList();" id="tagList">
                            <option value="none" disabled selected>--- Kategorie Ausw&auml;hlen ---</option>
                            <optgroup label="Hauptkategorien">
                                ';
                                $strSQL = "SELECT * FROM news_tags";
                                $rs=mysqli_query($link,$strSQL);
                                while($row=mysqli_fetch_assoc($rs)) { echo '<option value="'.$row['name'].'">'.$row['name'].'</option>'; }
                                echo '
                            </optgroup>
                        </select>

                        <input type="hidden" id="tag_nr" value="1"/>
                        <input type="hidden" id="tag_str" name="tags"/>

                        <div class="tag_container" id="tagContainer"></div>
                    <br>
                    <hr>
                </div>

                <div class="stagfade5">
                    <h3>Fotos ausw&auml;hlen</h3>
                    W&auml;hlen Sie die gew&uuml;nschten Fotos aus (Max. 200 Fotos):<br>
                    <span style="color: #696969">Zus&auml;tzlich k&ouml;nnen Sie noch angeben, ob das Album zum Download zur verf&uuml;gung steht</span><br>
                    <br>
                    <table>
                        <tr>
                            <td style="width: 50%">'.FileButton("images", "images", 1).'</td>
                            <td style="width: 10%">'.Checkbox("download","download",0).'</td>
                            <td style="width: 40%">Download Erlauben </td>
                        </tr>
                    </table>


                    <hr>
                </div>
                <div class="stagfade6">
                    <br>
                    <button type="submit" name="add_album" value="post-value">Album hinzuf&uuml;gen
                </div>
           </form>
        ';
    }
    else if(isset($_GET['album']))
    {
       echo '
       <h1 class="stagfade1">'.Fetch("fotogalerie","album_name","album_url",$_GET['album']).'</h1>
       <form action="'.ThisPage("!#").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
       <br>
       ';

       if(CheckPermission("AddGallery"))
       {
            echo '
                <a href="#addImages"><button type="button"><b>&#65291;</b> Weitere Fotos hinzuf&uuml;gen</button></a>

                <div class="gallery_addimg_wrapper" id="addImages">
                    <a href="#c">
                        <div class="gallery_addimg_bg"></div>
                    </a>
                    <div class="info_container">
                        <h3>Weitere Fotos hinzuf&uuml;gen</h3>
                        F&uuml;gen Sie weitere Fotos zu dieser Galerie hinzu:
                        <br><br>
                        <center>
                            <form action="'.ThisPage("!#").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                '.FileButton("addImages","addImg", true).'
                                <br>
                                <input type="hidden" value="'.$_GET['album'].'" name="album_url"/>
                                <button type="submit" name="addMoreImg" value="'.Fetch("fotogalerie","id","album_url",$_GET['album']).'">Fotos hinzuf&uuml;gen</button>
                            </form>
                        </center>
                    </div>
                </div>
            ';
       }

       $allowDownload = Fetch("fotogalerie","allowDownload","album_url",$_GET['album']);

       if($allowDownload) echo '<button type="submit" name="download_zip" value="'.$_GET['album'].'" style="float: right;"><i class="fa fa-download"></i> Album Herunterladen</button><br><br><br>';

       echo '

       <center>
       ';

        $album_path = $_GET['album'];
        $album_name = Fetch("fotogalerie","album_name","album_url",$_GET['album']);
        $album_id = Fetch("fotogalerie","id","album_url",$_GET['album']);


        $entriesPerPage = GetProperty("PagerSizeGalleryImage");
        $offset = ((isset($_GET['page'])) ? $_GET['page']-1 : 0 ) * $entriesPerPage;

        $i= 1 + ((isset($_GET['page'])) ? $_GET['page']-1 : 0 )*$entriesPerPage;
        $strSQL = "SELECT * FROM gallery_images WHERE album_id = '$album_id' LIMIT $offset,$entriesPerPage";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <a href="#galleryView" onclick="SelectGalleryImage('.$row['id'].');">
                    <div class="gallery_image_thumb">
                        <center><img src="/content/gallery/'.$_GET['album'].'/'.$row['image'].'" alt="" id="galleryImg'.$row['id'].'"/></center>
                        <p>
                            '.$album_name.' ('.$i++.')
                            <br>
                            <span>'.$row['image'].'</span>
                        </p>
                    </div>
                </a>


            ';
        }

        echo '
        </center>
            <div class="gallery_view_wrapper" id="galleryView" >
                <a href="#">
                    <div class="gallery_view_container"></div>
                </a>
                <div class="image_container">
                    <a href="#"><img src="/content/cross2.png" alt="" class="close_cross"/></a>
                    <a><img src="/content/left.png" alt="" class="back" onclick="SelectLastImage();"/></a>
                    <a><img src="/content/right.png" alt="" class="next" onclick="SelectNextImage();"/></a>
                    <input type="hidden" value="'.$allowDownload.'" id="galleryAllowDownload"/>
                    '.(($allowDownload) ? '<a href="" id="galleryDownload" download><button type="button" title="Bild Herunterladen"><i class="fa fa-download"></i></button></a>' : '').'
                    <img src="" alt="" class="gallery_image" id="galleryFullSized"/>
                    ';

                    if(CheckPermission("DeleteGallery"))
                    {
                        echo '<span style="position: absolute; bottom: 5px; right: 5px;"><a style="margin: 0px 3px; color: red;" href="/delete/gallery_images/Gallery/0" id="galleryDeleteLink"> &#10006;L&ouml;schen</a></span>';
                    }

                    echo '
                </div>
            </div>

            <input type="hidden" id="currentImageID"/>
        ';

        echo Pager("SELECT * FROM gallery_images WHERE album_id = '$album_id'",$entriesPerPage);

    }
    else
    {
        echo '<h1 class="stagfade1">Fotogalerie</h1>';

        if(CheckPermission("AddGallery"))
        {
            echo AddButton(ThisPage("!editSC","!#edit","+neu"));
        }

        $entriesPerPage = GetProperty("PagerSizeGalleryAlbum");
        $offset = ((isset($_GET['page'])) ? $_GET['page']-1 : 0 ) * $entriesPerPage;

        $strSQL = "SELECT * FROM fotogalerie LIMIT $offset,$entriesPerPage";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {

            if(isset($_GET['editSC']) AND $_GET['editSC']==$row['id'] AND CheckPermission("EditGallery"))
            {
                echo '<a name="edit"></a>';

                echo '
                    <div class="gallery_album">
                        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
                            <center>
                                <table style="width: 90%;">
                                    <tr>
                                        <td class="ta_r">Titel</td>
                                        <td><input name="album_name" type="text" class="cel_100" value="'.$row['album_name'].'"/></td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>'.TextareaPlus("description","description",$row['album_description']).'<br></td>
                                    </tr>
                                    <tr>
                                        <td class="ta_r">Ort/Datum anzeigen</td>
                                        <td>'.Checkbox("showDateLoc","dateloc",$row['show_dateloc'],"ChangeDateLocActive(this);").'</td>
                                    </tr>
                                    <tr>
                                        <td class="ta_r">Ort</td>
                                        <td><input name="OrtAlbum" id="gallocation" type="text" value="'.$row['event_location'].'"/></td>
                                    </tr>
                                    <tr>
                                        <td class="ta_r">Datum</td>
                                        <td><input type="date" id="galdate" name="DateAlbum" value="'.$row['event_date'].'"/></td>
                                    </tr>
                                    <tr>
                                        <td class="ta_r">Tags</td>
                                        <td>
                                            <input type="search" class="cel_l" id="tagText" placeholder="Tags eingeben... (Mit [Enter] best&auml;tigen)" onkeypress="return TagInsert(event)"/>
                                            oder
                                            <select onchange="TagList();" id="tagList">
                                                <option value="none" disabled selected>--- Kategorie Ausw&auml;hlen ---</option>
                                                <optgroup label="Hauptkategorien">
                                                    ';
                                                    $strSQLT = "SELECT * FROM news_tags";
                                                    $rsT=mysqli_query($link,$strSQLT);
                                                    while($rowT=mysqli_fetch_assoc($rsT)) { echo '<option value="'.$rowT['name'].'">'.$rowT['name'].'</option>'; }
                                                    echo '
                                                </optgroup>
                                            </select>

                                            <input type="hidden" id="tag_nr" value="1"/>
                                            <input type="hidden" id="tag_str" name="tags" value="'.$row['tags'].'"/>

                                            <div class="tag_container" id="tagContainer"></div>

                                            <script>
                                                window.onload = function () {
                                                    LoadTags();
                                                }
                                            </script>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ta_r">Download erlauben</td>
                                        <td>'.Checkbox("download","download",$row['allowDownload']).'</td>
                                    </tr>
                                </table>
                                <br>
                                <button type="submit" name="updateGallery" value="'.$row['id'].'">Aktualisieren</button>
                                <a href="/fotogalerie"><button type="button">&Auml;nderungen verwerfen</button></a>
                            </form>
                        </center>
                    </div>
                ';
            }
            else
            {
                echo'
                    <div class="gallery_album">
                        <div class="image_preview">
                            <a href="/fotogalerie/album/'.$row['album_url'].'">
                ';


                $albumId = $row['id'];

                if(MySQLCount("SELECT id FROM gallery_images WHERE album_id = '$albumId'")>=9)
                {
                    $i=1;
                    $strSQLI = "SELECT * FROM gallery_images INNER JOIN fotogalerie ON gallery_images.album_id = fotogalerie.ID WHERE fotogalerie.ID = '$albumId' LIMIT 0,9";
                    $rsI=mysqli_query($link,$strSQLI);
                    while($rowI=mysqli_fetch_assoc($rsI)) { echo '<img src="/content/gallery/'.$row['album_url'].'/'.$rowI['image'].'" id="img'.$i++.'">'; }
                }
                else if(MySQLCount("SELECT id FROM gallery_images WHERE album_id = '$albumId'")>=4)
                {
                    $i=1;
                    $strSQLI = "SELECT * FROM gallery_images INNER JOIN fotogalerie ON gallery_images.album_id = fotogalerie.ID WHERE fotogalerie.ID = '$albumId' LIMIT 0,4";
                    $rsI=mysqli_query($link,$strSQLI);
                    while($rowI=mysqli_fetch_assoc($rsI)) { echo '<img src="/content/gallery/'.$row['album_url'].'/'.$rowI['image'].'" id="img'.$i++.'">'; }
                }
                else if(MySQLCount("SELECT id FROM gallery_images WHERE album_id = '$albumId'") < 4)
                {
                    $strSQLI = "SELECT * FROM gallery_images INNER JOIN fotogalerie ON gallery_images.album_id = fotogalerie.ID WHERE fotogalerie.ID = '$albumId' LIMIT 0,1";
                    $rsI=mysqli_query($link,$strSQLI);
                    while($rowI=mysqli_fetch_assoc($rsI)) { echo '<img src="/content/gallery/'.$row['album_url'].'/'.$rowI['image'].'" id="img1">'; }
                }


                echo '
                                </a>
                            </div>
                            <div class="album_description">
                                <a href="/fotogalerie/album/'.$row['album_url'].'"><h3> '.$row['album_name'].'</h3></a>
                                <p>'.FroalaContent($row['album_description']).'</p>
                                ';

                                if($row['show_dateloc'])
                                {
                                    if($row['event_location']=='') echo '<span class="dateloc">'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['event_date']))).'</span>';
                                    else if($row['event_date']=='0000-00-00') echo '<span class="dateloc">'.$row['event_location'].'</span>';
                                    else echo '<span class="dateloc">'.$row['event_location'].', '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['event_date']))).'</span>';
                                }

                                if(CheckPermission("EditGallery"))
                                {
                                    echo '<span style="float: right;"> '.EditButton(ThisPage("!editContent","!editSC","+editSC=".$row['id'],"#edit")).' </span>';
                                }

                                if(CheckPermission("DeleteGallery"))
                                {
                                    echo '<span style="float: right;"> '.DeleteButton("Gallery","fotogalerie",$row['id']).' </span>';
                                }

                            echo '
                            </div>
                        </div>
                    </a>
                ';
            }
        }




        echo Pager("SELECT * FROM fotogalerie",$entriesPerPage);

    }


    include("footer.php");
?>