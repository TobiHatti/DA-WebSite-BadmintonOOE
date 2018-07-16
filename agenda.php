<?php

   require ("header.php");
   echo '<main>';
?>




<head>
  <title>Terminkalender | O&Ouml;. Badmintonverband</title>

  <style>
  body {
    font-family: Arial, Helvetica, sans-serif;
  }
  a {
    text-decoration: none;
  }
  a:hover {
    text-decoration: underline;
  }
  </style>
</head>



<?php

error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('UTC');

// date ok flag
$dateok = false;

// parse parameter
if (isset($_GET['day'])) {
  list($yr, $mo, $da) = explode('-', $_GET['day']);
  $yr = intval($yr);
  $mo = intval($mo);
  $da = intval($da);
  if (checkdate($mo, $da, $yr)) $dateok = true;
}

// if invalid date selected then selected date = today
if (!$dateok) {
  $mo = date('m');
  $da = date('d');
  $yr = date('Y');
}

$offset = date('w', mktime(0,0,0,$mo,1,$yr));
// we must have a value in range 1..7
if ($offset == 0) $offset = 7;

// days in month
$nd = date('d', mktime(0,0,0,$mo+1,0,$yr));

// days array
$days = array();

// reset array
for ($i=0;$i<=42;$i++) $days[$i]['out']= '&nbsp;';

// fill days array
// valid days contain data, invalid days are left blank
$j=1;
for ($i=$offset;$i<=($offset+$nd-1);$i++) {
  $day = $j++;
  $date = $yr.'-'.$mo.'-'.$day;
  $days[$i]['out']= '<a href="?day='.$date.'">'.$day.'</a>';
  $days[$i]['dat']= $date;
}

// output table
echo('<table style="text-align: center; border-collapse: collapse; width: 200px">');
echo('<tr>');
echo('<td colspan="1" style="border: 1px solid Silver;"><a href="?day='.date('Y-m-d', mktime(0,0,0,$mo,$da,$yr-1)).'">&laquo;</a></td>');
echo('<td colspan="5" style="border: 1px solid Silver;"><p class="calendar">'.$yr.'</p></td>');
echo('<td colspan="1" style="border: 1px solid Silver;"><a href="?day='.date('Y-m-d', mktime(0,0,0,$mo,$da,$yr+1)).'">&raquo;</a></td>');
echo('</tr>'."\n");
echo('<tr>');
echo('<td colspan="1" style="border: 1px solid Silver;"><a href="?day='.date('Y-m-d', mktime(0,0,0,$mo,0,$yr)).'">&laquo;</a></td>');
echo('<td colspan="5" style="border: 1px solid Silver;"><p style="font-weight: bold">'.date('F', mktime(0,0,0,$mo,$da,$yr)).'</p></td>');
echo('<td colspan="1" style="border: 1px solid Silver;"><a href="?day='.date('Y-m-d', mktime(0,0,0,$mo+1,1,$yr)).'">&raquo;</a></td>');
echo('</tr>'."\n");
$cntr = 1; // day printing counter
for ($i=1;$i<=6;$i++) {
  echo('<tr>');
  for ($j=1;$j<=7;$j++) {
    $curr = $cntr++;
    if ($days[$curr]['dat'] == $yr.'-'.$mo.'-'.$da) $style = 'bold'; else $style = 'normal';
    echo('<td style="border: 1px solid Silver; width: 14%; font-weight: '.$style.'">'.$days[$curr]['out'].'</td>'."\n");
  }
  echo('</tr>'."\n");
}
echo('</table>');
echo ('</main>');
include ("footer.php");

?>



