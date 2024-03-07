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
        "folder" => "william",
        "files" => array(
            array(
                "name" => "session.php",
                "title" => "Authentification par session"
            ),
            array(
                "name" => "cookies.php",
                "title" => "Authentification par cookies"
            )
        )
    )
);
