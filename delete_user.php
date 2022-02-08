<?php
require_once("connect.php");

if ($_SESSION['admin'] != 1){echo '<script>window.location.href = "staff.php"</script>';}

$login = $_GET['login'];

if ($login == 'root'){
    header("location: ../index.php");
    exit;
}
else{
    $sql = $conn->prepare("DELETE FROM Staff WHERE login = :login");
    $sql->bindParam(":login", $login);
    $sql->execute();

    $user_destroyed = true;
    header("location: ../users.php");
    exit;
}
?>