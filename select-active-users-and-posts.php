<?php

//require "classes/Database.php";
require "database.php";

$results = $db->selectActiveUsersAndPosts();

print_r($results);