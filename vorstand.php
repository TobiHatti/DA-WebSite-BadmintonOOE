<?php
    require("header.php");
    PageTitle("Vorstand");

    echo '
        <h1 class="stagfade1">Vorstand</h1>

        <p>'.PageContent('1',CheckPermission("ChangeContent")).'</p>
        <br>
        ';

        $strSQL = "SELECT * FROM vorstand WHERE darstellung = 'box'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="vmember_container">
                    <div class="vmember_img">
                        <img src="/content/vorstand/'.$row['foto'].'" alt="'.$row['name'].'" class="user_img_m"/>
                    </div>
                    <div class="vmember_content">
                        <b>'.$row['name'].'</b>
                        <br>
                        <i>'.nl2br($row['bereich']).'</i>
                        <br>
                        <a href="mailto:'.$row['email'].'">E-Mail senden</a>
                        <br>
                        <span>
                            Mobiltelefon:<br>
                            '.$row['telefon'].'
                        </span>
                    </div>
                </div>
            ';
        }

        echo '
        <hr style="margin: 10px 0 10px 0">
        <center>
        ';

        $strSQL = "SELECT DISTINCT bereich FROM vorstand WHERE darstellung = 'list'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="vadditional_info">
                <b>'.$row['bereich'].'</b><br>
            ';

            $bereich = $row['bereich'];
            $strSQLl = "SELECT * FROM vorstand WHERE darstellung = 'list' AND bereich = '$bereich'";
            $rsl=mysqli_query($link,$strSQLl);
            while($rowl=mysqli_fetch_assoc($rsl))
            {
                echo $rowl['name'].'<br>';
            }

            echo '</div>';
        }

        echo '</center>';

    include("footer.php");
?>

