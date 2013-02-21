<html>
    <?php
        include("header.php"); 
        
        if (isset($_SESSION['id'])) {    
            if(!(is_Admin($_SESSION['id']))==1){
                header("Location:index.php");         
            }            
        }else{
            header("Location:index.php");
        }


        get_users();

        if(isset($_POST['submit']) and isset($_POST['id'])){
            set_UserStatus($_POST['submit'],$_POST['id']);
        }

    ?>



    </body>
</html>