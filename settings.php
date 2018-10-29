<?php
    require("header.php");

    if(CheckPermission("ChangeContent"))
    {
        echo '

        <div class="settings_content">
            <div class="menu">
                <span>Men&uuml;</span>
                <a onclick="ChangeFrameLink(\'settings_content?topic=Allgemein\');"><li>Allgemein</li></a>
                <a onclick="ChangeFrameLink(\'settings_content?topic=Startseite\');"><li>Startseite</li></a>
                <a onclick="ChangeFrameLink(\'settings_content?topic=Nutzer\');"><li>Nutzer</li></a>
                <a onclick="ChangeFrameLink(\'settings_content?topic=Fusszeile\');"><li>Fu&szlig;zeile</li></a>
            </div>
            <iframe id="chframe" name="settingsContent" src="/settings_content?topic=Allgemein" frameborder="0"></iframe>
        </div>

        ';
    }

    include("footer.php");
?>