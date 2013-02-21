<!DOCTYPE html>
<html>
    <?php
        include("functions.php");
        require_once('connect_db.php');  

        $query="UPDATE battle_stats SET battles='1',wins='1',rank='2' 
        WHERE battle_id='8';";
        $result = mysql_query($query);
        if (mysql_affected_rows() == 1) {

            echo "success";
        }  
    ?>

    </body>
</html>
