<?php
/*****************************************************
 *                     BOOTSTRAP                     *
 *****************************************************/
require("../includes/bootstrap.php");

/*****************************************************
 *                     FUNCTIONS                     *
 *****************************************************/
function generate_token($size) {
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $length = strlen($characters);
    $code = "";

    for ($i = 0; $i < $size; $i++) {
        $rand = rand(0, $length - 1);
        $code .= $characters[$rand];
    }

    return $code;
}

function login($email, $password) {
    global $db;

    $req = "SELECT user_id FROM users WHERE user_email = ? AND user_password = ?";
    $stmt = mysqli_prepare($db, $req);
    $password_hash = hash('sha256', md5($password));
    mysqli_stmt_bind_param($stmt, "ss", $email, $password_hash);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $res);
    mysqli_stmt_fetch($stmt);
    $id = $res;
    mysqli_stmt_close($stmt);

    if (isset($id)) {
        $req = "INSERT INTO tokens (token_code, token_user_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($db, $req);
        $token = generate_token(50);
        mysqli_stmt_bind_param($stmt, "si", $token, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $token;
    }

    return null;
}

function is_logged_in($token) {
    global $db;

    $req = "SELECT token_code FROM tokens WHERE token_code = ?";
    $stmt = mysqli_prepare($db, $req);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $nb = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    return $nb > 0;
}

function get_logged_in_user_fullname($token) {
    global $db;

    $req = "SELECT user_firstname, user_lastname FROM users
            INNER JOIN tokens ON user_id = token_user_id
            WHERE token_code = ?";
    $stmt = mysqli_prepare($db, $req);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $first_name, $last_name);
    mysqli_stmt_fetch($stmt);
    $full_name = $first_name." ".$last_name;
    mysqli_stmt_close($stmt);

    return $full_name;
}

function logout($token) {
    global $db;

    $req = "DELETE FROM tokens WHERE token_code = ?";
    $stmt = mysqli_prepare($db, $req);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function sendmail($from_name, $from, $to, $subject, $message, $message_type, $fail_redirect) {
    if (!filter_var($from, FILTER_VALIDATE_EMAIL) && !empty($from)) {
        $from_email = $from."@".$_SERVER["HTTP_HOST"];
    } else if (empty($from)) {
        header("Location: ".$fail_redirect);
        exit();
    } else {
        $from_email = $from;
    }

    $from_address = $from_name." <".$from_email.">";

    $headers = array(
        "MIME-Version" => "1.0",
        "Content-type" => ($message_type == "html" ? "text/html" : "text")."; charset=utf-8",
        "From" => $from_address,
        "Reply-To" => $from_email
    );

    return mail($to, $subject, $message,$headers, "-f".$from_email);
}
