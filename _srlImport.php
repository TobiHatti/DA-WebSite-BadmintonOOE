<?php

    require("header.php");

    $countPlayerNoNr = 0;
    $countPlayerHaveNr = 0;

    $row = 1;
    if (($handle = fopen("files/_development/SRL/SRL1112.csv", "r")) !== FALSE)
    {
        //Skip first line
        $data = fgetcsv($handle, 1000, ";");

        echo '<table>';
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE)
        {

            $num = count($data);
             $email = '';

            $row++;

            $pass = false;

            for ($c=0; $c < $num; $c++)
            {
                if($c == 0)
                {
                    if(str_replace(' ','',str_replace(':','',$data[$c])) == "Herren") { $gender = 'M'; $pass = true; continue; }
                    else if(str_replace(' ','',str_replace(':','',$data[$c])) == "Damen") { $gender = 'F'; $pass = true; continue; }
                    else $rank =  intval($data[$c]);;
                }
                if($c == 1) $lastname = $data[$c];
                if($c == 2) $firstname = $data[$c];
                if($c == 3) $playerID = intval($data[$c]);
                if($c == 4) $team = intval($data[$c]);
                if($c == 5) $clubID = intval($data[$c]);
                if($c == 6) $mf = $data[$c];
                if($c == 7) $mobile = $data[$c];
                if($c == 8) $email = $data[$c];
                if($c == 9) $assignID = $data[$c];


            }

            if($pass != true)
            {



                echo '
                <tr style="background: '.($gender == 'M' ? '#99E6FF' : '#FFC7FF').'">
                <td>'.$rank.'</td>
                <td>'.$lastname.'</td>
                <td>'.$firstname.'</td>
                <td>'.$playerID.'</td>
                <td>'.$team.'</td>
                <td>'.$clubID.'</td>
                <td>'.$gender.'</td>
                <td>'.$mf.'</td>
                <td>'.$mobile.'</td>
                <td>'.(isset($email) ? $email : '').'</td>
                <td>'.$assignID.'</td>
                </tr>
                ';


                if(!MySQL::Exist("SELECT * FROM members WHERE playerID = ?",'s',$playerID))
                {
                    $countPlayerNoNr++;
                    $memberID = uniqid();
                    //MySQL::NonQuery("INSERT INTO members (id,clubID,playerID,gender,firstname,lastname,mobileNr,email) VALUES (?,?,?,?,?,?,?,?)",'@s',$memberID,$clubID,$playerID,$gender,$firstname,$lastname,$mobile,$email);
                }
                else
                {
                    $countPlayerHaveNr++;
                    $memberID = MySQL::Scalar("SELECT id FROM members WHERE playerID = ?",'s',$playerID);
                }

                $year = '2011-2012';

                //MySQL::NonQuery("INSERT INTO members_spielerranglisten (id,memberID,year,position,team,mf,currentClubID,assignedClubID) VALUES ('',?,?,?,?,?,?,?)",'@s',$memberID,$year,$rank,$team,$mf,$clubID,$assignID);

            }
        }

         echo '</table>';
        fclose($handle);
    }


    echo 'No: '.$countPlayerNoNr.'<br><br>';
    echo 'Have: '.$countPlayerHaveNr.'<br><br>';


    include("footer.php");

?>