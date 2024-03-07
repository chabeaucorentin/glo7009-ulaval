<?php
/*****************************************************
 *                     BOOTSTRAP                     *
 *****************************************************/
require("../includes/bootstrap.php");

/*****************************************************
 *                     FUNCTIONS                     *
 *****************************************************/
function vulnerable_query($value) {
    global $db;
    $sql_request = "SELECT user_firstname, user_lastname FROM users WHERE user_firstname='$value';";
    return mysqli_query($db, $sql_request);
}

function safe_query($value) {
    global $db;

    $sql_statement = $db->prepare("SELECT user_firstname, user_lastname FROM users WHERE user_firstname=?");
    $sql_statement->bind_param("s", $value);
    $sql_statement->execute();
    return $sql_statement->get_result();
}
