<?php
/*****************************************************
 * page.php                                          *
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
require($config["site_path"]."/views/menu.php");

/*****************************************************
 *                   DEFAULT PAGE                    *
 *****************************************************/
function render_page($title, $content) {
    global $config;

    $site_link = $config["site_link"];

    return '<!--
    '.basename($_SERVER['SCRIPT_NAME']).'

    Projet : Projet de session
    Cours : GLO-7009 - Sécurité des logiciels
    Équipe : Équipe 2
    Session : Hiver 2024
    Université : Université Laval
    Version : 1.0
-->

<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- META DATA -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
        <meta name="author" content="Équipe 2" />
        <meta name="description" content="Projet de session (Équipe 2)"/>
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-title" content="GLO-7009" />
        <meta name="application-name" content="GLO-7009 - Projet de session" />
        <meta name="theme-color" content="#F4F4F4" />

        <!-- TITLE -->
        <title>'.$title.' | GLO-7009</title>

        <!-- FAVICON -->
        <link rel="apple-touch-icon" href="'.$site_link.'/assets/images/favicon/apple-touch-icon.png" sizes="180x180" />
        <link rel="shortcut icon" href="'.$site_link.'/assets/images/favicon/favicon.ico" sizes="32x32" />
        <link rel="icon" href="'.$site_link.'/assets/images/favicon/favicon.svg" sizes="any" type="image/svg+xml" />
        <link rel="manifest" href="'.$site_link.'/assets/images/favicon/manifest.json" />

        <!-- PRISM CSS -->
        <link rel="stylesheet" href="'.$site_link.'/assets/vendor/prismjs/prism.min.css" />

        <!-- DROPIFY CSS -->
        <link rel="stylesheet" href="'.$site_link.'/assets/vendor/dropify/css/dropify.min.css" />

        <!-- STYLE CSS -->
        <link rel="stylesheet" href="'.$site_link.'/assets/css/reset.css" />
        <link rel="stylesheet" href="'.$site_link.'/assets/css/fonts.css" />
        <link rel="stylesheet" href="'.$site_link.'/assets/css/style.css" />
    </head>
    <body>
        <!-- HEADER -->
        <header class="topbar">
            <a class="left" href="'.$site_link.'/">
                <img class="logo" src="'.$site_link.'/assets/images/logo.svg" alt="Université Laval" />
                <div class="separator"></div>
                <span class="course">GLO-7009</span>
            </a>
            <div class="right">
                <p>'.$title.'</p>
            </div>
        </header>
        <!-- END HEADER -->

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <nav>
                '.render_menu().'
            </nav>
        </aside>
        <!-- END SIDEBAR -->

        <!-- MAIN CONTAINER -->
        <main class="container">
            '.$content.'
        </main>
        <!-- END MAIN CONTAINER -->

        <!-- SMALL DEVICE -->
        <main class="container small-device">
            <article class="error">
                <p>Une résolution d’au moins <strong>1050x550 pixels</strong> est requise pour consulter la plateforme.</p>
            </article>
        </main>
        <!-- END SMALL DEVICE -->

        <!-- FOOTER -->
        <footer>
            <p>GLO-7009 - Projet de session (Équipe 2)</p>
        </footer>
        <!-- END FOOTER -->

        <!-- PRISM JS -->
        <script src="'.$site_link.'/assets/vendor/prismjs/prism.min.js"></script>

        <!-- JQUERY JS -->
        <script src="'.$site_link.'/assets/vendor/jquery/jquery.min.js"></script>

        <!-- DROPIFY JS -->
        <script src="'.$site_link.'/assets/vendor/dropify/js/dropify.min.js"></script>

        <!-- SCRIPT JS -->
        <script src="'.$site_link.'/assets/js/script.js"></script>
    </body>
</html>';
}

/*****************************************************
 *                   MESSAGE PAGE                    *
 *****************************************************/
function render_error($title, $message) {
    $content = '<article class="error">
                    <p>'.$message.'</p>
                </article>';

    return render_page($title, $content);
}

/*****************************************************
 *                   MALICIOUS PAGE                  *
 *****************************************************/
function render_malicious($title, $presentation, $demonstration, $exploit, $fix) {
    switch ($_GET["view"]) {
        case "demonstration":
            $view = "demonstration";
            $article = $demonstration;
            break;
        case "exploit":
            $view = "exploit";
            $article = $exploit;
            break;
        case "fix":
            $view = "fix";
            $article = $fix;
            break;
        default:
            $view = "presentation";
            $article = $presentation;
    }

    $content = '<div class="vulnerability">
                    <nav>
                        <ul class="navigation">
                            <li>
                                <a'.(($view == "presentation") ? ' class="active"' : '').' href="?view=presentation">
                                    Présentation
                                </a>
                            </li>
                            <li>
                                <a'.(($view == "demonstration") ? ' class="active"' : '').' href="?view=demonstration">
                                    Démonstration
                                </a>
                            </li>
                            <li>
                                <a'.(($view == "exploit") ? ' class="active"' : '').' href="?view=exploit">
                                    Exploitation
                                </a>
                            </li>
                            <li>
                                <a'.(($view == "fix") ? ' class="active"' : '').' href="?view=fix">
                                    Correction
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <article>
                        '.$article.'
                    </article>
                </div>';

    return render_page($title, $content);
}
