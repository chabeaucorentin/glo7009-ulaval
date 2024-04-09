<?php
/*****************************************************
 * bootstrap.php                                     *
 *                                                   *
 * Project : Session project                         *
 * Course : GLO-7009 - Software security             *
 * Team : Team 2                                     *
 * Session : Winter 2024                             *
 * University : Laval University                     *
 * Version : 1.0                                     *
 *****************************************************/

/*****************************************************
 *                       IMPORT                      *
 *****************************************************/
require("config.php");
require($config["site_path"]."/views/page.php");

error_reporting(1);

/*****************************************************
 *                      DATABASE                     *
 *****************************************************/
$db = mysqli_connect($config["db_host"], $config["db_user"], $config["db_password"], $config["db_name"]);

if (mysqli_connect_errno()) {
    echo render_error("Erreur lors de la connexion à la base de donnée MySQL", mysqli_connect_error());
    exit();
}

mysqli_set_charset($db, "utf8");
