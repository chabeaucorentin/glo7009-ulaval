<?php
/*****************************************************
 *                     BOOTSTRAP                     *
 *****************************************************/
require("../includes/bootstrap.php");

/*****************************************************
 *                  UPLOAD EXECUTION                 *
 *****************************************************/
$image = $_FILES["profile"];

if ($image) {
    $image_name = basename($image["name"]);
    if (!$image["error"] && !move_uploaded_file($image["tmp_name"], "uploads/".$image_name)) {
        $error = "Une erreur s'est produite lors de la mise en ligne de la photo de profil !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>Exécution par mise en ligne</title>
    </head>
    <body>
        <h1>Exécution par mise en ligne</h1>
        <div>
            <h2>Présentation</h2>
            <section>
                <p>Présenter la vulnérabilité</p>
            </section>
        </div>
        <hr />
        <div>
            <h2>Démonstration</h2>
            <section>
                <h3>Photo de profil</h3>
                <form enctype="multipart/form-data" method="POST">
                    <?php
                        if (isset($error)) {
                            echo '<p style="color: red">'.$error.'</p>';
                        }
                    ?>
                    <label for="profile">Sélectionnez une photo de profil :</label>
                    <input id="profile" name="profile" type="file" />
                    <input type="submit" value="Mettre en ligne" />
                </form>
            </section>
            <section>
                <h3>Contenu</h3>
                <p><img src="uploads/<?php echo $image_name; ?>" height="250px"></p>
            </section>
        </div>
        <hr />
        <div>
            <h2>Exploitation</h2>
            <section>
                <h3>Mise en ligne d'un fichier malveillant</h3>
                <a href="upload.php" target="_blank"><button>Mettre en ligne</button></a>
            </section>
            <section>
                <h3>Emplacement du fichier</h3>
                <p>Afficher le lien de mise en ligne.</p>
            </section>
        </div>
        <hr />
        <div>
            <h2>Correction</h2>
            <section>
                <p>Présenter la correction</p>
            </section>
        </div>
    </body>
</html>
