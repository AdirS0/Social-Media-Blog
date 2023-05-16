<?php

require "classes/User.php";
require "classes/Database.php";

$ch = curl_init();
$url = "https://jsonplaceholder.typicode.com/users";
$table = 'users';

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($e = curl_error($ch)) {
    die("Error curling data: $e");
} else {
    $usersData = json_decode($response, true);
    

}

curl_close($ch);
