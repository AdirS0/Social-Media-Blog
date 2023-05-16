<?php

/**
 * Class to represent a user from the API.
 */
class User
{
    private $id;
    private $username;
    private $email;

    /**
     * User constructor.

     * @param int $id The id of the user.
     * @param int $username The username of the user.
     * @param int $email The email of the user.
     **/
    public function __construct($id, $username, $email)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
    }

    
}