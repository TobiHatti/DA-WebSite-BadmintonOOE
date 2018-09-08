<?php
    require("header.php");
    PageTitle("Fotogalerie");


     if(isset($_POST['add_album']))
     {
        $albumName = $_POST['album_name'];
        $albumUrl = SReplace($_POST['album_name']);
        $today = date("Y-m-d");
        $album_description=$_POST['description'];
        $album_ort=$_POST['OrtAlbum'];
        $album_date=$_POST['DateAlbum'];
        $download = (isset($_POST['download'])) ? 1 : 0 ;
        $user = $_SESSION['user_id'];


        MySQLNonQuery("INSERT INTO fotogalerie (id, album_url, album_name, album_description, event_location, event_date, allowDownload, creation_date, author) VALUES ('','$albumUrl','$albumName','$album_description','$album_ort','$album_date','$download','$today','$user')");

        $albID = Fetch("fotogalerie","id","album_name",$albumName);
        FileUpload("content/gallery/".$albumUrl."/", "images" ,"","","INSERT INTO gallery_images (id,album_id,image) VALUES ('','$albID','FNAME')");
    }

    if(isset($_POST['download_zip']))
    {
        $album_path = $_POST['download_zip'];
        $album_name = Fetch("fotogalerie","album_name","album_url",$album_path);
        $album_id = Fetch("fotogalerie","id","album_url",$album_path);

        //Zip-Ordner erstellen

        $verzeichnis = 'content/gallery/'.$album_path;
        $zip_name = "AlbumDownload.zip";

        // Verzeichnis auslesen
        $dateien = array_slice(scanDir($verzeichnis), 2);

        // Neue Instanz der ZipArchive Klasse erzeugen
        $zip = new ZipArchive;

        if (!file_exists($zip_name)) {
         // Zip-Archiv erstellen
         $status = $zip->open($zip_name, ZipArchive::CREATE);
        }
        else {
         // Zip-Archiv �berschreiben
         $status = $zip->open($zip_name, ZipArchive::OVERWRITE);
        }

        if ($status) {

         // Dateien ins Zip-Archiv einf�gen
         foreach ($dateien as $datei) {
          $zip->addFile($verzeichnis . $datei, $datei);
         }

        // Zip-Archiv schlie�en
         $zip->close();

         if (file_exists($zip_name)) {

          // Dateigr��e ermitteln
          $info = stat($zip_name);
          echo '
            <a href="' . $zip_name . '" download>' . $zip_name . '</a> - ' .
            number_format(round($info["size"] / 1024 ,1), 2, ",", ".") .' KB
          ';
         }
        }


    }


    if(isset($_GET['neu']))
    {
        echo '
            <h1 class="stagfade1">Fotogallerie</h1>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <br>
                <input type="text" placeholder="Album Name" name="album_name"/>
                <br>
                <br>
                '.TextareaPlus("description", "description", "Beschreibung hier eingeben").'
                <br>
                <br>
                <input type="text" placeholder="Ort" name="OrtAlbum"/>
                <br>
                <br>
                <input type="date" name="DateAlbum"/>
                <br>
                <br>
                '.FileButton("images", "images", 1).'
                <br>
                '.Checkbox("download","download",0).'Download Erlauben
                <br>
                <br>
                <button type="submit" name="add_album" value="post-value">Album hinzuf&uuml;gen

           </form>
        ';
    }
    else if(isset($_GET['album']))
    {
       echo '
       <h1 class="stagfade1">'.Fetch("fotogalerie","album_name","album_url",$_GET['album']).'</h1>
       <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
       <br>
       <button type="submit" name="download_zip" value="'.$_GET['album'].'">Download</button>
       <br>
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
           // Hier SQL-Query schleife mit untenstehendem echo f�r jedes Foto:


            echo '
                <a href="#galleryView" onclick="SelectGalleryImage('.$i.');">
                    <div class="gallery_image_thumb">
                        <center><img src="/content/gallery/'.$_GET['album'].'/'.$row['image'].'" alt="" id="galleryImg'.$i.'"/></center>
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
                    <img src="" alt="" class="gallery_image" id="galleryFullSized"/>
                </div>
            </div>

            <input type="hidden" id="currentImageID"/>
        ';

        echo Pager("SELECT * FROM gallery_images WHERE album_id = '$album_id'",$entriesPerPage);

    }
    else
    {
        echo '<h1 class="stagfade1">Fotogalerie</h1>';

        echo '
            <b><u>ToDo Fotogalerie:</u></b><br><br>
            <u>PHP-Form:</u><br>
            Tag-Funktion einf&uuml;gen (Tobi)<br>
            <br>
            <u>Fotogalerie: Alben:</u><br>
            <br>
            <u>Fotogalerie: Fotovorschau:</u><br>
            Download-Button<br>

            <br><br><br>


        ';

        $entriesPerPage = GetProperty("PagerSizeGalleryAlbum");
        $offset = ((isset($_GET['page'])) ? $_GET['page']-1 : 0 ) * $entriesPerPage;

        $strSQL = "SELECT * FROM fotogalerie LIMIT $offset,$entriesPerPage";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo'
                <a href="/fotogalerie/album/'.$row['album_url'].'" style="text-decoration:none;">
                    <div class="gallery_album">
                        <div class="image_preview">
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
                        </div>
                        <div class="album_description">
                            <h3> '.$row['album_name'].'</h3>
                            <p>
                                '.$row['album_description'].'
                            </p>
                            <p>
                                '.$row['event_location'].'
                            </p>
                            <p>
                                 '.$row['event_date'].'
                            </p>
                        </div>
                    </div>
                </a>
            ';
        }




        echo Pager("SELECT * FROM fotogalerie",$entriesPerPage);

    }


    include("footer.php");
?>