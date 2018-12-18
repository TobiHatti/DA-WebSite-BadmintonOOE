<?php

//=================================
// Connect to MySQL Database
//=================================
// Database-Hostname
$sqlConfigDatabaseHost = getenv("MYSQLDB_SERVER");

// Database-Username
$sqlConfigDatabaseUser = getenv("MYSQLDB_USERNAME");

// Database-Password
$sqlConfigDatabasePass = getenv("MYSQLDB_PASSWORD");

// Database-Name
$sqlConfigDatabaseName = getenv("MYSQLDB_DBNAME");

//=================================
// BackUp-Path
//=================================

// Path to directory where backups should be stored
$sqlConfigBackupPath = "backup/";

?>