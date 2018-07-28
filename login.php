<?php
    require("header.php");

    if(isset($_POST['login']))
    {
        $email=$_POST['email'];
        $pswd = hash('sha256',hash('sha256',$_POST['password']."salt")."pepper");

        if(MySQLExists("SELECT * FROM users WHERE email = '$email' AND password = '$pswd'"))
        {
            $_SESSION['user_id'] = Fetch("users","id","email",$email);
            $_SESSION['username'] = Fetch("users","username","email",$email);
            Redirect("/");
        }
        else Redirect(str_replace('?err','',ThisPage()).'?err');

    }

    echo '
        <h1 class="stagfade1">Login</h1>
        <br>
        <div class="stagfade2">
            <form action="'.str_replace('?err','',ThisPage()).'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <center>
                    <table class="centered_content">
                        <tr>
                            <td>'.Loader().'</td>
                        </tr>
                        <tr>
                            <td>'.((isset($_GET['err'])) ? '<span style="color: #CC0000">E-Mail oder Passwort nicht korrekt!</span>' : '').'</td>
                        </tr>
                        <tr>
                            <td><input type="email" name="email" class="cel_f15" placeholder="E-Mail..."/></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="password" class="cel_f15" placeholder="Passwort..."/></td>
                        </tr>
                        <tr>
                            <td><br><button type="submit" name="login" onclick="LoadAnimation();">Anmelden</button></td>
                        </tr>
                    </table>
                </center>
            </form>
        </div>
    ';

    include("footer.php");
?>