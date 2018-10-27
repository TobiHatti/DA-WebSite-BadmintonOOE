<?php

    require("../downloading.php");

    $year = $_GET['year'];
    $club = $_GET['club'];
    $originalClub = $club;

    if($club == "alle") $strSQLc = "SELECT DISTINCT club FROM reihung WHERE year = '$year'";
    else if(StartsWith($club,"M"))
    {
        $selectedClubs = str_replace('M','',$club);
        $clubArray = explode('-',$selectedClubs);

        $first = true;
        foreach($clubArray AS $club)
        {
            if($first) $sqlClubExtension = "club = '$club'";
            else $sqlClubExtension .= " OR club = '$club'";

            $first = false;
        }

        $strSQLc = "SELECT DISTINCT club FROM reihung WHERE year = '$year' AND ($sqlClubExtension)";
    }
    else $strSQLc = "SELECT DISTINCT club FROM reihung WHERE year = '$year' AND club = '$club'";

    $content = LetterCorrection("spielerid;clubid;name;vorname;gebdatum;sex;nat;\r\n");

    $rsc=mysqli_query($link,$strSQLc);
    while($rowc=mysqli_fetch_assoc($rsc))
    {
        $club = $rowc['club'];
        $strSQL = "SELECT * FROM reihung INNER JOIN members ON reihung.member = members.number WHERE reihung.club = '$club' AND reihung.year = '$year'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $content .= LetterCorrection($row['number'].';'.$row['club'].';'.$row['lastname'].';'.$row['firstname'].';'.$row['birthdate'].';'.str_replace('W','F',$row['gender']).';;'."\r\n");
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