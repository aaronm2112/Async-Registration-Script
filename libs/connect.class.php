<?php
#template for database connection -- Used for registration
$mysqli = new mysqli("localhost", "root", "", "database");
if ($mysqli->connect_errno) {
    echo "Could not connect to database. Error:" . $mysqli->connect_errno;
    exit();
}
?>