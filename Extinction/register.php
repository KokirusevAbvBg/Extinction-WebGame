<html>

    <?php 
        require_once('connect_db.php');
        require_once('functions.php'); 

        if (isset($_SESSION['username'])) {     
            header("Location:index.php");
        }
        
        if(isset($_POST['submit'])){
            register();
        }
        
        include("header.php");
    ?>


    <form name="regform" class="regform" method="post">
        <fieldset>
            <div><label for="username">Nickname:</label><input type='text' name='username'  /><br /></div>
            <div><label for="password">Password:</label><input type='password' name='password' /><br /> </div>
            <div><label for="confirm">Confirm Password:</label><input type='password' name='confirm' /><br /></div>
            <div><label for="email">Email:</label> <input type='text' name='email' /><br /> </div>
            <input type='submit' value='Register' name="submit" class="regbutton" />
        </fieldset>
    </form>
    </body>

</html>