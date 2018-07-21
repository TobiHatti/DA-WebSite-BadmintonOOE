<?php
    require("header.php");

    echo '
        <h1 class="stagfade1">Temporary Form-File</h1>

        <h3 class="stagfade2">Use temporarily for SQL/PHP Forms and Database insertions</h3>
        <br><br><br>

        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">

            <input type="text" class="cel_xxs" placeholder="Kurzer Text..."/><br>
            <input type="text" class="cel_xs" placeholder="Kurzer Text..."/><br>
            <input type="text" class="cel_s" placeholder="Kurzer Text..."/><br>
            <input type="text" class="cel_m" placeholder="Kurzer Text..."/><br>
            <input type="text" class="cel_l" placeholder="Kurzer Text..."/><br>
            <input type="text" class="cel_xl" placeholder="Kurzer Text..."/><br>
            <input type="text" class="cel_xxl" placeholder="Kurzer Text..."/><br><br>

            <input type="text" class="cel_100" placeholder="Kurzer Text..."/><br>
            <input type="text" class="cel_50" placeholder="Kurzer Text..."/><br>
            <input type="text" class="cel_33" placeholder="Kurzer Text..."/><br>
            <input type="text" class="cel_25" placeholder="Kurzer Text..."/><br>

        </form>




    ';

    require("footer.php");
?>