<?php
    require("header.php");

    echo '<h1>Trainingsgruppen</h1><hr>';

    if(isset($_GET['eintragen']))
    {
        echo '<h3>Spieler zu Gruppe "'.MySQL::Scalar("SELECT trainingsgruppe FROM trainingsgruppen WHERE tgURL = ?",'s',$_GET['eintragen']).'" hinzuf&uuml;gen</h3>';

        $tgID = MySQL::Scalar("SELECT id FROM trainingsgruppen WHERE tgURL = ?",'s',$_GET['eintragen']);

        echo '
            <iframe src="/memberAddFrame?assignUser=tg&tgID='.$tgID.'" frameborder="0" style="width: 100%; height: 400px;"></iframe>
        ';

    }
    else
    {
        $trainingsgruppen = MySQL::Cluster("SELECT * FROM trainingsgruppen");
        foreach($trainingsgruppen as $tg)
        {
            echo '<h3><sub>Gruppe</sub>&nbsp;&nbsp;&nbsp;'.$tg['trainingsgruppe'].'</h3><hr>';
            echo '<a href="/trainingsgruppen/'.$tg['tgURL'].'/eintragen">Spieler hinzuf&uuml;gen</a><br>';

            $tgData = MySQL::Cluster("SELECT * FROM members_trainingsgruppen INNER JOIN members ON members_trainingsgruppen.playerID = members.playerID WHERE members_trainingsgruppen.tgID = ?",'i',$tg['id']);
            foreach($tgData as $memberData)
            {
                if($memberData['gender']=='M') $styleBorder = "border-left: 5px groove blue;";
                else $styleBorder = "border-left: 5px groove red;";

                echo '
                    <div class="nwkaderCard" style="'.$styleBorder.'">
                        <img src="'.(($memberData['image']!="") ? ('/content/members/'.$memberData['image']) : '/content/user.png' ).'" alt="" />
                        <div>
                            <b>'.$memberData['lastname'].'</b> '.$memberData['firstname'].'<br>
                            Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($memberData['birthdate']))).'<br>
                            <span style="color: #696969">'.MySQL::Scalar("SELECT CONCAT_WS(' ',verein,ort) FROM vereine WHERE kennzahl = ?",'s',$memberData['clubID']).'</span>
                        </div>
                        <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                        ';
                        //if(CheckPermission("EditNWK")) echo EditButton("/nachwuchskader?edit=".$memberData['id'],true);
                        //if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","nachwuchskader",$memberData['id'],true);
                        echo '
                        </div>
                    </div>
                ';
            }
            echo '<br><br><br>';
        }
    }


    include("footer.php");
?>