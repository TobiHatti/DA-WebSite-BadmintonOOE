<?php
    require("header.php");

    echo '
        <h1 class="stagfade1">Temporary Form-File</h1>

        <h3 class="stagfade2">Use temporarily for SQL/PHP Forms and Database insertions</h3>
        <br><br><br>

        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">

            <table>
                <tr>
                    <td>button</td>
                    <td><input type="button" value="Button Type A"/></td>
                </tr>
                <tr>
                    <td>button</td>
                    <td><button type="button">Button Type B</button></td>
                </tr>
                <tr>
                    <td>reset</td>
                    <td><input type="reset" value="Reset Type A"/></td>
                </tr>
                <tr>
                    <td>reset</td>
                    <td><button type="reset">Reset Type B</button></td>
                </tr>
                <tr>
                    <td>submit</td>
                    <td><input type="submit" value="Submit Type A"/></td>
                </tr>
                <tr>
                    <td>submit</td>
                    <td><button type="submit">Submit Type B</button></td>
                </tr>
                <tr>
                    <td>file</td>
                    <td><input type="file"/></td>
                </tr>
                <tr>
                    <td>color</td>
                    <td><input type="color"/></td>
                </tr>
                <tr>
                    <td>checkbox</td>
                    <td>'.Checkbox("","check1").'</td>
                </tr>
                <tr>
                    <td>radio</td>
                    <td>
                        '.RadioButton("A1","radio1",1).'
                        '.RadioButton("A2","radio1",0).'
                    </td>
                </tr>
                <tr>
                    <td>range</td>
                    <td><input type="range"/></td>
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
                </tr>
                <tr>
                    <td>textarea</td>
                    <td><textarea name=""></textarea></td>
                </tr>
                <tr>
                    <td>email</td>
                    <td><input type="email"/></td>
                </tr>
                <tr>
                    <td>number</td>
                    <td><input type="number"/></td>
                </tr>
                <tr>
                    <td>password</td>
                    <td><input type="password"/></td>
                </tr>
                <tr>
                    <td>search</td>
                    <td><input type="search"/></td>
                </tr>
                <tr>
                    <td>tel</td>
                    <td><input type="tel"/></td>
                </tr>
                <tr>
                    <td>text</td>
                    <td><input type="text"/></td>
                </tr>
                <tr>
                    <td>url</td>
                    <td><input type="url"/></td>
                </tr>
                <tr>
                    <td>datetime-local</td>
                    <td><input type="datetime-local"/></td>
                </tr>
                <tr>
                    <td>date</td>
                    <td><input type="date"/></td>
                </tr>
                <tr>
                    <td>month</td>
                    <td><input type="month"/></td>
                </tr>
                <tr>
                    <td>week</td>
                    <td><input type="week"/></td>
                </tr>
                <tr>
                    <td>time</td>
                    <td><input type="time"/></td>
                </tr>
            </table>


        </form>




    ';

    require("footer.php");
?>