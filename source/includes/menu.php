<?php
/*****************************************************
 * menu.php                                          *
 *                                                   *
 * Project : Session project                         *
 * Course : GLO-7009 - Software security             *
 * Team : Team 2                                     *
 * Session : Winter 2024                             *
 * University : Laval University                     *
 * Version : 1.0                                     *
 *****************************************************/

/*****************************************************
 *                       MENU                        *
 *****************************************************/
$menu = array(
    /*****************************************************
     *                    CORENTIN C.                    *
     *****************************************************/
    array(
        "name" => "Corentin C.",
        "full_name" => "Corentin Chabeau",
        "category" => "Vulnérabilités d'exécution de code arbitraire",
        "folder" => "corentinc",
        "files" => array(
            array(
                "name" => "upload.php",
                "title" => "Exécution par mise en ligne"
            ),
            array(
                "name" => "include.php",
                "title" => "Exécution par inclusion"
            )
        )
    ),

    /*****************************************************
     *                    CORENTIN L.                    *
     *****************************************************/
    array(
        "name" => "Corentin L.",
        "full_name" => "Corentin Labelle",
        "category" => "Vulnérabilités d'injection de code",
        "folder" => "corentinl",
        "files" => array(
            array(
                "name" => "sql.php",
                "title" => "Injection SQL"
            ),
            array(
                "name" => "xss.php",
                "title" => "Injection XSS"
            )
        )
    ),

    /*****************************************************
     *                      WILLIAM                      *
     *****************************************************/
    array(
        "name" => "William",
        "full_name" => "William Malenfant",
        "category" => "Vulnérabilités dans les mécanismes d'authentification",
        "folder" => "william",
        "files" => array(
            array(
                "name" => "cookies.php",
                "title" => "Authentification par cookies"
            ),
            array(
                "name" => "phishing.php",
                "title" => "Authentification par phish."
            ),
        )
    )
);
