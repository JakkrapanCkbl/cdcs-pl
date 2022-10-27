<?php

$host     = "172.18.50.10\CK_BLUELINE2_DB\";//Ip of database, in this case my h$
$user     = "sa";       //Username to use
$pass     = "Automodx54165";//Password for that user
$dbname   = "ppls_db";//Name of the database

try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e)
{
  echo $e->getMessage();
}
?>