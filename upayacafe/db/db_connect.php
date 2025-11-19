<?php
// USERS database
$userServer = "localhost";
$userName = "root";
$userPass = "";
$userDB = "upayacafe";

$conn_users = new mysqli($userServer, $userName, $userPass, $userDB);
if ($conn_users->connect_error) {
    die("Connection failed (Users DB): " . $conn_users->connect_error);
}

// INVENTORY database
$invServer = "localhost";
$invName = "root";
$invPass = "";
$invDB = "upaya_inventory_db";

$conn_inventory = new mysqli($invServer, $invName, $invPass, $invDB);
if ($conn_inventory->connect_error) {
    die("Connection failed (Inventory DB): " . $conn_inventory->connect_error);
}
?>
