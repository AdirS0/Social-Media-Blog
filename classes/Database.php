<?php

/**
 * A class representing a Database and its methods
 */
class Database
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection = null;

    /**
     * Database constructor
     * 
     * The constructor pulls the data from the API, creates the tables and inserts the data.
     *
     * @param string $host The database host.
     * @param string $username The username for the database connection.
     * @param string $password The password for the database connection.
     * @param string $database The name of the database.
     **/
    public function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        $this->connect();
        $this->createUsersTable();
        $this->createPostsTable();
    }

    /**
     * Database destructor to disconnect from the database.
     **/
    public function __destruct()
    {
        $this->connection = null;
    }

    /**
     * To connect to the database.
     * 
     * @throws PDOException if connection has failed.
     **/
    private function connect()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->database;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die("Connection failed: {$e->getMessage()}");
        }
    }

    /**
     * To insert data into a table.
     *
     * @param string $table Table to insert data to.
     * @param array $data Data (user/post) to insert.
     **/
    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_values($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($data);
            return $statement;
        }
        catch (PDOException $e) {
            die("Error executing query: {$e->getMessage()}");
        }
    }

    

    /**
     * Creates 'users' table.
     * 
     * @throws PDOException if creation has failed.
     **/
    private function createUsersTable()
    {
        $createQuery = "CREATE TABLE IF NOT EXISTS `proj_db`.`users` (
            `id` INT NOT NULL , 
            `username` VARCHAR(255) NOT NULL , 
            `email` VARCHAR(255) NOT NULL , 
            `active` BOOLEAN NULL , 
            PRIMARY KEY (`id`)
            );";

        try {
            if ( ! $this->connection->query($createQuery)){
                die("Query for creating 'posts' table failed");
            }
        } catch (PDOException $e) {
            die("(PDOException) Creating 'users' table failed: {$e->getMessage()}");
        }
    }

    /**
     * Creates 'posts' table.
     *
     * @throws PDOException if creation has failed.
     **/
    private function createPostsTable()
    {
        $createQuery = "CREATE TABLE IF NOT EXISTS `proj_db`.`posts` (
            `id` INT NOT NULL , 
            `user_id` INT NOT NULL , 
            `title` VARCHAR(255) NOT NULL , 
            `body` TEXT NOT NULL , 
            `published_at` DATETIME NULL , 
            `active` BOOLEAN NULL , 
            PRIMARY KEY (`id`) , 
            FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
            );";

        try {
            if ( ! $this->connection->query($createQuery)){
                die("Query for creating 'posts' table failed");
            }
        } catch (PDOException $e) {
            die("(PDOException) Creating 'posts' table failed: {$e->getMessage()}");
        }
    }

    
}
