<?php

require "database.php";

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

    foreach ($usersData as $index => $userArr) {
        $userForDb = ['id' => $userArr['id'], 'username' => $userArr['username'], 'email' => $userArr['email']];
        
        $db->insert($table, $userForDb);
    }
}

curl_close($ch);
