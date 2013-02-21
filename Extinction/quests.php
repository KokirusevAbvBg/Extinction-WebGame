<html>
    <?php
        include("header.php");  

        if (!isset($_SESSION['id'])) {     
            header("Location:error.php");
        }else{
            $id=$_SESSION['id'];
        }


    ?>



    </body>
</html>