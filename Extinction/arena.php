<html>
    <?php
        include("header.php");  

        if (!isset($_SESSION['id'])) {     
            header("Location:error.php");
        }else{
            $id=$_SESSION['id'];
        }

        get_users_arena();
        
        if(isset($_POST['submit']) && isset($_POST['id'])){
            battle($_SESSION['id'],$_POST['id']);
        }
    ?>

    </body>
</html>