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

    <form name="itemform" class="regform" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <fieldset>
            <div><label for="name">Name:</label><input type='text' name='name' /><br /></div>
            <div><label for="description">Description:</label>
                <textarea rows="4" cols="30" name="description">
            </textarea><br /> </div>
            <div><label for="img">Image:</label><input type="file" name="file" id="file" /><br /></div>
            <div><label for="health">Health:</label> <input type='text' name='health' size="3" value="0"/><br /> </div>  
            <div><label for="damage">Damage:</label> <input type='text' name='damage' size="3" value="0"/><br /> </div>  
            <div><label for="defense">Defense:</label> <input type='text' name='defense' size="3" value="0"/><br /> </div>  
            <div><label for="luck">Luck:</label> <input type='text' name='luck' size="3" value="0"/><br /> </div>  
            <div><label for="price">Price:</label> <input type='text' name='price' size="5" value="0"/><br /> </div>  
            <div><label for="type">Type:</label> <select name="type" size="1">
                    <option value="weapon">Weapon</option>
                    <option value="armor">Armor</option>
            </select><br /> </div>  
            <input type='submit' value='Add' name="submit" class="regbutton" />

        </fieldset>
    </form>

    </body>
</html>