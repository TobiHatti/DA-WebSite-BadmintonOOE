<?php

    require("../downloading.php");

    $year = $_GET['year'];
    $club = $_GET['club'];
    $originalClub = $club;

    if($club == "alle") $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members_spielerranglisten.assignedClubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' GROUP BY members_spielerranglisten.assignedClubID";
    else if(StartsWith($club,"M"))
    {
        $selectedClubs = str_replace('M','',$club);
        $clubArray = explode('-',$selectedClubs);

        $first = true;
        foreach($clubArray AS $sClub)
        {
            if($first) $sqlClubExtension = "members_spielerranglisten.assignedClubID = '$sClub'";
            else $sqlClubExtension .= " OR members_spielerranglisten.assignedClubID = '$sClub'";

            $first = false;
        }

        $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members_spielerranglisten.assignedClubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' AND ($sqlClubExtension) GROUP BY members_spielerranglisten.assignedClubID";
    }
    else $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members_spielerranglisten.assignedClubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' AND members_spielerranglisten.assignedClubID = '$club' GROUP BY members_spielerranglisten.assignedClubID";

    $content = LetterCorrection("spielerid;clubid;name;vorname;gebdatum;sex;nat;\r\n");

    $rsc=mysqli_query($link,$strSQLc);
    while($rowc=mysqli_fetch_assoc($rsc))
    {
        $club = $rowc['assignedClubID'];
        $strSQL = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members_spielerranglisten.assignedClubID = '$club' AND members_spielerranglisten.year = '$year'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $content .= LetterCorrection($row['playerID'].';'.$row['currentClubID'].';'.$row['lastname'].';'.$row['firstname'].';'.$row['birthdate'].';'.$row['gender'].';;'."\r\n");
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