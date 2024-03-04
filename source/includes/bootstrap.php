<?php
require("config.php");

$db = mysqli_connect($config["dbhost"], $config["dbuser"], $config["dbpassword"], $config["dbname"]);

if (mysqli_connect_errno()) {
    echo "Erreur lors de la connexion à MySQL : " . mysqli_connect_error();
    exit();
}

mysqli_set_charset($db, "utf8");

session_start();
