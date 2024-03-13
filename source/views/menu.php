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
require($config["site_path"]."/includes/menu.php");

/*****************************************************
 *                     FUNCTIONS                     *
 *****************************************************/
function render_menu()
{
    global $config, $menu;

    $navigation = '<ul class="navigation">';
    foreach ($menu as $name) {
        $navigation .= '<li class="category">'.$name["name"].'</li>';
        foreach ($name['files'] as $file) {
            $file_path = $name["folder"]."/".$file["name"];
            $navigation .= '<li class="item">
                <a'.((substr($_SERVER['SCRIPT_NAME'], -strlen($file_path)) === $file_path) ? ' class="active"' : '').'
                    href="'.$config["site_link"]."/".$file_path.'">
                    '.$file["title"].'
                </a>
            </li>';
        }
    }
    $navigation .= '</ul>';

    return $navigation;
}

function render_vulnerabilities()
{
    global $config, $menu;

    $navigation = '<ul class="list">';
    foreach ($menu as $name) {
        $navigation .= '<li><strong>'.$name["full_name"].'</strong><br />'.$name["category"].'<ul>';
        foreach ($name['files'] as $file) {
            $navigation .= '<li>
                <a href="'.$config["site_link"]."/".$name["folder"]."/".$file["name"].'">
                    '.$file["title"].'
                </a>
            </li>';
        }
        $navigation .= '</ul></li>';
    }
    $navigation .= '</ul>';

    return $navigation;
}
