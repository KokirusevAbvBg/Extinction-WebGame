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
        
        if(isset($_POST['submit'])){
            add_quest();
        }

    ?>

    <form name="questform" class="regform" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" >
        <fieldset>
            <div><label for="name">Name:</label><input type='text' name='name' /><br /></div>
            <div><label for="description">Description:</label>
                <textarea rows="4" cols="30" name="description">
            </textarea><br /> </div>
            <div><label for="img">Points:</label><input type='text' name='points' size="3" value="0"/><br /></div>
            <div><label for="health">Experience Reward:</label> <input type='exp_reward' name='health' size="3" value="0"/><br /> </div>  
            <div><label for="damage">Cash Reward:</label> <input type='text' name='cash_reward' size="3" value="0"/><br /> </div> 
                        <div><label for="damage">Level:</label> <input type='text' name='level' size="3" value="0"/><br /> </div>   
            <input type='submit' value='Add' name="submit" class="regbutton" />

        </fieldset>
    </form>

    </body>
</html>