<?php

require "database.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Welcome!</h1>
    <p>In order for this to work, you need an active MySQL DB on your localhost with username "adir", password "142536" and db "proj_db".
        <br>
        Please follow the steps below so we can load data into our DB and then present our active users & posts page.
    </p>
    <p>At first, we "curl" users & posts data from "jsonplaceholder" API.
        <br>
        Then, we create another table with count of posts for each date & hour.
        <br>
        Lastly, we present all active users (users.active == 1) and their posts alongside "their" image.
    </p>
    
    <ol>
        <li><a href="get-users-into-table.php">Fill DB with users data.</a></li>
        <li><a href="get-posts-into-table.php">Fill DB with posts data.</a></li>
        <li><a href="get-data-into-date-hour-posts-table.php">Fill DB with data about posts' dates and amount of posts.</a></li>
        <li><a href="select-active-users-and-posts.php">Show active users and their posts (Blog View Style).</a></li>
    </ol>

</body>
</html>






