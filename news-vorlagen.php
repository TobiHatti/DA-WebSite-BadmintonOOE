<?php
    require("header.php");

    echo '
        <h2>Neue Vorlage erstellen</h2>
        <hr>
        <br>
        <div class="double_container">
            <div style="min-width: 640px;">
                <center>
                    <div style="position: relative; width: 640px; height: 360px; background: #E0F0FF; background-image: url(\'content/newstemplates/testtemplate.jpg\'); background-size: cover;">

                    </div>
                </center>
            </div>
            <div>
                <table style="font-size: 13pt; display: inline-table; vertical-align: top">
                    <tr>
                        <td><h4>Text 1</h4></td>
                        <td>'.Checkbox("chT1","chT1").'</td>
                    </tr>
                    <tr>
                        <td class="ta_r">Textgr&ouml;&szlig;e</td>
                        <td><input style="width: 120px" type="range" min="5" max="40"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Farbe</td>
                        <td>'.ColorPicker("post-name", "element-id", "Farbe wählen", "#F01B16").'</td>
                    </tr>

                    <tr><td colspan=2><hr></td></tr>

                    <tr>
                        <td><h4>Text 2</h4></td>
                        <td>'.Checkbox("chT2","chT2").'</td>
                    </tr>
                    <tr>
                        <td class="ta_r">Textgr&ouml;&szlig;e</td>
                        <td><input style="width: 120px" type="range" min="5" max="40"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Farbe</td>
                        <td>'.ColorPicker("post-name", "element-id", "Farbe wählen", "#F01B16").'</td>
                    </tr>

                    <tr><td colspan=2><hr></td></tr>

                    <tr>
                        <td><h4>Text 3</h4></td>
                        <td>'.Checkbox("chT3","chT3").'</td>
                    </tr>
                    <tr>
                        <td class="ta_r">Textgr&ouml;&szlig;e</td>
                        <td><input style="width: 120px" type="range" min="5" max="40"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Farbe</td>
                        <td>'.ColorPicker("post-name", "element-id", "Farbe wählen", "#F01B16").'</td>
                    </tr>
                </table>


                <table style="font-size: 13pt; display: inline-table; vertical-align: top">
                    <tr>
                        <td><h4>Bild/Logo</h4></td>
                        <td>'.Checkbox("chImg","chImg").'</td>
                    </tr>
                    <tr>
                        <td class="ta_r">Gr&ouml;&szlig;e</td>
                        <td><input style="width: 120px" type="range" min="10" max="400"/></td>
                    </tr>
                    <tr>
                        <td colspan=2><center>'.FileButton("post-name", "element-id", 1).'</center></td>
                    </tr>

                    <tr><td colspan=2><br><hr><br></td></tr>

                    <tr>
                        <td colspan=2><h4>Hintergrund</h4></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Bild/Farbe</td>
                        <td>'.Togglebox("chBg","chBg").'</td>
                    </tr>
                    <tr>
                        <td colspan=2><center>'.FileButton("post-name", "element-id", 1).'</center></td>
                    </tr>
                </table>
            </div>
        </div>



    ';

    include("footer.php");
?>