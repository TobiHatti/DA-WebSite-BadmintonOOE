<?php
    require("header.php");

    echo '
        <h1 class="stagfade1">PHP-Forms Overview</h1>

        <br>

        <span style="color: #008000">Empfohlen</span><br>
        <span style="color: #FF0000">Nicht Empfohlen</span><br>
        <span style="color: #1E90FF">N&uuml;tzlich/Regelm&auml;&szlig;ig ben&ouml;tigt</span><br>
        <span style="color: #FF8C00">Nur Selten ben&ouml;tigt</span><br>
        <span style="color: #8A2BE2">PHP-Funktion</span><br>


        <br><br>

        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">

            <table>
                <tr>
                    <td>button</td>
                    <td><input type="button" value="Button Type A"/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: red;"><input type="button" value="Button Type A"/></textarea></td>
                </tr>
                <tr>
                    <td>button</td>
                    <td><button type="button" name="post-name" value="post-value">Button Type B</button></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: green;"><button type="button">Button Type B</button></textarea></td>
                </tr>
                <tr>
                    <td>reset</td>
                    <td><input type="reset" value="Reset Type A"/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: red;"><input type="reset" value="Reset Type A"/></textarea></td>
                </tr>
                <tr>
                    <td>reset</td>
                    <td><button type="reset">Reset Type B</button></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: green;"><button type="button">Reset Type B</button></textarea></td>
                </tr>
                <tr>
                    <td>submit</td>
                    <td><input type="submit" value="Submit Type A"/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: red;"><input type="submit" name="post-name" value="Submit Type A"/></textarea></td>
                </tr>
                <tr>
                    <td>submit</td>
                    <td><button type="submit">Submit Type B</button></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: green;"><button type="button" name="post-name" value="post-value">Submit Type B</button></textarea></td>
                </tr>
                <tr>
                    <td>file</td>
                    <td>'.FileButton("fileupload1", "file1", 1).'</td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #8A2BE2;">&apos;.FileButton("post-name", "element-id", 1).&apos; //PHP-Function. Description &gt; functions.php</textarea></td>
                </tr>
                <tr>
                    <td>color</td>
                    <td><input type="color"/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #FF8C00;"><input type="color"/></textarea></td>
                </tr>
                <tr>
                    <td>checkbox</td>
                    <td>'.Checkbox("","check1").'</td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #8A2BE2;">&apos;.Checkbox("post-name","element-id",0).&apos; //PHP-Function. Description > functions.php</textarea></td>
                </tr>
                <tr>
                    <td>radio</td>
                    <td>
                        '.RadioButton("A1","radio1",1).'
                        '.RadioButton("A2","radio1",0).'
                    </td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #8A2BE2;">&apos;.RadioButton("A1","post-name",1).&apos; //PHP-Function. Description > functions.php</textarea></td>
                </tr>
                <tr>
                    <td>range</td>
                    <td><input type="range" min="0" max="5" value="3" name="post-name"/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #FF8C00;"><input type="range" min="0" max="5" value="3" name="post-name"/></textarea></td>
                </tr>
                <tr>
                    <td>select</td>
                    <td>
                        <select name="" id="">
                            <option value="Wert1">Wert 1</option>
                            <option value="Wert2">Wert 2</option>
                            <option value="Wert3">Wert 3</option>
                        </select>
                    </td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #1E90FF;"><select name="post-name"><option value="option-value">Option-Text</option></select></textarea></td>
                </tr>
                <tr>
                    <td>textarea</td>
                    <td><textarea name="" placeholder="Langer Text..."></textarea></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #1E90FF;"><textarea name="post-name" placeholder="Langer Text...">Inhalt&lt;/textarea&gt;</textarea></td>
                </tr>
                <tr>
                    <td>email</td>
                    <td><input type="email" placeholder="E-Mail..."/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #1E90FF;"><input type="email" placeholder="E-Mail..." name="post-name"/></textarea></td>
                </tr>
                <tr>
                    <td>number</td>
                    <td><input type="number" placeholder="Nummer..." name="post-name" step="0.5"/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #1E90FF;"><input type="number" placeholder="Nummer..." name="post-name" step="0.5"/></textarea></td>
                </tr>
                <tr>
                    <td>password</td>
                    <td><input type="password" placeholder="Passwort..."/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #1E90FF;"><input type="password" placeholder="Passwort..." name="post-name"/></textarea></td>
                </tr>
                <tr>
                    <td>search</td>
                    <td><input type="search" placeholder="Suchleiste..."/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #FF8C00;"><input type="search" placeholder="Suchleiste..." name="post-name"/></textarea></td>
                </tr>
                <tr>
                    <td>tel</td>
                    <td><input type="tel" placeholder="Telefonnummer..."/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #1E90FF;"><input type="tel" placeholder="Telefonnummer..." name="post-name"/></textarea></td>
                </tr>
                <tr>
                    <td>text</td>
                    <td><input type="text" placeholder="Kurzer Text..."/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #1E90FF;"><input type="text" placeholder="Kurzer Text..." name="post-name"/></textarea></td>
                </tr>
                <tr>
                    <td>url</td>
                    <td><input type="url" placeholder="Internet-Adresse..."/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #1E90FF;"><input type="url" placeholder="Internet-Adresse..." name="post-name"/></textarea></td>
                </tr>
                <tr>
                    <td>datetime-local</td>
                    <td><input type="datetime-local"/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #FF8C00;"><input type="datetime-local" name="post-name"/></textarea></td>
                </tr>
                <tr>
                    <td>date</td>
                    <td><input type="date"/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #FF8C00;"><input type="date" name="post-name"/></textarea></td>
                </tr>
                <tr>
                    <td>month</td>
                    <td><input type="month"/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #FF8C00;"><input type="month" name="post-name"/></textarea></td>
                </tr>
                <tr>
                    <td>week</td>
                    <td><input type="week"/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #FF8C00;"><input type="week" name="post-name"/></textarea></td>
                </tr>
                <tr>
                    <td>time</td>
                    <td><input type="time"/></td>
                    <td><textarea spellcheck="false" class="codeprev" style="color: #FF8C00;"><input type="time" name="post-name"/></textarea></td>
                </tr>
            </table>


        </form>




    ';

    require("footer.php");
?>