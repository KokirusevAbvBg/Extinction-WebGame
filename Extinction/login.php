<html>
    <?php
        include("header.php"); 
        require_once('connect_db.php');
        require_once('functions.php'); 

        if (isset($_SESSION['username'])) {     
            header("Location:index.php");
        }
        if(isset($_POST['submit'])){
            login();
        }
    ?>

    <form name="regform" class="regform" method="post">
        <fieldset>
            <div><label for="username">Nickname:</label><input type='text' name='username' /><br /></div>
            <div><label for="password">Password:</label><input type='password' name='password' /><br /> </div>
            <input type='submit' value='Login' name="submit" class="regbutton" />
        </fieldset>
    </form>

    </body>
</html>