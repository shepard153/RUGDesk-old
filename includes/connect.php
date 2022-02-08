<?php
$serverName = "PLTR-10-107-D";
$database = "HELPDESK";
$uid = "aplikacja";
$pwd = "Start.2021";

$conn = new PDO( "sqlsrv:server=$serverName;Database = $database", $uid, $pwd);   
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   

/* Kod wyświetlający błędy w razie problemów z połączeniem.
if( $conn )
{
    echo "Połączono";
}
else
{
    echo "Błąd";
    die( print_r( sqlsrv_errors(), true));
}
*/
?>