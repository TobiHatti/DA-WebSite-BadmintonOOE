<?php

function EditZA($id='')
{
    require("mysql_connect.php");
    $i=1;
    $j=8;
    $retval = '';

    if($id!='')
    {
        $strSQL = "SELECT * FROM zentralausschreibungen WHERE id = '$id'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $retval .= '
                <hr>
                <hr>
                <form action="'.ThisPage("!editSC","!#edit").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <div style="background: #F5F5DC">
                        <script>
                            window.onload = function() {
                                CopyZADate();
                                CopyZATitle();
                                SelectZAKategory();
                                for(var i=1;i<=14;i++) ChangeZAExtraData(i);
                            };
                        </script>


                        <table style="display:inline-table">
                            <tr>
                                <td>Kategorie:</td>
                                <td colspan=2>
                                    <select name="kategorie" id="zaKategory" onchange="SelectZAKategory();" class="cel_m" tabindex="1">
                                        <optgroup label="Gro&szlig;e Felder">
                                            <option '.((isset($row['kategorie']) AND $row['kategorie'] == "Landesmeisterschaft") ? 'selected' : '').' value="Landesmeisterschaft" style="color: '.Setting::Get("ColorLandesmeisterschaft").'">Landesmeisterschaft</option>
                                            <option '.((isset($row['kategorie']) AND $row['kategorie'] == "Doppelturnier") ? 'selected' : '').' value="Doppelturnier" style="color: '.Setting::Get("ColorDoppelturnier").'">Doppelturnier</option>
                                            <option '.((isset($row['kategorie']) AND $row['kategorie'] == "Nachwuchs") ? 'selected' : '').' value="Nachwuchs" style="color: '.Setting::Get("ColorNachwuchs").'">Nachwuchs</option>
                                            <option '.((isset($row['kategorie']) AND $row['kategorie'] == "SchuelerJugend") ? 'selected' : '').' value="SchuelerJugend" style="color: '.Setting::Get("ColorSchuelerJugend").'">Sch&uuml;ler/Jugend</option>
                                            <option '.((isset($row['kategorie']) AND $row['kategorie'] == "Senioren") ? 'selected' : '').' value="Senioren" style="color: '.Setting::Get("ColorSenioren").'">Senioren</option>
                                        </optgroup>
                                        <optgroup label="Kleine Felder">
                                            <option '.((isset($row['kategorie']) AND $row['kategorie'] == "Training") ? 'selected' : '').' value="Training" style="color: '.Setting::Get("ColorTraining").'">Training</option>
                                        </optgroup>
                                    </select>
                                </td>
                                <td>Beschreibung</td>
                                <td>
                                    <input name="title1" type="text" oninput="CopyZATitle();" placeholder="Titel Zeile 1" class="cel_m" id="zaTitleLineIn1" tabindex="4" value="'.((isset($row['title_line1'])) ? $row['title_line1'] : '').'"/>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Datum: </b><output id="outTimespan"></output></td>
                                <td colspan=2><input name="date1" type="date" onchange="CopyZADate();" id="datePick1" class="cel_m" tabindex="2" value="'.((isset($row['date_begin'])) ? $row['date_begin'] : date("Y-m-d")).'"/></td>
                                <td></td>
                                <td><input name="title2" type="text" oninput="CopyZATitle();" placeholder="Titel Zeile 2 (optional)" class="cel_m" id="zaTitleLineIn2" tabindex="5" value="'.((isset($row['title_line2'])) ? $row['title_line2'] : '').'"></td>
                            </tr>
                            <tr id="rwTimespan" style="display: none">
                                <td class="ta_r">Bis: </td>
                                <td colspan=2><input name="date2" type="date" onchange = "CopyZADate();" id="datePick2" class="cel_m" tabindex="3" value="'.((isset($row['date_end'])) ? $row['date_end'] : (date("Y-m-d",strtotime(date("Y-m-d")."+1 days")))).'"/></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>'.Checkbox("ch_timespan","chTimespan",0,"CopyZADate();").'</td>
                                <td>Zeitpsanne</td>
                            </tr>
                        </table>


                        <table style="display:inline-table" id="zaOptions">
                            <tr>
                                <td>'.Checkbox("ch_verein","chid".$i,((isset($row['act_verein'])) ? $row['act_verein'] : 1),"ChangeZAExtraData(".$i++.");").'</td>
                                <td>Verein</td>
                                <td>'.Checkbox("ch_oberschiedsrichter","chid".$j,((isset($row['act_oberschiedsrichter'])) ? $row['act_oberschiedsrichter'] : 1),"ChangeZAExtraData(".$j++.");").'</td>
                                <td>Oberschiedsrichter</td>
                            </tr>
                            <tr>
                                <td>'.Checkbox("ch_uhrzeit","chid".$i,((isset($row['act_uhrzeit'])) ? $row['act_uhrzeit'] : 1),"ChangeZAExtraData(".$i++.");").'</td>
                                <td>Uhrzeit</td>
                                <td>'.Checkbox("ch_telefon","chid".$j,((isset($row['act_telefon'])) ? $row['act_telefon'] : 1),"ChangeZAExtraData(".$j++.");").'</td>
                                <td>Telefon</td>
                            </tr>
                            <tr>
                                <td>'.Checkbox("ch_auslosung","chid".$i,((isset($row['act_auslosung'])) ? $row['act_auslosung'] : 1),"ChangeZAExtraData(".$i++.");").'</td>
                                <td>Auslosung</td>
                                <td>'.Checkbox("ch_anmeldung_online","chid".$j,((isset($row['act_anmeldung_online'])) ? $row['act_anmeldung_online'] : 1),"ChangeZAExtraData(".$j++.");").'</td>
                                <td>Anmeldung Online</td>
                            </tr>
                            <tr>
                                <td>'.Checkbox("ch_hallenname","chid".$i,((isset($row['act_hallenname'])) ? $row['act_hallenname'] : 1),"ChangeZAExtraData(".$i++.");").'</td>
                                <td>Hallenname</td>
                                <td>'.Checkbox("ch_anmeldung_email","chid".$j,((isset($row['act_anmeldung_email'])) ? $row['act_anmeldung_email'] : 1),"ChangeZAExtraData(".$j++.");").'</td>
                                <td>Anmeldung E-Mail</td>
                            </tr>
                            <tr>
                                <td>'.Checkbox("ch_anschrift_halle","chid".$i,((isset($row['act_anschrift_halle'])) ? $row['act_anschrift_halle'] : 1),"ChangeZAExtraData(".$i++.");").'</td>
                                <td>Anschrift Halle</td>
                                <td>'.Checkbox("ch_nennungen_email","chid".$j,((isset($row['act_nennungen_email'])) ? $row['act_nennungen_email'] : 1),"ChangeZAExtraData(".$j++.");").'</td>
                                <td>Nennungen E-Mail</td>
                            </tr>
                            <tr>
                                <td>'.Checkbox("ch_anzahl_felder","chid".$i,((isset($row['act_anzahl_felder'])) ? $row['act_anzahl_felder'] : 1),"ChangeZAExtraData(".$i++.");").'</td>
                                <td>Anzahl Felder</td>
                                <td>'.Checkbox("ch_nennschluss","chid".$j,((isset($row['act_nennschluss'])) ? $row['act_nennschluss'] : 1),"ChangeZAExtraData(".$j++.");").'</td>
                                <td>Nennschluss</td>
                            </tr>
                            <tr>
                                <td>'.Checkbox("ch_turnierverantwortlicher","chid".$i,((isset($row['act_turnierverantwortlicher'])) ? $row['act_turnierverantwortlicher'] : 1),"ChangeZAExtraData(".$i++.");").'</td>
                                <td>Turnierverantwortlicher</td>
                                <td>'.Checkbox("ch_zusatzangaben","chid".$j,((isset($row['act_zusatzangaben'])) ? $row['act_zusatzangaben'] : 1),"ChangeZAExtraData(".$j++.");").'</td>
                                <td>Zusatzangaben</td>
                            </tr>
                        </table>
                    ';

                    $i=1;

                    $retval .= '
                        <div class="za_box" id="zaFieldL">
                            <div class="za_title">

                                <h1>
                                    <output style="color: #FF0000" class="cel_l cef_nobg cef_brdb" id="zaTitleLineOut1">Titel/Beschreibung</output>
                                    <br>
                                    <output style="color: #FF0000" class="cel_l cef_nobg cef_brdb" id="zaTitleLineOut2"></output>
                                </h1>
                                <output class="cel_f15" id="zaDate"></output>

                            </div>
                            <div class="za_data">
                                <table>
                                    <tr id="edat'.$i.'">
                                        <td class="ta_r"><b>Verein:</b></td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <b><input name="verein" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" id="verein_in" value="'.((isset($row['verein'])) ? $row['verein'] : '').'"/></b>
                                            <select id="vereinSelection" onchange="UpdateZAVerein();" style="width: 40px;" class="cef_nomg cef_nopd">
                                                <option value="" disabled selected>&#9660; Verein&nbsp;&nbsp;ausw&auml;hlen</option>
                                                ';
                                                $strSQLv = "SELECT * FROM vereine";
                                                $rsv=mysqli_query($link,$strSQLv);
                                                while($rowv=mysqli_fetch_assoc($rsv))
                                                {
                                                    $retval .= '<option value="'.$rowv['verein'].' '.$rowv['ort'].'">'.$rowv['verein'].' <b>'.$rowv['ort'].'</b></option>';
                                                }
                                                $retval .= '
                                            </select>
                                        </td>
                                    </tr>
                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Uhrzeit:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="uhrzeit" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['uhrzeit'])) ? $row['uhrzeit'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Auslosung:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="auslosung" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['auslosung'])) ? $row['auslosung'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Hallenname:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="hallenname" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['hallenname'])) ? $row['hallenname'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Anschrift Halle:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="anschrift_halle" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['anschrift_halle'])) ? $row['anschrift_halle'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Anzahl Felder:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="anzahl_felder" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['anzahl_felder'])) ? $row['anzahl_felder'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Turnierverantwortlicher:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="turnierverantwortlicher" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['turnierverantwortlicher'])) ? $row['turnierverantwortlicher'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Oberschiedsrichter:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="oberschiedsrichter" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['oberschiedsrichter'])) ? $row['oberschiedsrichter'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Telefon:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="telefon" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['telefon'])) ? $row['telefon'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Anmeldung Online:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="anmeldung_online" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['anmeldung_online'])) ? $row['anmeldung_online'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Anmeldung E-Mail:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="anmeldung_email" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['anmeldung_email'])) ? $row['anmeldung_email'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Nennungen E-Mail:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="nennungen_email" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['nennungen_email'])) ? $row['nennungen_email'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Nennschluss:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="nennschluss" type="date" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['nennschluss'])) ? $row['nennschluss'] : '').'"/>
                                        </td>
                                    </tr>

                                    <tr id="edat'.$i.'">
                                        <td class="ta_r">Zusatzangaben:</td>
                                        <td class="ta_l">
                                            <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                            <input name="zusatzangaben" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['zusatzangaben'])) ? $row['zusatzangaben'] : '').'"/>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="za_box" id="zaFieldS" style="display:none;">
                            <div class="za_title">
                                <h3>
                                    <output style="color: '.Setting::Get("ColorTraining").'" class="cel_l cef_nobg cef_brdb" id="zaTitleLineOutS">Titel/Beschreibung</output>
                                </h3>

                            </div>
                            <div class="za_data">
                                <table>
                                    <tr>
                                        <td>Datum:</td>
                                        <td><output id="zaDateS"></output> </td>
                                    </tr>
                                    <tr>
                                        <td>Ort:</td>
                                        <td><input name="location" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" value="'.((isset($row['location'])) ? $row['location'] : '').'"/></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" name="postType" value="update"/>
                        <button type="submit" name="updateZA" value="'.$row['id'].'">Aktualisieren</button>
                        <a href="'.ThisPage("!editSC","!#edit").'"><button type="button">&Auml;nderungen verwerfen</button></a>
                    </div>
                </form>
                <hr><hr>
            ';
        }
    }
    else
    {
        echo '
            <h3>Zentralausschreibung erstellen</h3>
            <br>

            <script>
                window.onload = function() {
                    CopyZADate();
                };
            </script>

            <form action="'.ThisPage("!editSC","!#edit","!neu").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <table style="display:inline-table">
                    <tr>
                        <td>Kategorie:</td>
                        <td colspan=2>
                            <select name="kategorie" id="zaKategory" onchange="SelectZAKategory();" class="cel_m" tabindex="1">
                                <optgroup label="Gro&szlig;e Felder">
                                    <option value="Landesmeisterschaft" style="color: '.Setting::Get("ColorLandesmeisterschaft").'">Landesmeisterschaft</option>
                                    <option value="Doppelturnier" style="color: '.Setting::Get("ColorDoppelturnier").'">Doppelturnier</option>
                                    <option value="Nachwuchs" style="color: '.Setting::Get("ColorNachwuchs").'">Nachwuchs</option>
                                    <option value="SchuelerJugend" style="color: '.Setting::Get("ColorSchuelerJugend").'">Sch&uuml;ler/Jugend</option>
                                    <option value="Senioren" style="color: '.Setting::Get("ColorSenioren").'">Senioren</option>
                                </optgroup>
                                <optgroup label="Kleine Felder">
                                    <option value="Training" style="color: '.Setting::Get("ColorTraining").'">Training</option>
                                </optgroup>
                            </select>
                        </td>
                        <td>Beschreibung</td>
                        <td>
                            <input name="title1" type="text" oninput="CopyZATitle();" placeholder="Titel Zeile 1" class="cel_m" id="zaTitleLineIn1" value="" tabindex="4">
                        </td>
                    </tr>
                    <tr>
                        <td><b>Datum: </b><output id="outTimespan"></output></td>
                        <td colspan=2><input name="date1" type="date" onchange="CopyZADate();" id="datePick1" class="cel_m" value="'.date("Y-m-d").'" tabindex="2"/></td>
                        <td></td>
                        <td><input name="title2" type="text" oninput="CopyZATitle();" placeholder="Titel Zeile 2 (optional)" class="cel_m" id="zaTitleLineIn2" value="" tabindex="5"></td>
                    </tr>
                    <tr id="rwTimespan" style="display: none">
                        <td class="ta_r">Bis: </td>
                        <td colspan=2><input name="date2" type="date" onchange = "CopyZADate();" id="datePick2" class="cel_m" value="'.(date("Y-m-d",strtotime(date("Y-m-d")."+1 days"))).'" tabindex="3"/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>'.Checkbox("ch_timespan","chTimespan",0,"CopyZADate();").'</td>
                        <td>Zeitpsanne</td>
                    </tr>
                </table>


                <table style="display:inline-table" id="zaOptions">
                    <tr>
                        <td>'.Checkbox("ch_verein","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                        <td>Verein</td>
                        <td>'.Checkbox("ch_oberschiedsrichter","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                        <td>Oberschiedsrichter</td>
                    </tr>
                    <tr>
                        <td>'.Checkbox("ch_uhrzeit","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                        <td>Uhrzeit</td>
                        <td>'.Checkbox("ch_telefon","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                        <td>Telefon</td>
                    </tr>
                    <tr>
                        <td>'.Checkbox("ch_auslosung","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                        <td>Auslosung</td>
                        <td>'.Checkbox("ch_anmeldung_online","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                        <td>Anmeldung Online</td>
                    </tr>
                    <tr>
                        <td>'.Checkbox("ch_hallenname","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                        <td>Hallenname</td>
                        <td>'.Checkbox("ch_anmeldung_email","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                        <td>Anmeldung E-Mail</td>
                    </tr>
                    <tr>
                        <td>'.Checkbox("ch_anschrift_halle","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                        <td>Anschrift Halle</td>
                        <td>'.Checkbox("ch_nennungen_email","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                        <td>Nennungen E-Mail</td>
                    </tr>
                    <tr>
                        <td>'.Checkbox("ch_anzahl_felder","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                        <td>Anzahl Felder</td>
                        <td>'.Checkbox("ch_nennschluss","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                        <td>Nennschluss</td>
                    </tr>
                    <tr>
                        <td>'.Checkbox("ch_turnierverantwortlicher","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                        <td>Turnierverantwortlicher</td>
                        <td>'.Checkbox("ch_zusatzangaben","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                        <td>Zusatzangaben</td>
                    </tr>
                </table>

                ';

                $i=1;

                echo '


                <div class="za_box" id="zaFieldL">
                    <div class="za_title">
                        <h1>
                            <output style="color: #FF0000" class="cel_l cef_nobg cef_brdb" id="zaTitleLineOut1">Titel/Beschreibung</output>
                            <br>
                            <output style="color: #FF0000" class="cel_l cef_nobg cef_brdb" id="zaTitleLineOut2"></output>
                        </h1>
                        <output class="cel_f15" id="zaDate"></output>
                    </div>
                    <div class="za_data">
                        <table>
                            <tr id="edat'.$i.'">
                                <td class="ta_r"><b>Verein:</b></td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <b><input name="verein" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" id="verein_in"/></b>
                                    <select id="vereinSelection" onchange="UpdateZAVerein();" style="width: 40px;" class="cef_nomg cef_nopd">
                                        <option value="" disabled selected>&#9660; Verein&nbsp;&nbsp;ausw&auml;hlen</option>
                                        ';
                                        $strSQL = "SELECT * FROM vereine";
                                        $rs=mysqli_query($link,$strSQL);
                                        while($row=mysqli_fetch_assoc($rs))
                                        {
                                                echo '<option value="'.$row['verein'].' '.$row['ort'].'">'.$row['verein'].' <b>'.$row['ort'].'</b></option>';
                                        }
                                        echo '
                                    </select>
                                </td>
                            </tr>
                            <tr id="edat'.$i.'">
                                <td class="ta_r">Uhrzeit:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="uhrzeit" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Auslosung:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="auslosung" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Hallenname:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="hallenname" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Anschrift Halle:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="anschrift_halle" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Anzahl Felder:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="anzahl_felder" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Turnierverantwortlicher:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="turnierverantwortlicher" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Oberschiedsrichter:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="oberschiedsrichter" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Telefon:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="telefon" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Anmeldung Online:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="anmeldung_online" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Anmeldung E-Mail:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="anmeldung_email" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Nennungen E-Mail:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="nennungen_email" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Nennschluss:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="nennschluss" type="date" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>

                            <tr id="edat'.$i.'">
                                <td class="ta_r">Zusatzangaben:</td>
                                <td class="ta_l">
                                    <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                    <input name="zusatzangaben" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="za_box" id="zaFieldS" style="display:none;">
                    <div class="za_title">
                        <h3>
                            <output style="color: '.Setting::Get("ColorTraining").'" class="cel_l cef_nobg cef_brdb" id="zaTitleLineOutS">Titel/Beschreibung</output>
                        </h3>

                    </div>
                    <div class="za_data">
                        <table>
                            <tr>
                                <td>Datum:</td>
                                <td><output id="zaDateS"></output> </td>
                            </tr>
                            <tr>
                                <td>Ort:</td>
                                <td><input name="location" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <input type="hidden" name="postType" value="new"/>
                <button type="submit" name="updateZA">Eintragen</button>
            </form>
        ';
    }

    return $retval;
}


function EditGallery($id)
{
    require("mysql_connect.php");

    $strSQL = "SELECT * FROM fotogalerie WHERE id = '$id'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo'
            <div class="gallery_album">
                <center>
                    <table style="width: 90%;">
                        <tr>
                            <td class="ta_r">Titel</td>
                            <td><input type="text" class="cel_100" value="'.$row['album_name'].'"/></td>
                        </tr>
                        <tr>
                            <td colspan=2>'.TextareaPlus("description","description",$row['album_description']).'<br></td>
                        </tr>
                        <tr>
                            <td class="ta_r">Ort/Datum anzeigen</td>
                            <td>'.Checkbox("showDateLoc","dateloc",$row['show_dateloc']).'</td>
                        </tr>
                        <tr>
                            <td class="ta_r">Ort</td>
                            <td><input type="text" value="'.$row['event_location'].'"/></td>
                        </tr>
                        <tr>
                            <td class="ta_r">Datum</td>
                            <td><input type="date" value="'.$row['event_date'].'"/></td>
                        </tr>
                        <tr>
                            <td class="ta_r">Tags</td>
                            <td>
                                <input type="search" class="cel_l" id="tagText" placeholder="Tags eingeben... (Mit [Enter] best&auml;tigen)" onkeypress="return TagInsert(event)"/>
                                oder
                                <select onchange="TagList();" id="tagList">
                                    <option value="none" disabled selected>--- Kategorie Ausw&auml;hlen ---</option>
                                    <optgroup label="Hauptkategorien">
                                        ';
                                        $strSQLT = "SELECT * FROM news_tags";
                                        $rsT=mysqli_query($link,$strSQLT);
                                        while($rowT=mysqli_fetch_assoc($rsT)) { echo '<option value="'.$rowT['name'].'">'.$rowT['name'].'</option>'; }
                                        echo '
                                    </optgroup>
                                </select>

                                <input type="hidden" id="tag_nr" value="1"/>
                                <input type="hidden" id="tag_str" name="tags" value="'.$row['tags'].'"/>

                                <div class="tag_container" id="tagContainer"></div>

                                <script>
                                    window.onload = function () {
                                        LoadTags();
                                    }
                                </script>

                            </td>
                        </tr>
                        <tr>
                            <td class="ta_r">Download erlauben</td>
                            <td>'.Checkbox("enableDownload","endownload",$row['allowDownload']).'</td>
                        </tr>
                    </table>
                    <br>
                    <button type="submit" name="updateGallery" value="'.$id.'">Aktualisieren</button>
                    <a href="/fotogalerie"><button type="button">&Auml;nderungen verwerfen</button></a>
                </center>
            </div>
        ';
    }
}


?>