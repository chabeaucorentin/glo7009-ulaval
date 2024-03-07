<?php
/*****************************************************
 *                       MODEL                       *
 *****************************************************/
require("model.php");

/*****************************************************
 *                   SQL INJECTION                   *
 *****************************************************/
// CODE
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection</title>
</head>
<body>
    Enter your first name

    <form method="get">
        <input type="text" name="search_input" 
            value="<?php echo isset($_GET['search_input']) ? $_GET['search_input'] : '' ?>">
        <input type="submit" name="safe_search_button" value="Safe Search!" class="Button">
        <input type="submit" name="vulnerable_search_button" value="Vulnerable Search..." class="Button">
    </form> 
  
    <?php
        if(array_key_exists('safe_search_button', $_GET)) { 
            safe_search_button_callback();
        }
        elseif(array_key_exists('vulnerable_search_button', $_GET)) { 
            vulnerable_search_button_callback();
        }
        
        function safe_search_button_callback() {           
            $sql_result = safe_query($_GET['search_input']);
            display_sql_results($sql_result);
        }

        function vulnerable_search_button_callback() {
            $sql_result = vulnerable_query($_GET['search_input']);
            display_sql_results($sql_result);
        }

        function display_sql_results($sql_result) {
            if (mysqli_num_rows($sql_result) == 0) {
                echo "Nothing found...";
            } 
            else { 
                while ($row = $sql_result->fetch_assoc()) {
                    echo $row['user_firstname'] ." " , $row['user_lastname'] ."<br>";
                }           
            }
        }

    ?>

</body>
</html>

