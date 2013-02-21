<html>
    <?php
        include("header.php");  



        if(isset($_POST['submit']) and isset($_POST['id'])){
            set_UserStatus($_POST['submit'],$_POST['id']);
        }

        get_banned();

    ?>

    </body>
</html>