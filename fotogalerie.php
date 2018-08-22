<?php
    require("header.php");
    PageTitle("Fotogalerie");


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
            <h1 class="stagfade1">Fotogallerie</h1>
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
         $strSQL = "SELECT * FROM fotogalerie";
         $rs=mysqli_query($link,$strSQL);
         while($row=mysqli_fetch_assoc($rs))
        {
         echo'
          <div class="gallery_album">
            <div>
  	            <img src=""/>
                    </div>
                <div>
                    <h3> '.$row['AlbumName'].'</h3>
                    <p>
                    	Beschreibung hier
                    </p>
                </div>
            </div>

            ';
        }





       ';
    }


    include("footer.php");
?>