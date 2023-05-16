<?php

/**
 * Class to represent a Post from the API.
 */
class Post
{
    private $id;
    private $userId;
    private $title;
    private $body;

    /**
     * Post constructor.
     * 
     * @param int $id The id of the post.
     * @param int $userId The id of the user who created the post.
     * @param int $title The title of the post.
     * @param int $body The body of the post.
     **/
    public function __construct($id, $userId, $title, $body)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->title = $title;
        $this->body = $body;
    }
}