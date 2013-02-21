<?php  
    session_start(); 

    function register(){

        if( (isset($_POST['username']) and isset($_POST['password']) and (isset($_POST['confirm']))
        and (!empty($_POST['username'])) and !empty($_POST['password']) and !empty($_POST['confirm'])) ){
            $username=htmlentities (mysql_real_escape_string($_POST['username']));
            $password=htmlentities (mysql_real_escape_string(md5($_POST['password'])));
            $confirm=htmlentities (mysql_real_escape_string(md5($_POST['confirm'])));
            $email=htmlentities (mysql_real_escape_string($_POST['email']));   

            $result=mysql_query("SELECT username FROM users WHERE username='$username'");
            $name_num_rows=mysql_num_rows($result);

            if($name_num_rows!=0){
                $errors[] = '<p class="error">The username is taken</p>';
            }

            $result=mysql_query("SELECT email FROM users WHERE email='$email'");
            $email_num_rows=mysql_num_rows($result);

            if($email_num_rows!=0){
                $errors[] = '<p class="error">The email is taken</p>';
            }

            if($password!=$confirm){
                $errors[] = '<p class="error">The passwords doesnt match</p>';  
            }

        }else{
            $errors[] = '<p class="error">Please fill the fields</p>';  
        }

        if (empty($errors)) {
            $query = "INSERT INTO users (username,password,email) VALUES ('$username','$password','$email')";
            $result = mysql_query($query);
            if (mysql_affected_rows() == 1) {

                set_stats();
                set_battle_stats();
                set_hero_inventory();
                send_email($email);       
                header("Location:registered.php?true=1");
            } else {
                echo '<p class="error">There was an error while registering</p>';
            }

        } else {
            echo '<p class="error">The following error(s) occured:</p>';
            foreach ($errors as $msg) {
                echo  $msg;
            }
        }

    }

    function login(){

        if(isset($_POST['username']) and isset($_POST['password'])
        and !empty($_POST['username']) and !empty($_POST['password'])){

            $username=htmlentities(mysql_real_escape_string($_POST['username']));
            $password=htmlentities(mysql_real_escape_string(md5($_POST['password'])));
            $status=get_UserStatus($username);   

            $query="SELECT id,username,status FROM users 
            WHERE username='$username' AND password='$password'";
            $result=mysql_query($query);
            $count=mysql_num_rows($result);

            if($count==1 and $status!=2){
                $_SESSION['username']=$username;
                $_SESSION['id']=get_id($username);
                header("Location:index.php");                    
            }else if($count==1 and $status==2){
                    header("Location:banned.php?true=1");
                }else{
                    echo 'Incorrect username or passoword';    
            }
        }else{
            echo 'Please fill all the fields';  
        }        
    }

    function send_email($email){
        $to = $email;
        $subject = 'Extinction e-mail address confirmation';
        $message = "
        <p>Hey! Thanks for signing up for the browsergame. Click below to confirm your e-mail address.</p>
        <p><a href='http://website.com/confirm.php?email=$email'>below</a></p>";
        $headers = 'From: NikIvRu@Extinction.com' . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($to,$subject,$message,$headers);

    }

    function email_confrim(){
        if($_GET) {
            $email = $_GET['email'];

            $query = sprintf("SELECT COUNT(id) FROM users WHERE email = '%s' AND status=0",
            mysql_real_escape_string($email));
            $result = mysql_query($query);
            list($count) = mysql_fetch_row($result);
            if($count >= 1) {
                $query = sprintf("UPDATE users SET status=1 WHERE email = '%s'",
                mysql_real_escape_string($email));
                mysql_query($query);
            ?>
            <span style='color:#8F7B55'>Congratulations, you've confirmed your e-mail address!</span>
            <?php
            } else {
            ?>
            <span style='color:red'>Oops! Either that user doesn't exist, or that e-mail address has already been status.</span>
            <?php
            }
        }   
    }

    function check_session(){
        echo '<ul class="regbar">'; 
        if (isset($_SESSION['username'])) {

            echo '      <form name="logout" class="logout" method="post"> 
            <p>Hello ' . $_SESSION['username'] . ' 
            <input type="submit" value="Logout" name="logout" class="logoutbutton" />  
            </p></form>';
            if((is_Admin($_SESSION['id']))==1){

                echo '<li><a href="add_item.php">Add Item</a></li> ';
                echo '<li><a href="users_list.php">Users List</a></li> ';
                echo '<li><a href="banned_list.php">Banned List</a></li> ';  

            }

        }else{
            echo'
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            ';
        }
        echo '</ul>'; 
    }

    function is_Admin($id){
        $query = "SELECT isAdmin FROM users WHERE id='$id'";
        $result = mysql_query($query);    
        $isAdmin = mysql_result($result, 0); 
        return $isAdmin; 
    }

    function add_item(){
        if( (isset($_POST['name']) and isset($_POST['description']) )
        and (!empty($_POST['name'])) and !empty($_POST['description']) 
        and (is_numeric($_POST['health']) and is_numeric($_POST['damage']) 
        and is_numeric($_POST['defense']) and is_numeric($_POST['luck']) 
        and is_numeric($_POST['price']))){

            $name=htmlentities (mysql_real_escape_string($_POST['name']));
            $description=htmlentities (mysql_real_escape_string($_POST['description']));

            $health=$_POST['health'];
            $damage=$_POST['damage'];
            $defense=$_POST['defense'];
            $luck=$_POST['luck'];
            $price=$_POST['price'];
            $type=$_POST['type'];

            $result=mysql_query("SELECT name FROM items WHERE name='$name'");
            $name_num_rows=mysql_num_rows($result);

            if($name_num_rows!=0){
                $errors[] = '<p class="error">The item name is taken</p>';
            }

        }else{
            $errors[] = '<p class="error">Please fill the fields</p>';  
        }

        if (empty($errors)) {
            $filename = add_image();
            $query = "INSERT INTO items (name,description,img) VALUES ('$name','$description','$filename')";
            $result = mysql_query($query);
            if (mysql_affected_rows() == 1) {

                set_item_stats($health,$damage,$defense,$luck,$price,$type);      
                echo '<p id="welmsg">The item has been added.</p>';
            } else {
                echo '<p class="error">There was an error while adding the item</p>';
            }

        } else {
            echo '<p class="error">The following error(s) occured:</p>';
            foreach ($errors as $msg) {
                echo  $msg;
            }
        }
    }

    function add_image(){

        include('SimpleImage.php');
        $imag = new SimpleImage();

        if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/png")
        || ($_FILES["file"]["type"] == "image/pjpeg"))
        && ($_FILES["file"]["size"] < 2000000)){
            if ($_FILES["file"]["error"] > 0){
                echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
            }else{
                if (file_exists("images\\" . $_FILES["file"]["name"])){
                    echo $_FILES["file"]["name"] . " already exists. ";
                }else{

                    move_uploaded_file($_FILES["file"]["tmp_name"],"images\\" . $_FILES["file"]["name"]);
                }
            }
            $filename='images\\' . $_FILES["file"]["name"];

            $imag->load($filename);
            $imag->resize(150,190);
            $imag->save($filename);

            $filename=$_FILES["file"]["name"];

            return $filename;
        }else{
            echo "<div id='error'><p>Invalid file</p></div>";
            return null;
        }
    }

    function add_quest(){    
        if( (isset($_POST['name']) and isset($_POST['description']) )
        and (!empty($_POST['name'])) and !empty($_POST['description']) 
        and (is_numeric($_POST['points']) and is_numeric($_POST['exp_reward']) 
        and is_numeric($_POST['cash_reward']) and is_numeric($_POST['level']) 
        )){

            $name=htmlentities (mysql_real_escape_string($_POST['name']));
            $description=htmlentities (mysql_real_escape_string($_POST['description']));

            $points=$_POST['points'];
            $exp_reward=$_POST['exp_reward'];
            $cash_reward=$_POST['cash_reward'];
            $level=$_POST['level'];


            $result=mysql_query("SELECT name FROM quests WHERE name='$name'");
            $name_num_rows=mysql_num_rows($result);

            if($name_num_rows!=0){
                $errors[] = '<p class="error">The quest name is taken</p>';
            }

        }else{
            $errors[] = '<p class="error">Please fill the fields</p>';  
        }

        if (empty($errors)) {
            $query = "INSERT INTO quests (name,description,points,exp_reward,cash_reward,level) VALUES ('$name','$description','$points','$exp_reward','$cash_reward','$level')";
            $result = mysql_query($query);
            if (mysql_affected_rows() == 1) {

                echo '<p id="welmsg">The quest has been added.</p>';
            } else {
                echo '<p class="error">There was an error while adding the quest</p>';
            }

        } else {
            echo '<p class="error">The following error(s) occured:</p>';
            foreach ($errors as $msg) {
                echo  $msg;
            }
        }        
    }

    function equip($item_id,$user_id,$type){
        if($type=="weapon"){
            $query = "SELECT weapon_id FROM hero_inventory WHERE id='$user_id'";   
        }else {
            $query = "SELECT armor_id FROM hero_inventory WHERE id='$user_id'"; 
        }

        $result = mysql_query($query);
        $item=mysql_result($result, 0);

        if($item!="0"){
            unequip($user_id,$type);    
        }

        $query = "SELECT item_id,health,damage,defense,luck,price FROM item_stats WHERE item_id='$item_id'"; 
        $result = mysql_query($query);
        $item_row = mysql_fetch_array($result);

        $query = "SELECT stats_id,health,damage,defense,luck,cash FROM hero_stats WHERE stats_id='$user_id'"; 
        $result = mysql_query($query);
        $hero_row = mysql_fetch_array($result);

        if($hero_row['cash']>=$item_row['price']){
            $health=$hero_row['health']+$item_row['health'];
            $damage=$hero_row['damage']+$item_row['damage'];
            $defense=$hero_row['defense']+$item_row['defense'];
            $luck=$hero_row['luck']+$item_row['luck'];
            $cash=$hero_row['cash']-$item_row['price'];

            $query="UPDATE hero_stats
            SET health='$health', damage='$damage',defense='$defense',luck='$luck',cash='$cash'
            WHERE stats_id='$user_id'";
            $result = mysql_query($query);

            if($type=="weapon"){
                $query="UPDATE hero_inventory SET weapon_id='$item_id' WHERE id='$user_id'";  
            }else {
                $query="UPDATE hero_inventory SET armor_id='$item_id' WHERE id='$user_id'";     
            }
            $result = mysql_query($query);

            if (mysql_affected_rows() == 1) {
                echo ' <p class="error">You succesfully purchased the item</p> ';      
            }
        }else{
            echo ' <p class="error">You can\'t afford this item</p> '; 
        }
    }

    function unequip($user_id,$type){
        if($type=="weapon"){
            $query = "SELECT weapon_id FROM hero_inventory WHERE id='$user_id'";   
        }else {
            $query = "SELECT armor_id FROM hero_inventory WHERE id='$user_id'"; 
        }

        $result = mysql_query($query);
        $item_id=mysql_result($result, 0);

        $query = "SELECT item_id,health,damage,defense,luck,price FROM item_stats WHERE item_id='$item_id'"; 
        $result = mysql_query($query);
        $item_row = mysql_fetch_array($result);

        $query = "SELECT stats_id,health,damage,defense,luck,cash FROM hero_stats WHERE stats_id='$user_id'"; 
        $result = mysql_query($query);
        $hero_row = mysql_fetch_array($result);

        $health=$hero_row['health']-$item_row['health'];
        $damage=$hero_row['damage']-$item_row['damage'];
        $defense=$hero_row['defense']-$item_row['defense'];
        $luck=$hero_row['luck']-$item_row['luck'];
        $cash=$hero_row['cash']+($item_row['price']/2);

        $query="UPDATE hero_stats
        SET health='$health', damage='$damage',defense='$defense',luck='$luck',cash='$cash'
        WHERE stats_id='$user_id'";
        $result = mysql_query($query);

        if($type=="weapon"){
            $query="UPDATE hero_inventory SET weapon_id='0' WHERE id='$user_id'";  
        }else {
            $query="UPDATE hero_inventory SET armor_id='0' WHERE id='$user_id'";     
        }

        if (mysql_affected_rows() == 1) {
            echo ' <p class="error">You succesfully sold the item</p> ';      
        }

    }

    function battle($player_1,$player_2){

        $result = mysql_query("SELECT health,damage,defense,luck,exp,level,cash 
        FROM hero_stats WHERE stats_id=$player1;");
        $player1_row=mysql_fetch_array($result); 

        $result = mysql_query("SELECT health,damage,defense,luck,exp,level,cash 
        FROM hero_stats WHERE stats_id=$player2;");
        $player2_row=mysql_fetch_array($result);

        $player1_health=intval($player1_row['health']);
        $player1_damage=intval($player1_row['damage'] - $player2_row['luck']/5); 
        $player1_defense=intval($player1_row['defense'] + $player1_row['luck']/5);      

        $player2_health=intval($player2_row['health']);
        $player2_damage=intval($player2_row['damage'] - $player1_row['luck']/5); 
        $player2_defense=intval($player2_row['defense'] + $player2_row['luck']/5);   

        $reward=0;

        if($player1_row['cash']!=0 ){
            $reward=intval($reward+$player1_row['cash']/10);
            $player1_cash=intval($player1_row['cash']-$player1_row['cash']/10);
        }

        if($player2_row['cash']!=0 ){
            $reward=intval($reward+$player2_row['cash']/10);
            $player2_cash=intval($player2_row['cash']-$player2_row['cash']/10); 
        }

        while(1){
            $player2_health=intval($player2_health - $player1_damage + $player2_row['defense']);
            if($player2_health<=0){
                $winner=1;
                break;
            }
            $player1_health=intval($player1_health - $player2_damage + $player1_row['defense']);
            if($player1_health<=0){
                break;
                $winner=2; 
            }                
        }

        if($winner==1) {
            winner($player_1,$player_2,$reward);
            header("Location:winner.php?winner=1");  
        }else{
            winner($player_2,$player_1,$reward);
            header("Location:winner.php?winner=2");                   
        }
    }

    function winner($player_1,$player_2,$reward){
        $result = mysql_query("SELECT exp,cash
        FROM hero_stats WHERE stats_id=$player_1;");
        $row=mysql_fetch_array($result); 

        $user_cash=intval($row['cash'] + $reward);
        $user_exp= intval($row['exp']+10*$row['level']); 

        $query="UPDATE hero_stats SET exp='$user_exp',cash='$user_cash' WHERE stats_id='$player_1'";
        $result = mysql_query($query);
        check_level($player_1);

        $result = mysql_query("SELECT battles,wins,rank
        FROM battle_stats WHERE battle_id=$player_1;");
        $player1_row=mysql_fetch_array($result);

        $result = mysql_query("SELECT battles,defeats,rank
        FROM battle_stats WHERE battle_id=$player_1;");
        $player2_row=mysql_fetch_array($result);

        $player1_wins=$player1_row['wins']+1;
        $player2_defeats=$player2_row['defeats']+1;

        $player1_battles=$player1_row['battles']+1;
        $player2_battles=$player2_row['battles']+1;          

        if($player1_row['rank']>$player2_row['rank']){              
            $player1_rank=$player2_row['rank']; 
            $player2_rank=$player1_row['rank'];
        }else{
            $player1_rank=$player1_row['rank']; 
            $player2_rank=$player2_row['rank'];            
        }

        $query="UPDATE battle_stats SET battles='$player1_battles',wins='$player1_wins',rank='$player1_rank' 
        WHERE battle_id='$player_1';";
        $result = mysql_query($query);

        $query="UPDATE battle_stats SET battles='$player2_battles',defeats='$player2_defeats',rank='$player2_rank'
        WHERE battle_id='$player_2';";
        $result = mysql_query($query);           
    }

    function check_level($user_id){
        $query = "SELECT exp,level FROM hero_stats WHERE stats_id='$user_id'";
        $result = mysql_query($query);    
        $row=mysql_fetch_array($result);

        $player_exp=$row['exp'];      
        $next_level=$row['level']+1;

        $query = "SELECT exp_needed FROM levels WHERE level_id='$next_level'";
        $result = mysql_query($query);    
        $exp_needed = mysql_result($result, 0);

        if($player_exp>=$exp_needed){
            level_up($user_id,$next_level);     
        }      

    }

    function level_up($user_id,$next_level){

        $query = "SELECT health,damage,defense,luck FROM hero_stats WHERE stats_id='$user_id'";
        $result = mysql_query($query);    
        $row=mysql_fetch_array($result);  
        $health=$row['health'];
        $damage=$row['damage'];
        $defense=$row['defense'];
        $luck=$row['luck'];          

        $random_stat=rand(1,4);

        switch ($random_stat) {
            case 1:
                $health=$row['health']+20;
                break;
            case 2:
                $damage=$row['damage']+5;
                break;
            case 3:
                $defense=$row['defense']+5;
                break;
            case 4:
                $luck=$row['luck']+5;
                break;
        }

        $query="UPDATE hero_stats 
        SET level='$next_level',health='$health', damage='$damage',defense='$defense',luck='$luck' 
        WHERE stats_id='$user_id'";
        $result = mysql_query($query);             
    }

    function set_stats(){
        $health=100;
        $damage=20;
        $defense=10;
        $luck=10;
        $exp=0;
        $level=1;
        $cash=100;

        $query = "INSERT INTO hero_stats (health,damage,defense,luck,exp,level,cash) 
        VALUES ('$health','$damage','$defense','$luck','$exp','$level','$cash')";
        $result = mysql_query($query);
    }

    function set_battle_stats(){
        $battles=0;
        $wins=0;
        $defeats=0;

        $query="SELECT COUNT(id) FROM users";
        $result = mysql_query($query);    
        $rank = mysql_result($result, 0);

        $query = "INSERT INTO battle_stats (battles,wins,defeats,rank) 
        VALUES ('$battles','$wins','$defeats','$rank')";
        $result = mysql_query($query);
    }

    function set_UserStatus($value,$id){
        if($value=="Ban"){
            $query = "UPDATE users SET status=2 WHERE id='$id' ";
            $result = mysql_query($query);
            header("Location:users_list.php");       
        }else{
            $query = "UPDATE users SET status=1 WHERE id='$id' ";
            $result = mysql_query($query);
            header("Location:banned_list.php"); 
        }

    }

    function set_hero_inventory(){
        $query = "INSERT INTO hero_inventory (weapon_id,armor_id)  VALUES ('0','0')";
        $result = mysql_query($query);        
    }

    function set_item_stats($health,$damage,$defense,$luck,$price,$type) {
        $query = "INSERT INTO item_stats (health,damage,defense,luck,price,type) 
        VALUES ('$health','$damage','$defense','$luck','$price','$type')";
        $result = mysql_query($query);     
    }

    function get_UserStatus($username){
        $query = "SELECT status FROM users WHERE username='$username'";
        $result = mysql_query($query);    
        $row = mysql_fetch_object($result); 
        $status = $row->status;
        return $status;
    }

    function get_id($username){
        $query = "SELECT id FROM users WHERE username='$username'";
        $result = mysql_query($query);    
        $row = mysql_fetch_object($result); 
        $id = $row->id;
        return $id;
    }

    function get_stats($id){
        $result = mysql_query("SELECT health,damage,defense,luck,exp,level,cash FROM hero_stats WHERE stats_id=$id;");
        $row=mysql_fetch_array($result);

        echo '<tr>
        <td>Health</td>  ';
        echo '<td>'.$row['health'].'</td>';
        echo '</tr>';

        echo '<tr>
        <td>Level</td>';
        echo '<td>'.$row['level'].'</td>'; 
        echo '</tr>';

        echo '<tr>
        <td>Experience</td>';
        echo '<td>'.$row['exp'].'</td>';
        echo '</tr>';

        echo '<tr>
        <td>Damage</td>';
        echo '<td>'.$row['damage'].'</td>';
        echo '</tr>';

        echo '<tr>
        <td>Defense</td>';
        echo '<td>'.$row['defense'].'</td>';
        echo '</tr>';

        echo '<tr>
        <td>Luck</td> ';
        echo '<td>'.$row['luck'].'</td>';
        echo '</tr>';

        echo '<tr>
        <td>Cash</td>';
        echo '<td>'.$row['cash'].'</td>';
        echo '</tr>';

    }

    function get_battle_stats($id){
        $result = mysql_query("SELECT battles,wins,defeats FROM battle_stats WHERE battle_id=$id;");
        $count=mysql_num_rows($result); 
        $row=mysql_fetch_array($result);

        echo '<tr>
        <td>Battles</td>  ';
        echo '<td>'.$row['battles'].'</td>';
        echo '</tr>';

        echo '<tr>
        <td>Wins</td>';
        echo '<td>'.$row['wins'].'</td>'; 
        echo '</tr>';

        echo '<tr>
        <td>Defeats</td>';
        echo '<td>'.$row['defeats'].'</td>';
        echo '</tr>';


    }    

    function get_users(){
        $result = mysql_query("SELECT id,username,email,status FROM users WHERE status!=2 AND isAdmin!=1;");

        echo "<table class='userlist'>
        <tr>
        <th>Username</th>
        <th>Email</th> 
        <th>Status</th> 
        <th>Ban</th>
        </tr>";

        while($row = mysql_fetch_array($result)) {
            if ($row['status']==1){
                $status="Confirmed";   
            }else{
                $status="Not confirmed";
            }

            echo 
            "<form name='userform' class='regform' method='post' action='users_list.php'>
            <tr>
            <td>".$row['username']." <input type='hidden' name='id' value=".$row['id']." /> </td>
            <td>".$row['email']."</td>
            <td>".$status."</td> 
            <td> <input type='submit' value='Ban' name='submit' class='logoutbutton' /></td>
            </tr>
            </form>";
        }

    }

    function get_banned(){
        $result = mysql_query("SELECT id,username,email FROM users WHERE status='2';");

        echo "<div id='itemlist'><table class='userlist'>
        <tr>
        <th>Username</th>
        <th>Email</th>  
        <th>Restore</th>
        </tr>";

        while($row = mysql_fetch_array($result)){
            echo 
            "<form name='userform' class='regform' method='post' action='banned_list.php'>
            <tr>
            <td>".$row['username']." <input type='hidden' name='id' value=".$row['id']." /> </td>
            <td>".$row['email']."</td>
            <td> <input type='submit' value='Restore' name='submit' class='logoutbutton' /></td>
            </tr>
            </form>";
        }
    }

    function get_items(){
        $result = mysql_query("SELECT id,name,description,img,type,price FROM items
        JOIN item_stats ON (items.id=item_stats.item_id);");

        echo " <div id='itemlist'>
        <table class='userlist'>
        <tr>
        <th>Item</th>
        <th>Name</th> 
        <th>Type</th>
        <th>Descrition</th>
        <th>Price</th> 
        <th>Buy</th>
        </tr>";

        while($row = mysql_fetch_array($result)){

            $img= "images\\".$row['img'];


            echo 

            "<form name='itemform'  method='post' action='item_store.php'>
            <tr>
            <td> <img src=".$img." /></div></td>
            <td>".$row['name']." <input type='hidden' name='id' value=".$row['id']." /> </td>
            <td>".$row['type']."<input type='hidden' name='type' value=".$row['type']." /></td> 
            <td>".$row['description']."</td> 
            <td>".$row['price']."</td> 
            <td><input type='submit' value='Buy' name='submit' class='buybutton' />  </td>
            </tr>
            </form>";
        }
        echo "</table></div>";
    }

    function get_users_arena(){
        $result = mysql_query("SELECT id,username,level,rank FROM users 
        JOIN hero_stats ON (users.id=hero_stats.stats_id)
        JOIN battle_stats ON (users.id=battle_stats.battle_id)
        ORDER BY rank DESC;");

        echo "<table class='userlist'>
        <tr>
        <th>Rank</th>  
        <th>Username</th>
        <th>Level</th>  
        <th>Attack</th>
        </tr>";

        while($row = mysql_fetch_array($result)) {  

            echo 
            "<form name='userform' class='regform' method='post' action='arena.php'>
            <tr>
            <td>".$row['rank']."</td>  
            <td>".$row['username']." <input type='hidden' name='id' value=".$row['id']." /> </td>            
            <td>".$row['level']."</td> 
            <td> <input type='submit' value='Atack' name='submit' class='logoutbutton' /></td>
            </tr>
            </form>";
        }

    }
?>