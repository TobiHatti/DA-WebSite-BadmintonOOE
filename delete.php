<?php
    require("header.php");

    if(CheckPermission("Delete".$_GET['s']) OR $_GET['s'] == 'ClubManager')
    {
        if(isset($_POST['delete']))
        {
            $table = $_GET['t'];
            $id = $_GET['i'];
            MySQLNonQuery("DELETE FROM $table WHERE id = '$id'");
            echo '
                <script>
                    window.history.back();
                    window.history.back();
                </script>
            ';
        }

        if(isset($_POST['abort']))
        {
            echo '
                <script>
                    window.history.back();
                    window.history.back();
                </script>
            ';
        }

        echo '
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <center>
                    <br>
                    <h1 class="stagfade1">Eintrag wirklich l&ouml;schen?</h1>
                    <br>
                    <h2 class="stagfade2" style="color: #000000">Nach dem l&ouml;schen kann der Eintrag nicht wiederhergestellt werden!</h2>
                    <br><br>
                    <button type="submit" name="delete" class="cef_warning">L&ouml;schen</button>
                    <button type="submit" name="abort">Abbrechen</button>
                </center>
            </form>
        ';
    }


    include("footer.php");
?>