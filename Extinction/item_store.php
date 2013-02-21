<html>
    <?php
        include("header.php");  
       
        if (!isset($_SESSION['id'])) {     
            header("Location:error.php");
        }else{
            $id=$_SESSION['id'];
        }
        
        get_items();
        
         if(isset($_POST['submit']) && isset($_POST['id']) && isset($_POST['type'])){
            equip($_POST['id'],$_SESSION['id'],$_POST['type']);
        }

    ?>
        <div id="hp_txt">
        This is your hero panel page.From here you can see your base 
        statisctics:health,level,experience,damage,defense and cash.
        Battle statistics such as battles , wins and defeats are also
        displayed here.<br />
        This is also the place where you can manage your inventory 
        and equipped weapons and armor.<br />
        Remember: the main goal is not only to become stronger,but to
        be the best.That means having more wins than defeats and have
        a higher level.Those are the two factors that will bring you
        at the top of the list.
    </div>
    </body>
</html>