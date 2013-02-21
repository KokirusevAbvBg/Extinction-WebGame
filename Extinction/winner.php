<html>
    <?php
        include("header.php");  
        if (!isset($_GET['winner'])) {     
            header("Location:index.php");
        }
        
        if($_GET['winner']==1){
            echo '<h1 align="center" class="greeting">You won the battle</h1>';
        }else{
            echo '<h1 align="center" class="greeting">You lost the battle</h1>';             
        }
    ?>
    
    </body>
</html>