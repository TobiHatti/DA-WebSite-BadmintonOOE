<?php
    require("header.php");
    PageTitle("Fotogalierie");


     if(isset($_POST['add_album']))
     {
        $albumName = $_POST['album_name'];
        $today = date("Y-m-d");
        $user = $_SESSION['user_id'];

        MySQLNonQuery("INSERT INTO fotogalerie (ID,AlbumName, ErstellungsDatum, Autor) VALUES ('','$albumName','$today','$user')");

        $albID = Fetch("fotogalerie","id","AlbumName",$albumName);
        FileUpload("content/gallery/".SReplace($albumName)."/", "images" ,"","","INSERT INTO gallery_images (id,album_id,image) VALUES ('','$albID','FNAME')");
    }


    if(isset($_GET['neu']))
    {
        echo '
            <h1 class="stagfade1">Fotogalierie</h1>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <br>
                <input type="text" placeholder="Album Name" name="album_name"/>
                <br>
                <br>
                <br>
                '.FileButton("images", "element-id", 1).'
                <br>
                <button type="submit" name="add_album" value="post-value">Album hinzuf&uuml;gen
           </form>
        ';
    }
    else
    {
        // Hier die Anzeige für die Gallerie
    }


    include("footer.php");
?>