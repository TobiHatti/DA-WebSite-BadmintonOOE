<?php
    include('../header.php');



    $strSQL = "SELECT * FROM news";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        $id = $row['id'];
        foreach($tags = explode('||',$row['tags']) as $tag)
        {
            echo $tag.'<br>';  
            if($tag != 'Bundesliga' AND $tag != 'International' AND $tag != 'Nachwuchs' AND $tag != 'OEBV-RLT' AND $tag != 'OEM' AND $tag != 'OOEBV-RLT' AND $tag != 'OOEM' AND $tag != 'Top-News' AND $tag != 'Verbandsintern')
            {

                /*
                if($tag == 'M')
                {
                    $newTagString = str_replace('M','OEM',$row['tags']);
                    MySQLNonQuery("UPDATE news SET tags = '$newTagString' WHERE id = '$id'");
                }

                if($tag == 'OM')
                {
                    $newTagString = str_replace('OM','OOEM',$row['tags']);
                    MySQLNonQuery("UPDATE news SET tags = '$newTagString' WHERE id = '$id'");
                }

                if($tag == 'BV-RLT')
                {
                    $newTagString = str_replace('BV-RLT','OEBV-RLT',$row['tags']);
                    MySQLNonQuery("UPDATE news SET tags = '$newTagString' WHERE id = '$id'");
                }

                if($tag == 'OBV-RLT')
                {
                    $newTagString = str_replace('OBV-RLT','OOEBV-RLT',$row['tags']);
                    MySQLNonQuery("UPDATE news SET tags = '$newTagString' WHERE id = '$id'");
                }
                */

                echo $tag.'<br>';
            }
        }
    }


    include('../footer.php');
?>