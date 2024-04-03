<?php
/*****************************************************
 * model.php                                         *
 *                                                   *
 * Project : Session project                         *
 * Course : GLO-7009 - Software security             *
 * Team : Team 2                                     *
 * Session : Winter 2024                             *
 * University : Laval University                     *
 * Version : 1.0                                     *
 *****************************************************/

/*****************************************************
 *                     BOOTSTRAP                     *
 *****************************************************/
require("../includes/bootstrap.php");

/*****************************************************
 *                     FUNCTIONS                     *
 *****************************************************/
function vulnerable_query($value) {
    global $db;
    $sql_request = "SELECT user_firstname, user_lastname FROM users WHERE user_firstname='$value'";
    return mysqli_query($db, $sql_request);
}

function safe_query($value) {
    global $db;

    $sql_statement = $db->prepare("SELECT user_firstname, user_lastname FROM users WHERE user_firstname=?");
    $sql_statement->bind_param("s", $value);
    $sql_statement->execute();
    return $sql_statement->get_result();
}

function display_sql_results($sql_result) {
    if (mysqli_num_rows($sql_result) == 0) {
        $results = "Aucun résultat";
    } else {
        $results = "";
        while ($row = $sql_result->fetch_assoc()) {
            $results .= $row["user_firstname"]." ".$row["user_lastname"]."<br />";
        }
    }

    return $results;
}
