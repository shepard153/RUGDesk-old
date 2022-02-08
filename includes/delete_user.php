<?php
require("connect.php");

$login = $_GET['login'];
$staffID = $_GET['staffID'];

$sql = $conn->prepare("DELETE FROM Staff WHERE staffID = :staffID AND login = :login");
$sql->bindParam(":login", $login);
$sql->bindParam(":staffID", $staffID);
$sql->execute();

header("location: ../users.php?destroyed=true&login=".$login);
exit;
?>