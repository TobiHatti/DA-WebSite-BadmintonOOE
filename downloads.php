<?php
    require("header.php");
    PageTitle("Downloads");

    if(isset($_POST['addFile']))
    {
        $displayName = $_POST['name'];

        if($displayName == '') $strSQL = "INSERT INTO downloads (id,name,file) VALUES ('','FNAME','FNAME')";
        else $strSQL = "INSERT INTO downloads (id,name,file) VALUES ('','$displayName','FNAME')";

        FileUpload("/files/downloads/","file","","",$strSQL);

        Redirect(ThisPage());
        die();
    }


    echo '
        <h1 class="stagfade1">Downloads</h1>

        <p>
        ';

        $strSQL = "SELECT * FROM downloads";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo FileList('/files/downloads/'.$row['file'], $row['name']);

            if(CheckPermission("ChangeContent"))
            {
                echo DeleteButton("CC","downloads",$row['id']);
            }

            echo '<br><br>';
        }

        echo '
        </p>
    ';


    if(CheckPermission("ChangeContent"))
    {
        echo '
            <a href="#addfile"><button type="button">Datei hinzuf&uuml;gen</button></a>


            <div class="modal_wrapper" id="addfile">
                <a href="#c">
                    <div class="modal_bg"></div>
                </a>
                <div class="modal_container" style="width: 300px; height: 200px">
                    <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <h2>Datei hinzuf&uuml;gen</h2>
                        <br>
                        <center>
                            <input type="text" name="name" class="cel_l" placeholder="Anzeigename (optional)"/><br>
                            '.FileButton("file", "file").'
                            <br><br>
                            <button type="submit" name="addFile">Hinzuf&uuml;gen</button>
                        </center>
                    </form>
                </div>
            </div>
        ';
    }

    include("footer.php");
?>