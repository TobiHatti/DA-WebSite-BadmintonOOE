<?php
    require("header.php");


    if (($handle = fopen("files/_development/members.csv", "r")) !== FALSE)
    {
        //Skip first line
        $data = fgetcsv($handle, 1000, ";");

        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE)
        {
            $number = $data[0];
            $firstname = $data[2];
            $lastname = $data[1];
            $gender = strtoupper($data[4]);
            $bdate = date_format(date_create_from_format("d.m.Y",$data[5]),"Y-m-d");
            $clubID = $data[6];

            $playerData = MySQL::Row("SELECT * FROM members WHERE playerID = ?",'s',$number);

            $memberID = uniqid();

            if(MySQL::Exist("SELECT * FROM members WHERE playerID = ?",'i',$number))
            {
                MySQL::NonQuery("UPDATE members SET active = '1', firstname = ?, lastname = ?, gender = ?, birthdate = ?, clubID = ? WHERE playerID = ?",'ssssii',$firstname,$lastname,$gender,$bdate,$clubID,$number);
            }
            else MySQL::NonQuery("INSERT INTO members (id,active,firstname,lastname,gender,birthdate,playerID,clubID) VALUES (?,'1',?,?,?,?,?,?)",'sssssii',$memberID,$firstname,$lastname,$gender,$bdate,$number,$clubID);

        }


        fclose($handle);
    }
?>