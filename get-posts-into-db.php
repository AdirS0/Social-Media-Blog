<?php

$ch = curl_init();
$url = "https://jsonplaceholder.typicode.com/posts";

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($e = curl_error($ch)){
    die("Error curling data: $e");
} else{
    $postsData = json_decode($response, true);
    print_r($postsData);
}

curl_close($ch);