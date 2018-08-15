<?php
    require("header.php");

//=================================================================================
//=================================================================================
//      POST - SECTION
//=================================================================================
//=================================================================================

if(isset($_POST['add_vorstand_member']))
{
    $name = $_POST['name'];
    $fields = $_POST['fields'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $type = $_POST['type'];

    MySQLNonQuery("INSERT INTO vorstand (id,darstellung,name,bereich,email,telefon) VALUES ('','$type','$name','$fields','$email','$phone')");

    FileUpload("content/vorstand/","image","","","UPDATE vorstand SET foto = 'FNAME' WHERE name = '$name'");

    Redirect(ThisPage());
}

if(isset($_POST['add_tag']))
{
    $tag = $_POST['tag'];

    $tagid = SReplace($tag);

    MySQLNonQuery("INSERT INTO news_tags (id,name) VALUES ('$tagid','$tag')");

    Redirect(ThisPage());
}

//=================================================================================
//=================================================================================
//      PAGE - SECTION
//=================================================================================
//=================================================================================

    echo '
        <a href="__tempform"><h1 class="stagfade1">Temporary Form-File</h1></a>
        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    ';


    if(isset($_GET['vorstand']))
    {
        echo '<h3 class="stagfade2">[SQL-INSERT]: Vorand</h3> ';

        echo '
            <table>
                <tr>
                    <td>Darstellung</td>
                    <td>
                        <select name="type">
                            <option value="list">Liste</option>
                            <option value="box">Box</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><input type="text" name="name"/></td>
                </tr>
                <tr>
                    <td>Gebiete</td>
                    <td><textarea name="fields">Schiedsgericht</textarea></td>
                </tr>
                <tr>
                    <td>E-Mail</td>
                    <td><input type="email" name="email"/></td>
                </tr>
                <tr>
                    <td>Telefon</td>
                    <td><input type="tel" name="phone"/></td>
                </tr>
                <tr>
                    <td>Foto</td>
                    <td>'.FileButton('image','image').'</td>
                </tr>
                <tr>
                    <td colspan=2><button type="submit" name="add_vorstand_member">Hinzuf&uuml;gen</button></td>
                </tr>
            </table>
        ';
    }
    else if(isset($_GET['tags']))
    {
        echo '<h3 class="stagfade2">[SQL-INSERT]: Vorand</h3> ';

        echo '
            <table>
                <tr>
                    <td>Tag-Name</td>
                    <td><input type="text" name="tag"/></td>
                </tr>
                <tr>
                    <td colspan=2><button type="submit" name="add_tag">Hinzuf&uuml;gen</button></td>
                </tr>
            </table>
        ';
    }
    else
    {
        echo '<h3 class="stagfade2">Use temporarily for SQL/PHP Forms and Database insertions</h3> ';

        echo '
            <ul>
                <li><a href="?vorstand">[SQL-INSERT]: Vorand</a></li>
                <li><a href="?tags">[SQL-INSERT]: News-Tags</a></li>
            </ul>
        ';
    }

    echo '</form>';

    require("footer.php");
?>