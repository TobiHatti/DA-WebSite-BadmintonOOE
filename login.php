<?php
    require("header.php");

    if(isset($_POST['login']))
    {
        $email=$_POST['email'];
        $pswd = hash('sha256',hash('sha256',$_POST['password']."salt")."pepper");


        $stmt = $link->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
        $stmt->bind_param('ss', $email, $pswd); // 's' specifies the variable type => 'string'

        $stmt->execute();

        $result = $stmt->get_result();

        $executed = false;

        while ($row = $result->fetch_assoc())
        {
            $executed = true;

            $_SESSION['userID'] = $row['id'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['lastname'] = $row['lastname'];
            $_SESSION['rank'] = $row['rank'];
            Redirect("/");
        }

        if(!$executed) Redirect(str_replace('?err','',ThisPage()).'?err');
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