<?php
    require("header.php");

    if(CheckPermission("ChangeContent"))
    {
        echo '

        <div class="settings_content">
            <div class="menu">
                <span>Menu</span>
                <a onclick="ChangeFrameLink(\'settings_content?topic=Unsorted\');"><li>Unsortiert</li></a>
                <a onclick="ChangeFrameLink(\'settings_content?topic=Allgemein\');"><li>Allgemein</li></a>
                <a onclick="ChangeFrameLink(\'settings_content?topic=Startseite\');"><li>Startseite</li></a>
                <a onclick="ChangeFrameLink(\'settings_content?topic=Nutzer\');"><li>Nutzer</li></a>
            </div>
            <iframe id="chframe" name="settingsContent" src="/settings_content?topic=Unsorted" frameborder="0" scrolling="no"></iframe>
        </div>



        <h3>Permissions:</h3>

        <table>
            <tr>
                <td><b>ChangeContent</b></td>
                <td>Seiteninhalte verwalten/&auml;ndern</td>
            </tr>
            <tr><td colspan="2"><hr></td></tr>
            <tr>
                <td><b>ManageSettings</b></td>
                <td>Seiteneinstellungen verwalten/&auml;ndern</td>
            </tr>
            <tr><td colspan="2"><hr></td></tr>
            <tr>
                <td><b>ManageSponsors</b></td>
                <td>Sponsorenlisten verwalten/&auml;ndern</td>
            </tr>
            <tr><td colspan="2"><hr></td></tr>
            <tr>
                <td><b>ManageUsers</b></td>
                <td>Nutzer hinzuf&uuml;gen/entfernen</td>
            </tr>
            <tr>
                <td><b>ManagePermissions</b></td>
                <td>Nutzer-Rechte verwalten</td>
            </tr>
            <tr><td colspan="2"><hr></td></tr>
            <tr>
                <td><b>AddGallery</b></td>
                <td>Neue Galerie erstellen/Fotos hochladen</td>
            </tr>
            <tr>
                <td><b>EditGallery</b></td>
                <td>Galerie bearbeiten</td>
            </tr>
            <tr>
                <td><b>DeleteGallery</b></td>
                <td>Album/Fotos löschen</td>
            </tr>
            <tr><td colspan="2"><hr></td></tr>
            <tr>
                <td><b>AddNews</b></td>
                <td>Artikel hinzuf&uuml;gen</td>
            </tr>
            <tr>
                <td><b>EditNews</b></td>
                <td>Artikel bearbeiten</td>
            </tr>
            <tr>
                <td><b>DeleteNews</b></td>
                <td>Artikel l&ouml;schen</td>
            </tr>
            <tr><td colspan="2"><hr></td></tr>
        </table>


        ';
    }

    include("footer.php");
?>