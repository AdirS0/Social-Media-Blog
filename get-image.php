<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://cdn2.vectorstock.com/i/1000x1000/23/81/default-avatar-profile-icon-vector-18942381.jpg");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$imageData = curl_exec($ch);

curl_close($ch);

file_put_contents("image.jpg", $imageData);


