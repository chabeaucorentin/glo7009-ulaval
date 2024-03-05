<?php
/*****************************************************
 *                     BOOTSTRAP                     *
 *****************************************************/
require("includes/bootstrap.php");
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>GLO-7009</title>
    </head>
    <body>
        <h1>GLO-7009</h1>
        <section>
            <h2>Corentin Chabeau</h2>
            <p>Vulnérabilités d'exécution de code arbitraire</p>
            <ul>
                <li><a href="corentinc/upload.php">Exécution par mise en ligne</a></li>
                <li><a href="corentinc/include.php">Exécution par inclusion</a></li>
            </ul>
        </section>
        <section>
            <h2>Corentin Labelle</h2>
            <p>Vulnérabilités d'injection de code</p>
            <ul>
                <li><a href="corentinl/sql.php">Injection SQL</a></li>
                <li><a href="corentinl/xss.php">Injection XSS</a></li>
            </ul>
        </section>
        <section>
            <h2>William Malenfant</h2>
            <p>Vulnérabilités dans les mécanismes d'authentification</p>
            <ul>
                <li><a href="william/session.php">Authentification par session</a></li>
                <li><a href="william/cookies.php">Authentification par cookies</a></li>
            </ul>
        </section>
    </body>
</html>
