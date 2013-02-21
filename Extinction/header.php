<head>
    <title>Extinction</title>
    <meta name="description" content="MMORPG Browser-Based Game">
    <meta name="keywords" content="Extinction,MMO,RPG,Game,Hero,Quest,Arena">
    <meta name="author" content="Nikola Rusev">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<div class="regbar">
    <?php
        require_once('connect_db.php');
        require_once('functions.php');


        if(isset($_POST['logout'])){
            session_destroy();
            header("Location: index.php"); 
        }
        check_session();

    ?>
</div>    


<div id="title">
    <h1>Extinction</h1>
</div>

<div class="navbar">    
    <ul class="navbar">
        <li><a href="index.php">Home</a></li>
        <li><a href="hero_panel.php">Your Hero</a></li> 
        <li><a href="item_store.php">Item Store</a></li>
        <li><a href="arena.php">Arena</a></li>  
        <li><a href="quests.php">Quests</a></li>  
    </ul>  
        

    </div>

