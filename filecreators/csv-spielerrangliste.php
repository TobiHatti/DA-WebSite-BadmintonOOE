<?php

    require("../downloading.php");

    $year = $_GET['year'];
    $club = $_GET['club'];
    $originalClub = $club;

    if($club == "alle") $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' GROUP BY members.clubID";
    else if(StartsWith($club,"M"))
    {
        $selectedClubs = str_replace('M','',$club);
        $clubArray = explode('-',$selectedClubs);

        $first = true;
        foreach($clubArray AS $sClub)
        {
            if($first) $sqlClubExtension = "members.clubID = '$sClub'";
            else $sqlClubExtension .= " OR members.clubID = '$sClub'";

            $first = false;
        }

        $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' AND ($sqlClubExtension) GROUP BY members.clubID";
    }
    else $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' AND members.clubID = '$club' GROUP BY members.clubID";

    $content = LetterCorrection("spielerid;clubid;name;vorname;gebdatum;sex;nat;\r\n");

    $rsc=mysqli_query($link,$strSQLc);
    while($rowc=mysqli_fetch_assoc($rsc))
    {
        $club = $rowc['clubID'];
        $strSQL = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.clubID = '$club' AND members_spielerranglisten.year = '$year'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $content .= LetterCorrection($row['playerID'].';'.$row['clubID'].';'.$row['lastname'].';'.$row['firstname'].';'.$row['birthdate'].';'.$row['gender'].';;'."\r\n");
        }
    }

    $filename = 'Spielerrangliste-'.$year.'-'.$originalClub.'.csv';
    $path = "../files/spielerranglisten/$filename";
    $pathDL = "files/spielerranglisten/$filename";

    $handle = fopen ($path, 'w');

    fwrite ($handle, $content);
    fclose ($handle);

    Redirect("/forceDownload?file=".urlencode($pathDL));
?>