<?php
    require("header.php");

    if(isset($_POST['addTemplate']))
    {
        $id = uniqid();

        $name = $_POST['name'];

        $actT1 = isset($_POST['chT1']) ? 1 : 0;
        $sizeT1 = $_POST['sizeT1'];
        $colorT1 = $_POST['colorT1'];
        $xT1 = $_POST['xT1'];
        $yT1 = $_POST['yT1'];

        $actT2 = isset($_POST['chT2']) ? 1 : 0;
        $sizeT2 = $_POST['sizeT2'];
        $colorT2 = $_POST['colorT2'];
        $xT2 = $_POST['xT2'];
        $yT2 = $_POST['yT2'];

        $actT3 = isset($_POST['chT3']) ? 1 : 0;
        $sizeT3 = $_POST['sizeT3'];
        $colorT3 = $_POST['colorT3'];
        $xT3 = $_POST['xT3'];
        $yT3 = $_POST['yT3'];

        $actImg = isset($_POST['chImg']) ? 1 : 0;
        $sizeImg = $_POST['sizeImg'];
        $xImg = $_POST['xImg'];
        $yImg = $_POST['yImg'];


        $bgIsImage = isset($_POST['chBg']) ? 0 : 1;
        $bgIsGradient = isset($_POST['toggleBgStyle']) ? 1 : 0;
        $bgColor1 = $_POST['colorBG1'];
        $bgColor2 = $_POST['colorBG2'];
        $bgDirection = $_POST['bgDirection'];

        $strSQL = "
        INSERT INTO news_templates
        (id, name, actT1, sizeT1, colorT1,xT1,yT1, actT2, sizeT2, colorT2,xT2,yT2, actT3, sizeT3, colorT3,xT3,yT3, actImg, sizeImg,xImg,yImg, bgIsImage, bgIsGradient, bgColor1, bgColor2, bgDirection)
        VALUES
        ('$id','$name','$actT1','$sizeT1','$colorT1','$xT1','$yT1','$actT2','$sizeT2','$colorT2','$xT2','$yT2','$actT3','$sizeT3','$colorT3','$xT3','$yT3','$actImg','$sizeImg','$xImg','$yImg','$bgIsImage','$bgIsGradient','$bgColor1','$bgColor2','$bgDirection');
        ";

        MySQLNonQuery($strSQL);

        FileUpload("content/newstemplates/img/","templateLogo","","","UPDATE news_templates SET pathImg = 'FNAME' WHERE id = '$id'",uniqid());
        FileUpload("content/newstemplates/","templateBackground","","","UPDATE news_templates SET bgPath = 'FNAME' WHERE id = '$id'",uniqid());

        Redirect("/news/neu#imageMaker");
        die();
    }


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


            window.setInterval(function(){
                TemplatesGetPosition();
            }, 50);
        </script>

        <br>
        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">


            <input type="hidden" name="xT1" id="outT1x"/>
            <input type="hidden" name="yT1" id="outT1y"/>
            <input type="hidden" name="xT2" id="outT2x"/>
            <input type="hidden" name="yT2" id="outT2y"/>
            <input type="hidden" name="xT3" id="outT3x"/>
            <input type="hidden" name="yT3" id="outT3y"/>
            <input type="hidden" name="xImg" id="outImgx"/>
            <input type="hidden" name="yImg" id="outImgy"/>


            <div class="double_container">
                <div style="min-width: 640px;">
                    <center>
                        <span style="float: left;">&#128712; Elemente mit Maus verschieben</span>
                        <div id="creatorContainer" style=" border: 1px solid black; color: #FFFFFF; position: relative; width: 640px; height: 360px; background-size: cover; background: #E0FFFF">

                            <div id="dragT1" class="dragItem" style="position: absolute; width: 30px; white-space: nowrap; overflow: visible; top: 40px; left: 20px;"><span id="dragContent1" style="color: #F01B16; font-size: 26pt;">Beispiel-Text #1</span></div>
                            <div id="dragT2" class="dragItem" style="position: absolute; width: 30px; white-space: nowrap; overflow: visible; top: 100px; left: 60px;"><span id="dragContent2" style="color: #F01B16; font-size: 18pt;">Beispiel-Text #2</span></div>
                            <div id="dragT3" class="dragItem" style="position: absolute; width: 30px; white-space: nowrap; overflow: visible; top: 160px; left: 100px;"><span id="dragContent3" style="color: #F01B16; font-size: 18pt;">Beispiel-Text #3</span></div>
                            <div id="dragImg" class="dragItem" style="position: absolute; width: 30px; white-space: nowrap; overflow: visible; top: 10px; left: 380px; display: none"><img id="dragContent4" style="width: 200px;" src="/content/not-found.png" alt="" /></div>
                        </div>
                    </center>
                    <br>
                    Vorlagen-Name: <input type="text" name="name" placeholder="Vorlagen-Name..." required/>
                </div>
                <div>
                    <table style="font-size: 13pt; display: inline-table; vertical-align: top; margin-right: 40px;">
                        <tr>
                            <td><h4>Text 1</h4></td>
                            <td>'.Checkbox("chT1","chT1",true,"ShowHideElement(this,'dragT1');").'</td>
                        </tr>
                        <tr>
                            <td class="ta_r">Textgr&ouml;&szlig;e</td>
                            <td><input name="sizeT1" style="width: 120px" type="range" value="26" min="10" max="50" oninput="ChangeFontSize(this,\'dragContent1\');"/></td>
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
                            <td><input name="sizeT2" style="width: 120px" type="range" value="18" min="10" max="50" oninput="ChangeFontSize(this,\'dragContent2\');"/></td>
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
                            <td><input name="sizeT3" style="width: 120px" type="range" value="18" min="10" max="50" oninput="ChangeFontSize(this,\'dragContent3\');"/></td>
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
                            <td><input name="sizeImg" style="width: 120px" type="range" value="200" min="50" max="800" oninput="ChangeImageSize(this,\'dragContent4\');"/></td>
                        </tr>

                        <tr>
                            <td colspan=2><center>'.FileButton("templateLogo", "templateLogo", false,"ReadURL(this,'dragContent4');","ActivateSwitch('chImg');ShowElement('dragImg')").'</center></td>
                        </tr>

                        <tr><td colspan=2><br><hr><br></td></tr>

                        <tr>
                            <td colspan=2><h4>Hintergrund</h4></td>
                        </tr>
                        <tr>
                            <td class="ta_r">Bild/Farbe</td>
                            <td>'.Togglebox("chBg","chBg",true,"ShowHideBGImage(this,'creatorContainer');TemplatesUpdateBGRows();").'</td>
                        </tr>


                        <tr id="bgColorRowBGImage">
                            <td colspan=2><center><input type="hidden" id="saveOutput"/>'.FileButton("templateBackground", "templateBackground", false,"ReadURLDivBG(this,'creatorContainer');").'</center></td>
                        </tr>

                        <tr id="bgColorRowGradientCheck">
                            <td>
                                Volltonfarbe/<br>
                                Farbverlauf
                            </td>
                            <td>'.Togglebox("toggleBgStyle","toggleBgStyle",true,"TemplatesUpdateBGRows();").'</td>
                        </tr>

                        <tr id="bgColorRowPicker1">
                            <td class="ta_r">Farbe 1</td>
                            <td>'.ColorPicker("colorBG1", "colorBG1", "Farbe wählen", "#E0FFFF","","","TemplatesSetBGColor();").'</td>
                        </tr>

                        <tr id="bgColorRowGPicker2">
                            <td class="ta_r">Farbe 2</td>
                            <td>'.ColorPicker("colorBG2", "colorBG2", "Farbe wählen", "#E0FFFF","","","TemplatesSetBGColor();").'</td>
                        </tr>

                        <tr id="bgColorRowGradientDirections">
                            <td class="ta_r">Richtung</td>
                            <td>
                                <select name="bgDirection" id="bgGradientOrientation" onchange="TemplatesSetBGColor();">
                                    <option value="left bottom||left top">Nach Oben</option>
                                    <option value="left top||left bottom">Nach Unten</option>
                                    <option value="right top|| left top">Nach Links</option>
                                    <option value="left top||right top">Nach Rechts</option>
                                    <option value="right bottom||left top">Nach Links Oben</option>
                                    <option value="left bottom||right top">Nach Rechts Oben</option>
                                    <option value="right top||left bottom">Nach Links Unten</option>
                                    <option value="left top||right bottom">Nach Rechts Unten</option>
                                </select>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
            <center><button type="submit" name="addTemplate">Vorlage Speichern</button></center>
        </form>



    ';




    include("footer.php");
?>