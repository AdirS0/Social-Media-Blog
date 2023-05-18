<?php

require "database.php";

$results = $db->selectActiveUsersAndPosts();
$username = $results[0]['username'];
$email = $results[0]['email'];

echo '<div>';
echo '<div>';
echo '<img src="image.jpg" alt="User Image" width=150 height=150>';
echo '<h1>' . $username . ' ' . $email . '</h1>';
foreach ($results as $index => $row) {
    $closeDivFlag = 0;
    if ($username !== $row['username']) {
        echo '</div>';

        $username = $row['username'];
        $email = $row['email'];

        echo '<div>';
        echo '<img src="image.jpg" alt="User Image" width=150 height=150>';
        echo '<h1>' . $username . ' ' . $email . '</h1>';
    }

    $publishedAt = $row['published_at'];
    $postTitle = $row['title'];
    $postBody = $row['body'];

    echo '<h3>'. $publishedAt . ' : ' . $postTitle . '</h3>';
    echo '<p>' . $postBody . '</p>';
}
echo '</div>';
echo '</div>';