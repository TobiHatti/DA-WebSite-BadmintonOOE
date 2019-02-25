<?php
    require("header.php");

    if(CheckPermission("ChangeContent"))
    {
        echo '

        <div class="settings_content">
            <div class="menu">
                <span>Men&uuml;</span>
                <a onclick="ChangeFrameLink(\'settings_content?topic=Account\');"><li>Ihr Account</li></a>
                ';

                if(CheckPermission("ManageSettings")) echo '<a onclick="ChangeFrameLink(\'settings_content?topic=Allgemein\');"><li>Allgemein</li></a>';
                if(CheckPermission("ManageSettings")) echo '<a onclick="ChangeFrameLink(\'settings_content?topic=Startseite\');"><li>Startseite</li></a>';
                if(CheckPermission("ManageUsers")) echo '<a onclick="ChangeFrameLink(\'settings_content?topic=Nutzer\');"><li>Nutzer</li></a>';
                if(CheckPermission("ChangeContent")) echo '<a onclick="ChangeFrameLink(\'settings_content?topic=Fusszeile\');"><li>Fu&szlig;zeile</li></a>';
                if(CheckPermission("ChangeContent")) echo '<a onclick="ChangeFrameLink(\'settings_content?topic=Dateien\');"><li>Dateien</li></a>';

                echo '
            </div>
            <iframe id="chframe" name="settingsContent" src="/settings_content?topic=Account" frameborder="0"></iframe>
        </div>

        ';
    }

    include("footer.php");
?>