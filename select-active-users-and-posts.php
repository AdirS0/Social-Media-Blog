<?php

require "database.php";
require "get-image.php";

$activeUsersAndPosts = $db->selectActiveUsersAndPosts();

echo '<div>';
foreach ($activeUsersAndPosts as $userPostIndex => $userPost) {
    if ($userPostIndex === 0) {
        echo '<div>';
        echo '<img src="image.jpg" alt="User Image" width=150 height=150>';
        echo '<h1>' . $userPost['username'] . ' , ' . $userPost['email'] . '</h1>';
    }
    elseif ($userPost['username'] !== $activeUsersAndPosts[$userPostIndex - 1]['username']) {
        echo '</div>';

        echo '<div>';
        echo '<img src="image.jpg" alt="User Image" width=150 height=150>';
        echo '<h1>' . $userPost['username'] . ' , ' . $userPost['email'] . '</h1>';
    }

    echo '<h3>'. $userPost['published_at'] . ' : ' . $userPost['title'] . '</h3>';
    echo '<p>' . $userPost['body'] . '</p>';
}
echo '</div>';
echo '</div>';