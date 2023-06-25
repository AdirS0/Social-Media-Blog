<?php

require "database.php";

$ch = curl_init();
$url = "https://jsonplaceholder.typicode.com/posts";
$table = 'posts';

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($e = curl_error($ch)) {
    die("Error curling data: $e");
} else {
    $postsData = json_decode($response, true);
    
    foreach ($postsData as $index => $postArr) {
        $postForDb = ['id' => $postArr['id'], 'user_id' => $postArr['userId'], 'title' => $postArr['title'], 'body' => $postArr['body']];
        
        $db->insert($table, $postForDb);
    }
}

curl_close($ch);

echo "Posts table created with data.";
