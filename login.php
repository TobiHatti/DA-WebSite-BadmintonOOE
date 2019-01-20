<?php
    require("header.php");

    if(isset($_POST['login']))
    {
        $email=$_POST['email'];

        $passwordHash = MySQL::Scalar("SELECT password FROM users WHERE email = ?",'s',$email);
        $executed = false;

        if(password_verify($_POST['password'],$passwordHash))
        {
            $userDataArray = MySQL::Row("SELECT * FROM users WHERE email = ?",'s',$email);

            $executed = true;

            $_SESSION['userID'] = $userDataArray['id'];
            $_SESSION['firstname'] = $userDataArray['firstname'];
            $_SESSION['lastname'] = $userDataArray['lastname'];
            $_SESSION['rank'] = $userDataArray['rank'];
            Redirect("/");
        }

        if(!$executed) Redirect(str_replace('?err','',ThisPage()).'?err');
        die();
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