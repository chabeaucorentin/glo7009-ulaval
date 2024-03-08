<?php
    // Original XSS injection
    if (isset($_GET['argument1'])) {
        $argument1 = $_GET['argument1'];
        #$argument1 = htmlspecialchars($argument1);
        echo 'Value of argument1: '. $argument1;
    }
?>


<!DOCTYPE html>
<html>
<head>
    <title>Roche Papier Ciseau</title>
    <style>
        .result {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <img src="rpc.jpg" width="50" height="50"><br>
    To win the game, you need to win 5 rounds.<br>
    Step for XSS Injection:<br>
    1. Inspect the image<br>
    2. Modify the image path (src attribute)<br>
    3. Inject this line: onerror="javascript:(()=>{wins = 5;})()"<br>
    4. Play one round<br>

    <h1>Roche Papier Ciseau</h1>


    <form method="post" onsubmit="return playGame()">

        <label for="choice">Choix: </label>
        <select name="choice" id="choice">
            <option value="roche">Roche</option>
            <option value="papier">Papier</option>
            <option value="ciseau">Ciseau</option>
        </select>

        <button type="submit">Jouer!</button>

    </form>

    <script>

        var games = 0;
        var wins = 0;

        function playGame() {
            games++;
            var userChoice = document.getElementById("choice").value;
            var choices = ["roche", "papier", "ciseau"];
            var computerChoice = choices[Math.floor(Math.random() * choices.length)];
            
            if (userChoice == computerChoice) {
                resultMessage = "Egalite!";
            } else if ((userChoice == "roche" && computerChoice == "ciseau") ||
                       (userChoice == "papier" && computerChoice == "roche") ||
                       (userChoice == "ciseau" && computerChoice == "papier")) {
                resultMessage = "Bravo, tu as gagne!";
                wins++;
            } else {
                resultMessage = "HAHA Tu as perdu!";
            }
            
            alert(
                "Ton choix: " + userChoice + "\n" +
                "Mon choix: " + computerChoice + "\n\n" + 
                resultMessage + "\n\n" + 
                "Nombre de partie jouee: " + games + "\n" + 
                "Nombre de victoire: " + wins);

            isGameOver();

            return false;
        }

        function isGameOver() {
            if (wins >= 5) {
                alert("Bravo, tu as gagne le jeu! WOUHOU");
                games = 0;
                wins = 0;
            }
        }

    </script>
</body>
</html>
