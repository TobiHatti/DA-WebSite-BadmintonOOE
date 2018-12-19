<?php

//=================================
// Connect to MySQL Database
//=================================
// Database-Hostname
$uploadConfigDatabaseHost = getenv("MYSQLDB_SERVER");

// Database-Username
$uploadConfigDatabaseUser = getenv("MYSQLDB_USERNAME");

// Database-Password
$uploadConfigDatabasePass = getenv("MYSQLDB_PASSWORD");

// Database-Name
$uploadConfigDatabaseName = getenv("MYSQLDB_DBNAME");


// Default upload path
$uploadConfigDefaultUploadDirectory = "upload/";

?>