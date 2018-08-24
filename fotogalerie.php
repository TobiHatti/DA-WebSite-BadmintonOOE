<?php
    require("header.php");
    PageTitle("Fotogalerie");


     if(isset($_POST['add_album']))
     {
        $albumName = $_POST['album_name'];
        $today = date("Y-m-d");
        $user = $_SESSION['user_id'];

        MySQLNonQuery("INSERT INTO  (ID,AlbumName, ErstellungsDatum, Autor) VALUES ('','$albumName','$today','$user')");

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
                <div class="image_preview">
                    ';

                    //Fotoanzahl 1
                    $albumname = $row['AlbumName'];
                    if(MySQLCount("SELECT id FROM fotogalerie WHERE AlbumName = '$albumname'")==1)
                    {
                     $id = $row['ID'];
                     $strSQLI = "SELECT * FROM gallery_images INNER JOIN fotogalerie ON gallery_images.album_id = fotogalerie.ID WHERE fotogalerie.ID = '$id' LIMIT 0,1";
                     $rsI=mysqli_query($link,$strSQLI);
                     while($rowI=mysqli_fetch_assoc($rsI))
                     {
                         echo '<img src="/content/gallery/'.$row['AlbumName'].'/'.$rowI['image'].'" id="img1">';
                     }
                    }else if(MySQLCount("SELECT id FROM fotogalerie WHERE AlbumName = '$albumname'")>=4)
                    {
                        $i=1;
                        $id = $row['ID'];
                        $strSQLI = "SELECT * FROM gallery_images INNER JOIN fotogalerie ON gallery_images.album_id = fotogalerie.ID WHERE fotogalerie.ID = '$id' LIMIT 0,4";
                        $rsI=mysqli_query($link,$strSQLI);
                        while($rowI=mysqli_fetch_assoc($rsI))
                        {
                            echo '<img src="/content/gallery/'.$row['AlbumName'].'/'.$rowI['image'].'" id="img'.$i.'">';
                            $i++;
                        }
                    }

                    else if(MySQLCount("SELECT id FROM fotogalerie WHERE AlbumName = '$albumname'")>=9)
                    {
                        $i=1;
                        $id = $row['ID'];
                        $strSQLI = "SELECT * FROM gallery_images INNER JOIN fotogalerie ON gallery_images.album_id = fotogalerie.ID WHERE fotogalerie.ID = '$id' LIMIT 0,9";
                        $rsI=mysqli_query($link,$strSQLI);
                        while($rowI=mysqli_fetch_assoc($rsI))
                        {
                            echo '<img src="/content/gallery/'.$row['AlbumName'].'/'.$rowI['image'].'" id="img'.$i.'">';
                            $i++;
                        }

                    }
                      	                // Mit MySQLCount() abfragen, wieviele Fotos in einem Album vorhanden sind.
                                        // Je nach Anzahl bestimmte bedingung auslösen:
                                        // Fotoanzahl == 1  (1 Foto wird in der Vorschau angezeigt)
                                        // Fotoanzahl >= 4  (4 Fotos werden in der Vorschau angezeigt)
                                        // Fotoanzahl >= 9  (9 Fotos werden in der Vorschau angezeigt)

                                        // Tipp:
                                        //
                                        // if(MySQLCount("SELECT id FROM fotogalerie WHERE AlbumName = '$albumname'") >=9)
                                        // { ... }
                                        // else if(...)
                                        // { ... }
                                        // ...

                                        echo '
                                    </div>
                                    <div class="album_description">
                                        <h3> '.$row['AlbumName'].'</h3>
                                        <p>
                                        	Beschreibung hier
                                        </p>
                                    </div>
                                </div>

                                ';
                            }
                        }


    include("footer.php");
?>