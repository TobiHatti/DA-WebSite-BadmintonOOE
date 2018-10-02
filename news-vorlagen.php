<?php
    require("header.php");




    echo '
        <h2>Neue Vorlage erstellen</h2>
        <hr>


        <script>
            $( function() {
                $( "#dragT1" ).draggable({containment: "#creatorContainer", scroll: false});
                $( "#dragT2" ).draggable({containment: "#creatorContainer", scroll: false});
                $( "#dragT3" ).draggable({containment: "#creatorContainer", scroll: false});
                $( "#dragImg" ).draggable({containment: "#creatorContainer", scroll: false});
            } );
        </script>

        <br>
        <div class="double_container">
            <div style="min-width: 640px;">
                <center>
                    <span style="float: left;">&#128712; Elemente mit Maus verschieben</span>
                    <div id="creatorContainer" style=" border: 1px solid black; color: #FFFFFF; position: relative; width: 640px; height: 360px; background: #E0F0FF; background-image: url(\'content/newstemplates/testtemplate.jpg\'); background-size: cover; background-color: lime;">

                        <div id="dragT1" class="dragItem" style="width: 30px; white-space: nowrap; overflow: visible; top: 40px; left: 20px;"><span id="dragContent1" style="color: #F01B16; font-size: 26pt;">Beispiel-Text #1</span></div>
                        <div id="dragT2" class="dragItem" style="width: 30px; white-space: nowrap; overflow: visible; top: 100px; left: 60px;"><span id="dragContent2" style="color: #F01B16; font-size: 18pt;">Beispiel-Text #2</span></div>
                        <div id="dragT3" class="dragItem" style="width: 30px; white-space: nowrap; overflow: visible; top: 160px; left: 100px;"><span id="dragContent3" style="color: #F01B16; font-size: 18pt;">Beispiel-Text #3</span></div>
                        <div id="dragImg" class="dragItem" style="width: 30px; white-space: nowrap; overflow: visible; top: 10px; left: 380px; display: none"><img id="dragContent4" style="width: 200px;" src="/content/not-found.png" alt="" /></div>
                    </div>
                </center>
            </div>
            <div>
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table style="font-size: 13pt; display: inline-table; vertical-align: top; margin-right: 40px;">
                        <tr>
                            <td><h4>Text 1</h4></td>
                            <td>'.Checkbox("chT1","chT1",true,"ShowHideElement(this,'dragT1');").'</td>
                        </tr>
                        <tr>
                            <td class="ta_r">Textgr&ouml;&szlig;e</td>
                            <td><input style="width: 120px" type="range" value="26" min="10" max="50" oninput="ChangeFontSize(this,\'dragContent1\');"/></td>
                        </tr>
                        <tr>
                            <td class="ta_r">Farbe</td>
                            <td>'.ColorPicker("colorT1", "colorT1", "Farbe wählen", "#F01B16","dragContent1").'</td>
                        </tr>

                        <tr><td colspan=2><hr></td></tr>

                        <tr>
                            <td><h4>Text 2</h4></td>
                            <td>'.Checkbox("chT2","chT2",true,"ShowHideElement(this,'dragT2');").'</td>
                        </tr>
                        <tr>
                            <td class="ta_r">Textgr&ouml;&szlig;e</td>
                            <td><input style="width: 120px" type="range" value="18" min="10" max="50" oninput="ChangeFontSize(this,\'dragContent2\');"/></td>
                        </tr>
                        <tr>
                            <td class="ta_r">Farbe</td>
                            <td>'.ColorPicker("colorT2", "colorT2", "Farbe wählen", "#F01B16","dragContent2").'</td>
                        </tr>

                        <tr><td colspan=2><hr></td></tr>

                        <tr>
                            <td><h4>Text 3</h4></td>
                            <td>'.Checkbox("chT3","chT3",true,"ShowHideElement(this,'dragT3');").'</td>
                        </tr>
                        <tr>
                            <td class="ta_r">Textgr&ouml;&szlig;e</td>
                            <td><input style="width: 120px" type="range" value="18" min="10" max="50" oninput="ChangeFontSize(this,\'dragContent3\');"/></td>
                        </tr>
                        <tr>
                            <td class="ta_r">Farbe</td>
                            <td>'.ColorPicker("colorT3", "colorT3", "Farbe wählen", "#F01B16","dragContent3").'</td>
                        </tr>
                    </table>


                    <table style="font-size: 13pt; display: inline-table; vertical-align: top">
                        <tr>
                            <td><h4>Bild/Logo</h4></td>
                            <td>'.Checkbox("chImg","chImg",false,"ShowHideElement(this,'dragImg');").'</td>
                        </tr>
                        <tr>
                            <td class="ta_r">Gr&ouml;&szlig;e</td>
                            <td><input style="width: 120px" type="range" value="200" min="50" max="800" oninput="ChangeImageSize(this,\'dragContent4\');"/></td>
                        </tr>
                        <tr>
                            <td colspan=2><center>'.FileButton("templateLogo", "templateLogo", false,"ReadURL(this,'dragContent4');").'</center></td>
                        </tr>

                        <tr><td colspan=2><br><hr><br></td></tr>

                        <tr>
                            <td colspan=2><h4>Hintergrund</h4></td>
                        </tr>
                        <tr>
                            <td class="ta_r">Bild/Farbe</td>
                            <td>'.Togglebox("chBg","chBg",true,"ShowHideBGImage(this,'creatorContainer');").'</td>
                        </tr>
                        <tr>
                            <td colspan=2><center>'.FileButton("templateBackground", "templateBackground", false,"ReadURLDivBG(this,'creatorContainer');").'</center></td>
                        </tr>


                        <tr>
                            <td>
                                Volltonfarbe/<br>
                                Farbverlauf
                            </td>
                            <td>'.Togglebox("toggleBgStyle","toggleBgStyle" ).'</td>
                        </tr>

                        <tr>
                            <td class="ta_r">Farbe</td>
                            <td>'.ColorPicker("colorBG1", "colorBG1", "Farbe wählen", "#F01B16").'</td>
                        </tr>

                        <tr>
                            <td class="ta_r">Farbe</td>
                            <td>'.ColorPicker("colorBG2", "colorBG2", "Farbe wählen", "#F01B16").'</td>
                        </tr>
                        <tr>
                            <td class="ta_r">Richtung</td>
                            <td>
                                <select name="" id="">
                                    <option value="to top">Nach Oben</option>
                                    <option value="to bottom">Nach Unten</option>
                                    <option value="to left">Nach Links</option>
                                    <option value="to right">Nach Rechts</option>
                                    <option value="to top left">Nach Links Oben</option>
                                    <option value="to top right">Nach Rechts Oben</option>
                                    <option value="to bottom left">Nach Links Unten</option>
                                    <option value="to bottom right">Nach Rechts Unten</option>
                                </select>
                            </td>
                        </tr>

                    </table>
                </form>
            </div>
        </div>



    ';




    include("footer.php");
?>