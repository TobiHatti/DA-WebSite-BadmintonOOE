<?php
    require("header.php");
    PageTitle("Downloads");

    if(isset($_POST['add_data'])){

    }
    echo '
        <h1 class="stagfade1">Downloads</h1>
        <p>
           '.PageContent('1',CheckPermission("ChangeContent")).'
        </p>
        <div>
            <br>
            <button type="submit" name="add_data" value="post-value">Datei ausw&auml;hlen
        </div>
    ';

    include("footer.php");
?>