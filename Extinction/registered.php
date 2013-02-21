<html>
    <?php
        include("header.php");  
        if (!isset($_GET['true'])) {     
            header("Location:index.php");
        }
    ?>


    <h1 align="center" class="greeting">You have been registered.You can login now</h1>
    </body>
</html>