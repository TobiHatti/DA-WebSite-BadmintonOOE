<?php

//=================================
// Connect to MySQL Database
//=================================
// Database-Hostname
$configDatabaseHost = getenv("MYSQLDB_SERVER");

// Database-Username
$configDatabaseUser = getenv("MYSQLDB_USERNAME");

// Database-Password
$configDatabasePass = getenv("MYSQLDB_PASSWORD");

// Database-Name
$configDatabaseName = getenv("MYSQLDB_DBNAME");

$sqlConLink = mysqli_connect($configDatabaseHost,$configDatabaseUser,$configDatabasePass,$configDatabaseName) OR die("<br><br><b>Error in sqlConnect.php :</b> Could not connect to Database (Code 1)<br><br>");

?>